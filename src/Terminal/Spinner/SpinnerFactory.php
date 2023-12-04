<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Spinner;

use Exception;
use Ninja\Cosmic\Config\Env;
use Symfony\Component\Process\Process;

class SpinnerFactory extends Spinner
{
    /**
     * @throws Exception
     */
    public static function for(Process | callable $callable, string $message): bool
    {
        return $callable instanceof Process ?
            self::forProcess($callable, $message) :
            self::forCallable($callable, $message);
    }

    /**
     * @throws Exception
     */
    private static function forProcess(Process $process, string $message): bool
    {
        $spinner = new self();
        $spinner->setMessage($message);

        return $spinner->callback(static function () use ($process): bool {
            if (!$process->isRunning()) {
                $process->start();
            }

            while ($process->isRunning()) {
                usleep(1000);
            }

            if (Env::isDebug()) {
                print $process->getErrorOutput();
            }

            return $process->isSuccessful();
        });
    }

    /**
     * @throws Exception
     */
    private static function forCallable(callable $callback, string $message): bool
    {
        $spinner = new self();
        $spinner->setMessage($message);

        return $spinner->callback($callback);
    }
}
