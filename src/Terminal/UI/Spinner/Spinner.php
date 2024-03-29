<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Spinner;

use Exception;
use Ninja\Cosmic\Signal\SignalHandler;
use Ninja\Cosmic\Terminal\Terminal;
use RuntimeException;

class Spinner
{
    final public const DEFAULT_SPINNER_STYLE    = 'dots8Bit';
    final public const DEFAULT_SPINNER_INTERVAL = 1000;
    final public const DEFAULT_SPINNER_PADDING  = 2;
    final public const CLEAR_LINE               = "\33[2K\r";
    final public const RETURN_TO_LEFT           = "\r";

    private int $child_pid = 0;

    /** @var array{
     *   frames: string[],
     *   interval: int
     * }|null
     */
    private ?array $spinner;

    private int $padding = self::DEFAULT_SPINNER_PADDING;
    private string $message;

    public function __construct(
        private readonly ?string $style = null
    ) {
        $config = Terminal::getTheme()?->getConfig("spinner");
        $style  = $this->style ?? $config["style"] ?? self::DEFAULT_SPINNER_STYLE;

        $this->spinner = Terminal::getTheme()?->getSpinners()->spinner($style)?->toArray();
    }

    public function setMessage(string $message): self
    {
        $this->message = Terminal::render($message) ?? "";
        return $this;
    }

    public function setPadding(int $padding): self
    {
        $this->padding = $padding;
        return $this;
    }

    /**
     * @return array<string>
     */
    private function getSpinnerFrames(): array
    {
        return $this->spinner["frames"] ?? [];
    }

    private function loopSpinnerFrames(): void
    {
        Terminal::hideCursor();

        /** @phpstan-ignore-next-line */
        while (true) {
            foreach ($this->getSpinnerFrames() as $frame) {
                $parsed_frame = Terminal::render(
                    sprintf(
                        "%s<info>%s</info> %s%s",
                        $this->addPadding(),
                        $frame,
                        $this->message,
                        self::RETURN_TO_LEFT
                    )
                ) ?? "";

                Terminal::output()->write($parsed_frame);
                $interval = $this->spinner["interval"] ?? self::DEFAULT_SPINNER_INTERVAL;
                usleep($interval * self::DEFAULT_SPINNER_INTERVAL);
            }
        }
    }

    private function addPadding(): string
    {
        return str_repeat(' ', $this->padding);
    }

    private function reset(): void
    {
        Terminal::output()->write(self::CLEAR_LINE);
        Terminal::restoreCursor();
    }

    private function keyboardInterrupts(): void
    {
        // Keyboard interrupts. E.g. ctrl-c
        // Exit both parent and child process
        // They are both running the same code

        $keyboard_interrupts = function (int $signal): never {
            posix_kill($this->child_pid, SIGTERM);
            $this->reset();
            exit($signal);
        };

        pcntl_signal(SIGINT, $keyboard_interrupts);
        pcntl_signal(SIGTSTP, $keyboard_interrupts);
        pcntl_signal(SIGQUIT, $keyboard_interrupts);
        pcntl_async_signals(true);
    }

    /**
     * @throws Exception
     */
    public function callback(callable $callback): mixed
    {
        if (!extension_loaded('pcntl') || !posix_isatty(STDOUT)) {
            return $callback();
        }

        SignalHandler::reset();

        return $this->runCallBack($callback);
    }

    private function runCallBack(callable $callback): mixed
    {
        $child_pid = pcntl_fork();
        if ($child_pid === -1) {
            throw new RuntimeException('Could not fork process');
        }

        if ($child_pid !== 0) {
            $this->keyboardInterrupts();
            $this->child_pid = $child_pid;
            $res             = $callback();
            posix_kill($child_pid, SIGTERM);

            if ($res !== false) {
                $this->success();
            } else {
                $this->failure();
            }

            return $res;
        }

        $this->loopSpinnerFrames();

        SignalHandler::restore();

        return null;
    }

    private function success(): void
    {
        $this->reset();
        Terminal::output()->writeln(
            sprintf(
                "%s<success>%s</success> %s",
                $this->addPadding(),
                Terminal::getTheme()?->getIcon("success"),
                $this->message
            )
        );
    }

    private function failure(): void
    {
        $this->reset();
        Terminal::output()->writeln(
            sprintf(
                "%s<warn>%s</warn> %s",
                $this->addPadding(),
                Terminal::getTheme()?->getIcon("failure"),
                $this->message
            )
        );
    }
}
