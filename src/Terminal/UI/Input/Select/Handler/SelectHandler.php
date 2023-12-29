<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Input\Select\Handler;

use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Input\Select\Input\CheckboxInput;
use Ninja\Cosmic\Terminal\UI\Input\Select\Input\ColumnAwareInterface;
use Ninja\Cosmic\Terminal\UI\Input\Select\Input\SelectInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\terminal;

class SelectHandler
{
    final public const SIMPLE_CTR  = 0x01;
    final public const COMPLEX_CTR = 0x02;

    final public const DEFAULT_CTR = self::COMPLEX_CTR;

    protected int $row = 0;
    protected int $column = 0;
    protected bool $firstRun = false;

    /**
     * @param resource $stream
     */
    public function __construct(protected SelectInputInterface & ColumnAwareInterface $question, protected OutputInterface $output, protected mixed $stream = STDIN, protected ?int $columns = null, protected ?int $terminalWidth = null) {}

    /**
     * Navigates through option items.
     *
     * @return list<string>
     */
    public function handle(): array
    {
        $this->firstRun = true;
        $ctrlMode       = $this->question->controlMode();
        $usage          = $ctrlMode === self::SIMPLE_CTR ?
            '[<question>ENTER=select</question>]' :
            '[<question>SPACE=select</question>, <question>ENTER=submit</question>]';

        $this->output->writeln('');
        $this->output->writeln('<text>' . $this->question->getMessage() . '</text> ' . $usage);
        $this->repaint();

        $sttyMode = shell_exec('stty -g');
        shell_exec('stty -icanon -echo');

        Terminal::hideCursor();

        // Read a keypress
        while (!feof($this->stream)) {
            $char = fread($this->stream, 1);
            if (" " === $char && $ctrlMode !== self::SIMPLE_CTR) {
                $this->select();
            } elseif ("\033" === $char) {
                $this->navigate($char, $ctrlMode);
            } elseif (10 === ord((string) $char)) {
                if ($ctrlMode === self::SIMPLE_CTR && !$this->question->hasSelections()) {
                    $this->select();
                }
                $this->clear();
                break;
            }
            $this->repaint();
        }

        shell_exec(sprintf('stty %s', $sttyMode));
        $this->output->writeln(' ');
        $this->finalClear();

        Terminal::restoreCursor();

        return $this->question->getSelections();
    }

    public function exists(int $row, int $column): bool
    {
        return $this->question->hasEntryAt($row, $column);
    }

    protected function up(): void
    {
        if ($this->exists($this->row - 1, $this->column)) {
            --$this->row;
        }
    }

    protected function down(): void
    {
        if ($this->exists($this->row + 1, $this->column)) {
            ++$this->row;
        }
    }

    protected function left(): void
    {
        if ($this->exists($this->row, $this->column - 1)) {
            --$this->column;
        }
    }

    protected function right(): void
    {
        if ($this->exists($this->row, $this->column + 1)) {
            ++$this->column;
        }
    }

    protected function select(): void
    {
        // Try to select cell.
        if ($this->exists($this->row, $this->column)) {
            $option = $this->question->getEntryAt($this->row, $this->column);
            if ($this->question->isSelected($option)) {
                $this->question->deselect($option);
            } else {
                $this->question->select($option);
            }
        }
    }

    protected function navigate(string $char, int $ctrlMode = self::DEFAULT_CTR): void
    {
        $char .= fread($this->stream, 2);
        if (empty($char[2]) || !in_array($char[2], ['A', 'B', 'C', 'D'])) {
            return;
        }

        switch ($char[2]) {
            case 'A': // go up!
                $this->up();
                break;
            case 'B': // go down!
                $this->down();
                break;
            case 'C': // go right!
                $this->right();
                break;
            case 'D': // go left!
                $this->left();
                break;
        }
        if ($ctrlMode === self::SIMPLE_CTR) {
            $this->select();
        }
    }

    public function repaint(): void
    {
        $message = $this->message();
        if (!$this->firstRun) {
            $this->clear();
        }

        $this->firstRun = false;
        $this->output->write($message);
    }

    public function clear(): void
    {
        $this->output->write("\x0D");
        $this->output->write("\x1B[2K");
        $lines = $this->question->getColumnCount() - 1;
        if ($lines > 0) {
            $this->output->write(str_repeat("\x1B[1A\x1B[2K", $lines));
        }
    }

    public function finalClear(): void
    {
        $this->output->write("\033[2A");
        $this->output->write("\033[2K");
        $this->output->write("\033[1A");
        $this->output->write("\033[2K");
    }

    protected function message(): string
    {
        $columnSize  = $this->getColumns();
        $columns     = $this->question->getColumns($columnSize);
        $columnSpace = (int)floor(($this->terminalWidth() - ($columnSize * 5)) / $columnSize);

        return implode(PHP_EOL, array_map(function ($entries) use ($columns, $columnSpace): string {
            $hasCursor = $this->row === array_search($entries, $columns, true);
            return $this->makeRow($entries, ($hasCursor ? $this->column : -10), $columnSpace);
        }, $columns));
    }

    /**
     * @param array<string> $entries
     */
    protected function makeRow(array $entries, int $activeColumn, int $columnSpace): string
    {
        return array_reduce($entries, function (string $carry, $item) use ($entries, $activeColumn, $columnSpace): string {
            $isActive = $activeColumn === array_search($item, $entries, true);
            return $carry . $this->makeCell($item, $isActive, $columnSpace);
        }, '');
    }

    protected function makeCell(string $option, bool $hasCursor = false, int $maxWidth = 20): string
    {
        $selected = $this->question->isSelected($option);
        $name     = substr($option, 0, ($maxWidth - 1));

        return
            $this->isMultiple() ?
                $this->checkbox($name, $selected, $hasCursor, $maxWidth) :
                $this->radio($name, $selected, $hasCursor, $maxWidth);
    }

    protected function checkbox(string $name, bool $selected, bool $hasCursor, int $maxWidth): string
    {
        return sprintf(
            $hasCursor ? ' <hl> %1$s %2$s </hl>' : ' <%3$s> %1$s %2$s </%3$s>',
            ($selected ? Terminal::getTheme()->getIcon("checkbox_selected") : Terminal::getTheme()->getIcon("checkbox")),
            $name . str_repeat(' ', $maxWidth - mb_strlen($name)),
            ($selected ? 'info' : 'question')
        );
    }

    protected function radio(string $name, bool $selected, bool $hasCursor, int $maxWidth): string
    {
        return sprintf(
            $hasCursor ? ' <hl> %1$s %2$s </hl>' : ' <%3$s> %1$s %2$s </%3$s>',
            ($selected ? Terminal::getTheme()->getIcon("radio_selected") : Terminal::getTheme()->getIcon("radio")),
            $name . str_repeat(' ', $maxWidth - mb_strlen($name)),
            ($selected ? 'info' : 'question')
        );
    }

    public function terminalWidth(): int
    {
        return $this->terminalWidth ?? terminal()->width();
    }

    public function getColumns(): int
    {
        return $this->columns ?? $this->columnSize();
    }

    public function columnSize(): int
    {
        $max     = $this->terminalWidth();
        $largest = array_reduce($this->question->getOptions(), 'max', 0);

        if ($largest > ($max / 2)) {
            return 1;
        }

        if ($largest > ($max / 3)) {
            return 2;
        }

        return 3;
    }

    protected function isMultiple(): bool
    {
        return $this->question instanceof CheckboxInput;
    }

}
