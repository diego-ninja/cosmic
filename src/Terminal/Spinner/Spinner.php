<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Spinner;

use Exception;
use JsonException;
use Ninja\Cosmic\Terminal\Terminal;
use RuntimeException;

class Spinner
{
    public const DEFAULT_SPINNER_STYLE    = 'dots13';
    public const DEFAULT_SPINNER_INTERVAL = 1000;
    public const DEFAULT_SPINNER_PADDING  = 2;
    public const BLINK_OFF                = "\e[?25l";
    public const BLINK_ON                 = "\e[?25h";
    public const CLEAR_LINE               = "\33[2K\r";
    public const RETURN_TO_LEFT           = "\r";

    private int $child_pid = 0;

    private array $styles;
    private array $spinner;

    private int $padding = self::DEFAULT_SPINNER_PADDING;
    private string $message;

    /**
     * @throws JsonException
     */
    public function __construct(
        private readonly string $style = self::DEFAULT_SPINNER_STYLE
    ) {
        $this->loadStyles();
        $this->spinner = $this->styles[$this->style];
    }

    public function setMessage(string $message): self
    {
        $this->message = Terminal::render($message);
        return $this;
    }

    public function setPadding(int $padding): self
    {
        $this->padding = $padding;
        return $this;
    }

    private function loadStyles(): void
    {
        $spinner_style_file = __DIR__ . '/spinners.json';
        if (!file_exists($spinner_style_file)) {
            throw new RuntimeException(sprintf('Spinner styles definitions file %s not found', $spinner_style_file));
        }

        try {
            $spinner_json = file_get_contents($spinner_style_file);
            $this->styles = json_decode($spinner_json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Could not load spinner styles', 0, $e);
        }
    }

    /**
     * @return array<string>
     */
    private function getSpinnerFrames(): array
    {
        return $this->spinner["frames"];
    }

    private function loopSpinnerFrames(): void
    {
        print self::BLINK_OFF;
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
                );

                Terminal::output()->write($parsed_frame);
                usleep($this->spinner["interval"] * self::DEFAULT_SPINNER_INTERVAL);
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
        Terminal::output()->write(self::BLINK_ON);
    }

    private function keyboardInterrupts(): void
    {
        // Keyboard interrupts. E.g. ctrl-c
        // Exit both parent and child process
        // They are both running the same code

        $keyboard_interrupts = function (int $signal) {
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
        return $this->runCallBack($callback);
    }

    private function runCallBack(callable $callback): mixed
    {
        $child_pid = pcntl_fork();
        if ($child_pid === -1) {
            throw new RuntimeException('Could not fork process');
        }

        if ($child_pid) {
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
        return null;
    }

    private function success(): void
    {
        $this->reset();
        Terminal::output()->writeln(
            sprintf(
                "%s<green>%s</green> %s",
                $this->addPadding(),
                Terminal::getTheme()->getIcon("success"),
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
                Terminal::getTheme()->getIcon("failure"),
                $this->message
            )
        );
    }
}
