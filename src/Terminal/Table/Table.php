<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Table;

use Ninja\Cosmic\Terminal\Table\Manipulator\ManipulatorFactory;

class Table
{
    protected ?array $injectedData = null;
    protected array $fields        = [];
    protected bool $showHeaders    = true;
    protected string $tableColor   = 'reset';
    protected string $headerColor  = 'reset';
    protected array $colors        = [];
    protected array $chars         = [
        'top'          => '═',
        'top-mid'      => '╤',
        'top-left'     => '╔',
        'top-right'    => '╗',
        'bottom'       => '═',
        'bottom-mid'   => '╧',
        'bottom-left'  => '╚',
        'bottom-right' => '╝',
        'left'         => '║',
        'left-mid'     => '╟',
        'mid'          => '─',
        'mid-mid'      => '┼',
        'right'        => '║',
        'right-mid'    => '╢',
        'middle'       => '│ ',
    ];

    public function __construct(
        protected string $itemName = 'Row',
        protected bool $useColors = true,
        protected bool $centerContent = false
    ) {
        $this->defineColors();
    }

    public function getUseColors(): bool
    {
        return $this->useColors;
    }

    public function getCenterContent(): bool
    {
        return $this->centerContent;
    }

    public function setTableColor($color): self
    {
        $this->tableColor = $color;
        return $this;
    }

    public function getTableColor(): string
    {
        return $this->tableColor;
    }

    public function setChars(array $chars): self
    {
        $this->chars = $chars;
        return $this;
    }

    public function setHeaderColor($color): self
    {
        $this->headerColor = $color;
        return $this;
    }

    public function getHeaderColor(): string
    {
        return $this->headerColor;
    }

