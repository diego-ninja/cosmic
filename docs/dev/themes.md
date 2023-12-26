# 🌈 Terminal Themes

## Overview
Themes are used to customize the appearance of the terminal output. A theme is a set of files that define the colors, styles, and icons used by the terminal. Cosmic can load themes from a directory or a zipped theme file (`.zth`). By default, themes are stored in the `themes` directory of the application but can be stored anywhere.

## Theme structure
Nevertheless, the theme is loaded from a directory or a zipped theme file, the structure of the theme is the same. The theme directory or zipped theme file must contain the following files:

### Colors
Cosmic uses a ten color palette with gradient variations, plus black and white colors that doesn't have variations. 
The colors are defined in the `colors.json` file.

```json
{
    "colors": {
        "white": "#f8f8f2",
        "black": "#282a36",
        "gray": "#adb5bd",
        "blue": "#6272a4",
        "indigo": "#6610f2",
        "purple": "#bd93f9",
        "pink": "#ff79c6",
        "red": "#ff5555",
        "orange": "#ffb86c",
        "yellow": "#f1fa8c",
        "green": "#50fa7b",
        "teal": "#20c997",
        "cyan": "#8be9fd",

        "text": "@white",
        "comment": "@yellow",
        "info": "@cyan",
        "notice": "@blue",
        "warning": "@orange",
        "error": "@red",
        "success": "@green",
        "question": "@cyan",
        "default": "@cyan"
    }
}
```

#### Mandatory colors
The following colors are mandatory and must be defined in the colors file to assure the correct behavior of the application. You can define directly the color value or use an alias to another color, but this colors must be present in the colors file.

```json
{
    "colors": {
        // Other color definitions...

        "text": "@white",
        "comment": "@yellow",
        "info": "@cyan",
        "notice": "@blue",
        "warning": "@orange",
        "error": "@red",
        "success": "@green",
        "question": "@cyan",
        "default": "@cyan"
    }
}
```

#### Gradients
For each color defined in the colors object, Cosmic will generate nine variations of the color to build a gradient with the seed color in the middle of the gradient.
The gradient variations are named like the seed color with a three digit suffix that represents the gradient variation. The suffix is a number between 100 and 900 with a step of 100. The suffix 500 represents the seed color.

For example, the color `blue` in the previous set of colors will generate the following gradient variations:
```json
{
    "blue100": "#a4b0e0",
    "blue200": "#8e9cd9",
    "blue300": "#7888d2",
    "blue400": "#6274cb",
    "blue500": "#4c60c4",
    "blue600": "#3d4ea0",
    "blue700": "#2e3c7c",
    "blue800": "#1f2a58",
    "blue900": "#101834"
}
```

along the code you can use this color variations like any other color. For example, to use the `blue200` color you can use the following code:
```php
$colored = Terminal::render(sprintf('<blue200>%s</>', $text));
```

#### Aliases
The colors object can contain aliases to other colors. The alias is defined with the `@` character followed by the name of the color to alias. For example, the `text` color is an alias to the `yellow` color. The following code is equivalent:
```json
{
    "colors": {
        "yellow": "#f1fa8c",
        "comment": "@yellow"
    }
}
```

later in your code you can use both names to reference the same color. For example, the following code is equivalent:

```php
$yellow = Terminal::render(sprintf('<yellow>%s</>', $text));
$comment = Terminal::render(sprintf('<comment>%s</>', $text));
```

you can alias gradient colors too:

```json
{
    "colors": {
        "blue": "#6272a4",
        "notice": "@blue200"
    }
}
```

### Styles
Styles are used to format the text output, styles goes a step forwarder than colors, and it let you customize the terminal output in more ways that just the font color. The styles are defined in the `styles.json` file.
Cosmic supports two different styles formats: [Symfony based styles](https://symfony.com/doc/current/console/coloring.html) and [Termwind based styles]().

#### Symfony based styles
Symfony based styles are built on top of [OutputFormatterStyle](https://github.com/symfony/symfony/blob/7.0/src/Symfony/Component/Console/Formatter/OutputFormatterStyle.php) class, this type of styles
supports the following attributes:

- `fg` - Foreground color
- `bg` - Background color
- `options` - Text options [bold, underscore, blink, reverse]

to define a Symfony based style you must use the following format:

```json
{
    "styles": {
        "critical": {
            "fg": "#f8f8f2",
            "bg": "#ff5555",
            "options": [
                "bold"
            ]
        }
    }
}
```

#### Termwind based styles
Termwind based styles are built on top of [Termwind](https://github.com/nunomaduro/termwind), you can define a Termwind based style using the following format:

```json
{
    "styles": {
        "critical": "bg-red-500 text-white font-bold"
    }
}
```

please, refer to the [Termwind documentation](https://github.com/nunomaduro/termwind) to learn more about the Termwind styles.

At the time of writing this documentation there is a way to translate some Symfony based styles to Termwind based styles, but there is no way to translate Termwind based styles to Symfony based styles. But a full two-way translation is planned for the future. 

### Icons
Icons are used to customize the terminal output, icons are defined in the `icons.json` file. At the time of writing this documentation, Cosmic just supports [ANSI](https://www.alt-codes.net/) and [Unicode](https://apps.timwhitlock.info/emoji/tables/unicode) icons, but more icon types are planned for the future detecting and using hacked fonts.

Icons are used mainly to decorate the terminal output when requesting input or displaying information. At the time of writing this documentation, Cosmic uses the following icons:

```json
{
    "icons": {
        "application": "🚀",
        "bullet": "🔸",
        "success": "⬡",
        "failure": "⬡",
        "radio_selected": "⬢",
        "radio": "⬡",
        "checkbox": "☐",
        "checkbox_selected": "✔",
        "mandatory": "🔸",
        "nullable": "🔹",
        "collection": "🔲"
    }
}
```

### Charsets

### Spinners

### Theme file

### Theme logo

### Notification icon
