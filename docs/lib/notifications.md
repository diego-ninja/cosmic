# 💬 Notifications

The `Notifier` class is a singleton class that provides methods to display OS-based notifications. It uses the `Joli\JoliNotif\Notifier` class to send the notifications. The notifications can be of success or error type.

## Usage

### Getting the Instance

The `Notifier` class is a singleton, so you get an instance of it by calling the `getInstance` method:

```php
$notifier = \Ninja\Cosmic\Notifier\Notifier::getInstance();
```

### Sending Notifications

The `Notifier` class provides three static methods to send notifications: `success`, `error`, and `notify`.

#### Success Notification

To send a success notification, use the `success` method. This method accepts a string message as a parameter:

```php
\Ninja\Cosmic\Notifier\Notifier::success('Your operation was successful.');
```

#### Error Notification

To send an error notification, use the `error` method. This method also accepts a string message as a parameter:

```php
\Ninja\Cosmic\Notifier\Notifier::error('An error occurred during the operation.');
```

#### General Notification

To send a general notification, use the `notify` method. This method also accepts a string message as a parameter:

```php
\Ninja\Cosmic\Notifier\Notifier::notify('A general notification message.');
```

## Implementing the NotifiableInterface

Commands that should display notifications upon exit (either successful or failure) should implement the `NotifiableInterface`. This interface requires two methods to be implemented: `getSuccessMessage` and `getErrorMessage`.

You don't need to take care of sending the notifications using the Notifier class as the CosmicCommand does it for you under the hood, the success() and failure() methods of the CosmicCommand class detects if the command is notifiable and sends the proper notification upon command exit.

Here is an example of a command implementing the `NotifiableInterface`:

```php
<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Notifier\NotifiableInterface;

class ExampleCommand extend CosmicCommand implements NotifiableInterface
{
    public function _invoke(): void
    {
    }

    public function getSuccessMessage(): string
    {
        return 'The command executed successfully.';
    }

    public function getErrorMessage(): string
    {
        return 'An error occurred while executing the command.';
    }
}
```
