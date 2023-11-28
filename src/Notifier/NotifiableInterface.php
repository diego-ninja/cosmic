<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Notifier;

interface NotifiableInterface
{
    public function getSuccessMessage(): string;
    public function getErrorMessage(): string;
}
