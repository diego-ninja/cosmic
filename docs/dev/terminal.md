# 🖥️ Terminal

The `Terminal` class, part of the `Ninja\Cosmic\Terminal` namespace, is a central component of the application. It provides a set of static methods for interacting with the terminal, including outputting text, asking questions, confirming actions, selecting options, and displaying tables.

## Overview

The `Terminal` class is a singleton, meaning there can only be one instance of it at any given time. This instance can be retrieved using the `getInstance` method.

The `Terminal` class uses the `ThemeLoader` class to manage themes, which control the appearance of the terminal output. Themes can be loaded from a directory, added individually, and enabled by name.

The `Terminal` class also provides methods for outputting text to the terminal (`output`), reading input from the terminal (`input`), and managing sections of the terminal output (`header`, `body`, `footer`).

## Usage

### Outputting Text

To output text to the terminal, you can use the `output` method. This method returns an instance of `ConsoleOutput`, which you can use to write text to the terminal.

```php
Terminal::output()->writeln('Hello, world!');
```

### Asking Questions

To ask a question in the terminal, you can use the `ask` method. This method prompts the user for input and returns their response.

```php
$name = Terminal::ask('What is your name?');
Terminal::output()->writeln("Hello, $name!");
```

### Confirming Actions

To confirm an action in the terminal, you can use the `confirm` method. This method prompts the user for a yes/no response and returns a boolean indicating their choice.

```php
if (Terminal::confirm('Are you sure you want to continue?')) {
    Terminal::output()->writeln('Continuing...');
} else {
    Terminal::output()->writeln('Aborting...');
}
```

### Selecting Options

To prompt the user to select an option from a list, you can use the `select` method. This method displays a list of options in the terminal and returns the user's selection(s).

```php
$color = Terminal::select('Choose a color:', ['Red', 'Green', 'Blue']);
Terminal::output()->writeln("You chose: $color");
```

### Displaying Tables

To display a table in the terminal, you can use the `table` method. This method accepts an array of headers and an array of data, and it displays the data in a table format in the terminal.

```php
$headers = ['Name', 'Age'];
$data = [
    ['John Doe', 30],
    ['Jane Doe', 25],
];
Terminal::table($headers, $data);
```

## Themes

The `Terminal` class uses the `ThemeLoader` class to manage themes. Themes control the appearance of the terminal output, including colors, styles, and icons.

You can load themes from a directory using the `loadThemes` method, add individual themes using the `addTheme` method, and enable a theme using the `enableTheme` method.

```php
// Load themes from a directory
Terminal::loadThemes('/path/to/themes');

// Add an individual theme
$theme = new MyTheme();
Terminal::addTheme($theme);

// Enable a theme
Terminal::enableTheme('my-theme');
```

## Sections

The `Terminal` class provides methods for managing sections of the terminal output. These sections can be used to organize the output into separate areas, such as a header, a body, and a footer.

```php
// Write to the header section
Terminal::header()->writeln('This is the header.');

// Write to the body section
Terminal::body()->writeln('This is the body.');

// Write to the footer section
Terminal::footer()->writeln('This is the footer.');
```

## Resetting the Terminal

To clear the terminal, you can use the `reset` method. This method clears all output from the terminal.

```php
Terminal::reset();
```

## Rendering Text

To format text according to the current theme, you can use the `render` method. This method accepts a string of text and returns a string of text formatted according to the current theme.

```php
$formattedText = Terminal::render('<info>This is some informational text.</info>');
Terminal::output()->writeln($formattedText);
```

## Color Styles

To retrieve a color style defined in the current theme, you can use the `color` method. This method accepts the name of a color style and returns an `OutputFormatterStyleInterface` object representing the color style.

```php
$style = Terminal::color('info');
$formattedText = $style->apply('This is some informational text.');
Terminal::output()->writeln($formattedText);
```