    public function setItemName(string $name): self
    {
        $this->itemName = $name;
        return $this;
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function injectData(array $data): self
    {
        $this->injectedData = $data;
        return $this;
    }

    public function getInjectedData(): ?array
    {
        return $this->injectedData;
    }

    public function showHeaders(): self
    {
        $this->showHeaders = true;
        return $this;
    }

    public function hideHeaders(): self
    {
        $this->showHeaders = false;
        return $this;
    }

    public function getShowHeaders(): bool
    {
        return $this->showHeaders;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    protected function getPluralItemName(): string
    {
        if (count($this->injectedData) === 1) {
            return $this->getItemName();
        }

        $lastChar = strtolower(substr($this->getItemName(), strlen($this->getItemName()) - 1, 1));
        if ($lastChar === 's') {
            return $this->getItemName() . 'es';
        }

        if ($lastChar === 'y') {
            return substr($this->getItemName(), 0, -1) . 'ies';
        }

        return $this->getItemName() . 's';
    }

    public function addField(
        string $fieldName,
        string $fieldKey,
        ?string $manipulator = null,
        string $color = 'reset'
    ): self {
        $this->fields[$fieldKey] = [
            'name'        => $fieldName,
            'key'         => $fieldKey,
            'manipulator' => $manipulator,
            'color'       => $color,
        ];

        return $this;
    }

    /**
     * get
     *
     * @access public
     * @return string
     */
    public function get(): string
    {
        $rowCount      = 0;
        $columnLengths = [];
        $headerData    = [];
        $cellData      = [];

        // Headers
        if ($this->getShowHeaders()) {
            foreach ($this->fields as $field) {
                $headerData[$field['key']] = trim($field['name']);

                // Column Lengths
                if (!isset($columnLengths[$field['key']])) {
                    $columnLengths[$field['key']] = 0;
                }
                $columnLengths[$field['key']] = max($columnLengths[$field['key']], strlen(trim($field['name'])));
            }
        }

        // Data
        if ($this->injectedData !== null) {
            if (count($this->injectedData)) {
                foreach ($this->injectedData as $row) {
                    // Row
                    $cellData[$rowCount] = [];
                    foreach ($this->fields as $field) {
                        $key   = $field['key'];
                        $value = $row[$key];
                        if ($field['manipulator']) {
                            $value = ManipulatorFactory::create($field['manipulator'])->manipulate($value);
                            //$value = trim($field['manipulator']->manipulate($value, $row, $field['name']));
                        }

                        $cellData[$rowCount][$key] = $value;

                        // Column Lengths
                        if (!isset($columnLengths[$key])) {
                            $columnLengths[$key] = 0;
                        }
                        $c     = chr(27);
                        $lines = explode("\n", preg_replace("/({$c}\[(.*?)m)/s", '', $value));
                        foreach ($lines as $line) {
                            $columnLengths[$key] = max($columnLengths[$key], mb_strlen($line));
                        }
                    }
                    $rowCount++;
                }
            } else {
                return 'There are no ' . $this->getPluralItemName() . PHP_EOL;
            }
        } else {
            return 'There is no injected data for the table!' . PHP_EOL;
        }

        $response = '';

        $screenWidth = trim(exec("tput cols"));

        // Idea here is we're column the accumulated length of the data
        // Then adding the quantity of column lengths to accommodate for the extra characters
        //     for when vertical pipes are placed between each column
        $dataWidth = mb_strlen($this->getTableTop($columnLengths)) + count($columnLengths);

        $spacing = '';

        // Only try and center when content is less than available space
        if ($this->getCenterContent() && (($dataWidth / 2) < $screenWidth)) {
            $spacing = str_repeat(' ', ((int)$screenWidth - ($dataWidth / 2)) / 2);
        }

        // Now draw the table!
        $response .= $spacing . $this->getTableTop($columnLengths);
        if ($this->getShowHeaders()) {
            $response .= $spacing . $this->getFormattedRow($headerData, $columnLengths, true);
            $response .= $spacing . $this->getTableSeparator($columnLengths);
        }

        foreach ($cellData as $row) {
            $response .= $spacing . $this->getFormattedRow($row, $columnLengths);
        }

        $response .= $spacing . $this->getTableBottom($columnLengths);

        return $response;
    }

    /**
     * getFormattedRow
     *
     * @access protected
     * @param array $rowData
     * @param array $columnLengths
     * @param bool $header
     * @return string
     */
    protected function getFormattedRow(array $rowData, array $columnLengths, bool $header = false): string
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
                    $color = $this->getHeaderColor();
                } else {
                    $color = $this->fields[$key]['color'];
                }

                $line = $lines[$i] ?? '';

                $c          = chr(27);
                $lineLength = mb_strwidth(preg_replace("/({$c}\[(.*?)m)/", '', $line)) + 1;
                $line       = ' ' . ($this->getUseColors() ? $this->getColorFromName($color) : '') . $line;
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

    /**
     * getTableTop
     *
     * @access protected
     * @param array $columnLengths
     * @return string
     */
    protected function getTableTop(array $columnLengths): string
    {
        $response = $this->getChar('top-left');
        foreach ($columnLengths as $length) {
            $response .= $this->getChar('top', $length + 2);
            $response .= $this->getChar('top-mid');
        }

        return substr($response, 0, -3) . $this->getChar('top-right') . PHP_EOL;
    }

    /**
     * getTableBottom
     *
     * @access protected
     * @param array $columnLengths
     * @return string
     */
    protected function getTableBottom(array $columnLengths): string
    {
        $response = $this->getChar('bottom-left');
        foreach ($columnLengths as $length) {
            $response .= $this->getChar('bottom', $length + 2);
            $response .= $this->getChar('bottom-mid');
        }

        return substr($response, 0, -3) . $this->getChar('bottom-right') . PHP_EOL;
    }

    protected function getTableSeparator(array $columnLengths): string
    {
        $response = $this->getChar('left-mid');
        foreach ($columnLengths as $length) {
            $response .= $this->getChar('mid', $length + 2);
            $response .= $this->getChar('mid-mid');
        }

        return substr($response, 0, -3) . $this->getChar('right-mid') . PHP_EOL;
    }

    /**
     * getChar
     *
     * @access protected
     * @param string $type
     * @param int $length
     * @return string
     */
    protected function getChar(string $type, int $length = 1): string
    {
        $response = '';
        if (isset($this->chars[$type])) {
            if ($this->getUseColors()) {
                $response .= $this->getColorFromName($this->getTableColor());
            }
            $char = trim($this->chars[$type]);
            for ($x = 0; $x < $length; $x++) {
                $response .= $char;
            }
        }
        return $response;
    }

    protected function defineColors(): void
    {
        $this->colors = [
            'blue'    => chr(27) . '[1;34m',
            'red'     => chr(27) . '[1;31m',
            'green'   => chr(27) . '[1;32m',
            'yellow'  => chr(27) . '[1;33m',
            'black'   => chr(27) . '[1;30m',
            'magenta' => chr(27) . '[1;35m',
            'cyan'    => chr(27) . '[1;36m',
            'white'   => chr(27) . '[1;37m',
            'grey'    => chr(27) . '[0;37m',
            'reset'   => chr(27) . '[0m',
        ];
    }

    /**
     * getColorFromName
     *
     * @access protected
     * @param string $colorName
     * @return string
     */
    protected function getColorFromName(string $colorName): string
    {
        return $this->colors[$colorName] ?? $this->colors['reset'];
    }

    public function display(): void
    {
        print $this->get();
    }

}
