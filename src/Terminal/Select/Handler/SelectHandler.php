<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Handler;

use Ninja\Cosmic\Terminal\Select\Input\SelectInputInterface;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\terminal;

class SelectHandler
{
    public const SIMPLE_CTR  = 0x01;
    public const COMPLEX_CTR = 0x02;

    public const DEFAULT_CTR = self::COMPLEX_CTR;

    protected int $row;
    protected int $column;
    protected bool $firstRun     = false;
    protected int $terminalWidth = 0;

    /**
     * @param resource $stream
     */
    public function __construct(
        protected SelectInputInterface $question,
        protected OutputInterface $output,
        protected $stream = STDIN,
        protected ?int $columns = null,
    ) {
        $this->row    = 0;
        $this->column = 0;
    }

    /**
     * Navigates through option items.
     *
     * @return list<string>
     */
    public function handle(): array
    {
        $this->firstRun = true;
        $ctrlMode       = $this->question->controlMode();
        $usage          = $ctrlMode === self::SIMPLE_CTR ? '[<comment>ENTER=select</>]' : '[<comment>SPACE=select</>, <comment>ENTER=submit</>]';

        $this->output->writeln('');
        $this->output->writeln('<text>' . $this->question->getMessage() . '</text> ' . $usage);
        $this->repaint();

        $sttyMode = shell_exec('stty -g');
        shell_exec('stty -icanon -echo');

        // Read a keypress
        while (!feof($this->stream)) {
            $char = fread($this->stream, 1);
            if (" " === $char && $ctrlMode !== self::SIMPLE_CTR) {
                $this->tryCellSelection();
            } elseif ("\033" === $char) {
                $this->tryCellNavigation($char, $ctrlMode);
            } elseif (10 === ord($char)) {
                if ($ctrlMode === self::SIMPLE_CTR && !$this->question->hasSelections()) {
                    $this->tryCellSelection();
                }
                $this->clear();
                break;
            }
            $this->repaint();
        }

        shell_exec(sprintf('stty %s', $sttyMode));
        $this->output->writeln(' ');
        $this->finalClear();

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
            $this->column += 1;
        }
    }

    protected function tryCellSelection(): void
    {
        // Try to select cell.
        if ($this->exists($this->row, $this->column)) {
            $option = $this->question->getEntryAt($this->row, $this->column);
            $this->question->select($option);
        }
    }

    protected function tryCellNavigation(string $char, int $ctrlMode = self::DEFAULT_CTR): void
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
            $this->tryCellSelection();
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
        $lines = $this->question->getChunksCount() - 1;
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
        $chunkSize   = $this->getColumns();
        $chunks      = $this->question->getChunks($chunkSize);
        $columnSpace = (int)floor(($this->terminalWidth() - ($chunkSize * 5)) / $chunkSize);
        return implode(PHP_EOL, array_map(function ($entries) use ($chunks, $columnSpace) {
            $hasCursor = $this->row === array_search($entries, $chunks, true);
            return $this->makeRow($entries, ($hasCursor ? $this->column : -10), $columnSpace);
        }, $chunks));
    }

    protected function makeRow(array $entries, int $activeColumn, int $columnSpace): string
    {
        return array_reduce($entries, function ($carry, $item) use ($entries, $activeColumn, $columnSpace) {
            $isActive = $activeColumn === array_search($item, $entries, true);
            return $carry . $this->makeCell($item, $isActive, $columnSpace);
        }, '');
    }

    protected function makeCell(string $option, bool $hasCursor = false, int $maxWidth = 20): string
    {
        $selected = $this->question->isSelected($option);
        $name     = substr($option, 0, ($maxWidth - 1));

        return sprintf(
            $hasCursor ? ' <hl> %1$s %2$s </hl>' : ' <%3$s> %1$s %2$s </%3$s>',
            ($selected ? Terminal::getTheme()->getIcon("selected") : Terminal::getTheme()->getIcon("unselected")),
            $name . str_repeat(' ', $maxWidth - mb_strlen($name)),
            ($selected ? 'info' : 'comment')
        );
    }

    public function terminalWidth(): int
    {
        return terminal()->width();
    }

    public function getColumns(): int
    {
        return $this->columns ?? $this->chunkSize();
    }

    public function chunkSize(): int
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

}
