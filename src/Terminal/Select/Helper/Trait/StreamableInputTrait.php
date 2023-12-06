<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Select\Helper\Trait;

use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Input\StreamableInputInterface;

trait StreamableInputTrait
{
    protected $inputStream;

    /** @return false|resource */
    protected function getInputStream()
    {
        if (empty($this->inputStream) && Terminal::input() instanceof StreamableInputInterface) {
            $this->inputStream = $this->input->getStream() ?: STDIN;
        }

        return $this->inputStream;
    }

}
