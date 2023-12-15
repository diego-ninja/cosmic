# 🌀 Spinner

## Overview

The `Spinner` and `SpinnerFactory` classes are part of the `Ninja\Cosmic\Terminal\Spinner` namespace. They are used to create and manage a spinner in the terminal, which is a visual indicator of an ongoing process.

## Spinner

The `Spinner` class is responsible for creating a spinner. It provides methods to set the spinner style, message, and padding. The spinner style is defined in the `spinners.json` file, which contains a list of predefined spinner styles.

### Usage

To create a spinner, you first need to instantiate the `Spinner` class. You can optionally pass the spinner style to the constructor. If no style is provided, the default style (`dots13`) is used.

```php
$spinner = new Spinner('dots13');
```

You can set a message for the spinner using the `setMessage` method. This message is displayed next to the spinner.

```php
$spinner->setMessage('Loading...');
```

You can also set the padding for the spinner using the `setPadding` method. The padding is the number of spaces between the spinner and the left border of the terminal.

```php
$spinner->setPadding(2);
```

To start the spinner, you need to call the `callback` method and pass a callable that represents the process you want to indicate. The spinner will run in a separate process and will continue to display until the callable has finished executing.

```php
$spinner->callback(function () {
    // Your long-running process here...
});
```

## SpinnerFactory

The `SpinnerFactory` class provides static methods to create a spinner for a `Process` or a callable. It extends the `Spinner` class and inherits all its methods.

### Usage

To create a spinner for a `Process`, you can use the `forProcess` method. You need to pass the `Process` instance and a message to the method.

```php
$process = new Process(['ls', '-la']);
SpinnerFactory::forProcess($process, 'Listing directory...');
```

To create a spinner for a callable, you can use the `forCallable` method. You need to pass the callable and a message to the method.

```php
SpinnerFactory::forCallable(function () {
    // Your long-running process here...
}, 'Processing data...');
```

## Configuring the spinners

The spinner frames are defined in the `spinners.json` file. Each spinner style is an object with two properties: `interval` and `frames`. The `interval` is the time in milliseconds between each frame, and `frames` is an array of strings that represent the frames of the spinner.
