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

## Themes

The `Terminal` class uses the `ThemeLoader` class to manage themes. Themes control the appearance of the terminal output, including colors, styles, and icons.

You can load themes from a directory using the `loadThemes` method, add individual themes using the `addTheme` method, and enable a theme using the `enableTheme` method.

```php
// Load themes from a directory
Terminal::loadThemes('/path/to/themes');

// Add an individual theme
$theme = Theme::fromThemeFolder('/path/to/theme');
Terminal::addTheme($theme);

// Enable a theme
Terminal::enableTheme('my-theme');
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
