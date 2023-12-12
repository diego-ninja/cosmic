<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Notifier;

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Terminal\Terminal;

class Notifier
{
    private static ?self $instance = null;

    private \Joli\JoliNotif\Notifier $notifier;

    private function __construct()
    {
        $this->notifier = NotifierFactory::create();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public static function success(string $message): void
    {
        self::getInstance()->notifySuccess($message);
    }

    public static function error(string $message): void
    {
        self::getInstance()->notifyError($message);
    }

    public static function notify(string $message): void
    {
        self::getInstance()->notifySuccess($message);
    }

    private function notifySuccess(string $message): void
    {
        $this->notifier->send($this->getSuccessNotification($message));
    }

    private function notifyError(string $message): void
    {
        $this->notifier->send($this->getErrorNotification($message));
    }

    private function getSuccessNotification(string $message): Notification
    {
        return (new Notification())
            ->setTitle(ucfirst(Env::get("APP_NAME")))
            ->setBody(sprintf("%s", $message))
            ->setIcon(Terminal::getTheme()->getNotificationIcon());
    }

    private function getErrorNotification(string $message): Notification
    {
        return (new Notification())
            ->setTitle(ucfirst(Env::get("APP_NAME")))
            ->setBody(sprintf("%s", $message))
            ->setIcon(Terminal::getTheme()->getNotificationIcon());
    }
}
