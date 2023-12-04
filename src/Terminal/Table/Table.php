<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table;

use Ninja\Cosmic\Terminal\Table\Column\ColumnCollection;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Output\OutputInterface;

use function Cosmic\pluralize;

class Table
{
    protected ColumnCollection $columns;

    public function __construct(
        protected array $data,
        ?array $columns,
        protected TableConfig $config
    ) {
        $this->columns = new ColumnCollection($columns);
    }

    public function injectData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getColumns(): ColumnCollection
    {
        return $this->columns;
    }

    public function addColumn(TableColumn $field): self
    {
        $this->columns->add($field);
        return $this;
    }

    public function display(?OutputInterface $output): void
    {
        $output ? $output->writeln($this->render()) : Terminal::output()->writeln($this->render());
    }

    public function render(): string
    {
        $rowCount      = 0;
        $columnLengths = [];
        $headerData    = [];
        $cellData      = [];

        // Headers
        if ($this->config->getShowHeader()) {
            foreach ($this->getColumns() as $column) {
                /** @var TableColumn $column */
                $headerData[$column->key] = trim($column->name);

                if (!isset($columnLengths[$column->key])) {
                    $columnLengths[$column->key] = 0;
                }
                $columnLengths[$column->key] = max($columnLengths[$column->key], strlen(trim($column->name)));
            }
        }

        // Data
        if (!empty($this->data)) {
            foreach ($this->data as $row) {
                // Row
                $cellData[$rowCount] = [];
                foreach ($this->getColumns() as $column) {
                    /** @var TableColumn $column */
                    $key                       = $column->key;
                    $value                     = $column->getManipulators()->apply($row[$key]);
                    $cellData[$rowCount][$key] = $value;

                    // Column Lengths
                    if (!isset($columnLengths[$key])) {
                        $columnLengths[$key] = 0;
                    }

                    $c     = chr(27);
                    $lines = explode("\n", preg_replace("/($c\[(.*?)m)/s", '', $value));
                    foreach ($lines as $line) {
                        $columnLengths[$key] = max($columnLengths[$key], mb_strlen($line));
                    }
                }
                $rowCount++;
            }
        } else {
            return 'There are no ' . $this->getPluralItemName() . PHP_EOL;
        }

        $response = '';

        $screenWidth = \Termwind\terminal()->width();

        // Idea here is we're column the accumulated length of the data
        // Then adding the quantity of column lengths to accommodate for the extra characters
        //     for when vertical pipes are placed between each column
        $dataWidth = mb_strlen($this->getTableTop($columnLengths)) + count($columnLengths);

        // Only try and center when content is less than available space
        if ((($dataWidth / 2) < $screenWidth) && $this->config->getCenterContent()) {
            $padding = str_repeat(' ', ($screenWidth - ($dataWidth / 2)) / 2);
        } else {
            $padding = str_repeat(' ', $this->config->getPadding());
        }

        // Now draw the table!
        $response .= $padding . $this->getTableTop($columnLengths);
        if ($this->config->getShowHeader()) {
            $response .= $padding . $this->formatRow($headerData, $columnLengths, true);
            $response .= $padding . $this->getTableSeparator($columnLengths);
        }

        foreach ($cellData as $row) {
            $response .= $padding . $this->formatRow($row, $columnLengths);
        }

        $response .= $padding . $this->getTableBottom($columnLengths);

        return $response;
    }

    protected function formatRow(array $rowData, array $columnLengths, bool $header = false): string
    {
        $response = '';

        $splitLines = [];
        $maxLines   = 1;
        foreach ($rowData as $key => $line) {
            $splitLines[$key] = explode("\n", $line);
            $maxLines         = max($maxLines, count($splitLines[$key]));
        }

        for ($i = 0; $i < $maxLines; $i++) {
            $response .= $this->getChar('left');

            foreach ($splitLines as $key => $lines) {
                if ($header) {
                    $color = $this->config->getHeaderColor();
                } else {
                    $color = $this->columns->getByKey($key)->color ?? $this->config->getFieldColor();
                }

                $line = $lines[$i] ?? '';

                $c          = chr(27);
                $lineLength = mb_strwidth(preg_replace("/($c\[(.*?)m)/", '', $line)) + 1;
                $line       = sprintf(" <%s>%s</%s>", $color, $line, $color);
                $response .= $line;

                for ($x = $lineLength; $x < ($columnLengths[$key] + 2); $x++) {
                    $response .= ' ';
                }
                $response .= $this->getChar('middle');
            }

            $response = substr($response, 0, -3) . $this->getChar('right') . PHP_EOL;
        }

        return $response;
    }

    protected function getTableTop(array $columnLengths): string
    {
        $color = $this->config->getTableColor();

        $response = Terminal::color($color)->apply($this->getChar('top-left'));
        foreach ($columnLengths as $length) {
            $response .= Terminal::color($color)
                ->apply($this->getChar('top', $length + 2));
            $response .= $this->getChar('top-mid');
        }

        return
            substr($response, 0, -3) .
            Terminal::color($color)->apply($this->getChar('top-right')) .
            PHP_EOL;
    }

    protected function getTableBottom(array $columnLengths): string
    {
        $color = $this->config->getTableColor();

        $response = Terminal::color($color)->apply($this->getChar('bottom-left'));
        foreach ($columnLengths as $length) {
            $response .= Terminal::color($color)
                ->apply($this->getChar('bottom', $length + 2));
            $response .= $this->getChar('bottom-mid');
        }

        return
            substr($response, 0, -3) .
            Terminal::color($color)->apply($this->getChar('bottom-right')) .
            PHP_EOL;
    }

    protected function getTableSeparator(array $columnLengths): string
    {
        $color = $this->config->getTableColor();

        $response = Terminal::color($color)->apply($this->getChar('left-mid'));
        foreach ($columnLengths as $length) {
            $response .= Terminal::color($color)
                ->apply($this->getChar('mid', $length + 2));

            $response .= $this->getChar('mid-mid');
        }

        return
            substr($response, 0, -3) .
            Terminal::color($color)->apply($this->getChar('right-mid')) .
            PHP_EOL;
    }

    protected function getChar(string $type, int $length = 1): string
    {
        $response = '';
        if ($this->config->hasChar($type)) {
            $char     = trim($this->config->getChar($type));
            $response = str_repeat($char, $length);
        }

        return $response;

    }

    protected function getPluralItemName(): string
    {
        $itemName = $this->config->getItemName();

        if (count($this->data) === 1) {
            return $itemName;
        }

        return pluralize($itemName);
    }
}
