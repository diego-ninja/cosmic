<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Spinner;

use Exception;
use Symfony\Component\Process\Process;

class ProcessSpinner extends Spinner
{
    /**
     * @throws Exception
     */
    public static function for(Process $process, string $message): bool
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

            return $process->isSuccessful();
        });
    }
}
