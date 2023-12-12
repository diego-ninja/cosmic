# 🎨 Terminal Themes

## Overview

Themes are used to customize the appearance of the terminal output. A theme is an object that implements the `ThemeInterface` and provides a set of styles that are used by the `Terminal` class to format its output.

## Creating a theme
A theme is a set of files inside a folder, at the time of writing this document, a theme is composed by three files, the default theme folder is /themes, but you can load themes from any folder. The applications generated with the cosmic binary loads all themes inside the /themes folders automatically.

This three files must reside inside a folder named as the theme, so, for example, the included theme alien, is inside the folder /themes/alien
### theme.json

This is the real theme definition file, it is a free json file, and free means that it has no schema to conform to, schema enforcement is in the roadmap for the next versions of Cosmic, but for now at least you need to define the following sections, in addition to the name, description and other metadata values defined in the root of the json object:

#### colors
This section defines the color scheme for the theme. Each key-value pair represents a specific text type (such as `text`, `comment`, `info`, etc.) and its corresponding color in hexadecimal format. The regular definition is in the form key-value, where the key is the name of the color and the value is its hexadecimal representation, but you can use an extended notation that instead of the hexadecimal color representation uses an array consisting of three elements, fg for foreground color, bg for background color, and options for additional attributes that define the color style such as bold or blink.

The colors are loaded into the default OutputInterface in the application and they are defined as termwind custom styles too. (Termwind style just load the simple notation colors)

You can define as many colors as you want, but at least, the following set of colors must be present in every terminal theme.

```json
"colors": {  
    "text": "#E5E9F0",  
    "comment": "#EBCB8B",  
    "info": "#81A1C1",  
    "notice": "#8FBCBB",  
    "warning": "#D08770",  
    "error": "#BF616A",  
    "success": "#A3BE8C",  
    "white": "#FFFFFF",  
    "black": "#000000",  
    "grey": "#4C566A",  
    "red": "#BF616A",  
    "green": "#A3BE8C",  
    "yellow": "#EBCB8B",  
    "blue": "#81A1C1",  
    "purple": "#B48EAD",  
    "cyan": "#88C0D0",  
    "hl": {  
        "fg": "#E5E9F0",  
        "bg": "#4C566A"  
    },  
    "critical": {  
        "fg": "#BF616A",  
        "options": [  
            "blink"  
        ]  
    },    "warn": {  
        "fg": "#D08770",  
        "options": [  
            "blink"  
        ]  
    }
}
```

#### icons
This section defines various icons used in the terminal. Each key-value pair represents a specific type of icon (like `application`, `bullet`, `success`, etc.) and its corresponding character representation. You can define as many icons as you want, but at least the following set of icons must be present in every theme, directly or extending another theme.

```json
"icons": {  
    "application": "🚀",  
    "bullet": "⬢",  
    "success": "⬡",  
    "failure": "⬡",  
    "radio_selected": "⬢",  
    "radio": "⬡",  
    "checkbox": "☐",  
    "checkbox_selected": "✔"  
}
```

#### styles
This section defines various styles used in the terminal. Each key-value pair represents a specific type of style (like `container`, `footer`, `emphasis`, etc.) and its corresponding CSS class names. This styles are just only loaded as Termwind styles and are mainly used to render the command help markdown files using the console.

As in prior sections, you can define as many Termwind styles as you want, but the minimal set of styles required to properly render the command help files is as follows:

```json
"styles": {  
    "container": "w-120 ml-2",  
    "footer": "w-120 pt-2 ml-2",  
    "emphasis": "font-bold text-yellow italic",  
    "strong": "font-bold text-blue",  
    "strike": "line-through",  
    "hr": "text-blue",  
    "h1": "invisible",  
    "heading": "text-blue pt-1",  
    "language-sh": "w-120",  
    "language-php": "w-120",  
    "language-js": "w-120",  
    "language-sql": "w-120",  
    "language": "w-120",  
    "link": "text-blue underline",  
    "app-icon": "mr-1",  
    "question": "text-blue",  
    "default-option": "font-bold text-yellow"  
}
```

#### config
This section holds miscellaneous configuration used along the application, at the time of writing this document,  you can define here as many config values as you want,  the only required configuration is the one exposed in the following code excerpt:

```json
"config": {  
    "icons_enabled": true,  
    "table": {  
        "charset": {  
            "top": "═",  
            "top-mid": "╤",  
            "top-left": "╔",  
            "top-right": "╗",  
            "bottom": "═",  
            "bottom-mid": "╧",  
            "bottom-left": "╚",  
            "bottom-right": "╝",  
            "left": "║",  
            "left-mid": "╟",  
            "mid": "─",  
            "mid-mid": "┼",  
            "right": "║",  
            "right-mid": "╢",  
            "middle": "│"  
        },  
        "table_color": "white",  
        "header_color": "cyan",  
        "field_color": "white"  
    }  
}
```


You can use the default theme as guide when you need to define a new theme,

### notification.png
This file is used when displaying an os-based notification using the Notifier class, the recomended settings for this file is an PNG image of width and height of 512px and transparent background.

### logo.php
This file is a PHP script that returns a string containing ANSI escape codes. These codes are used to format text in a terminal, allowing for things like color changes and cursor movements. It represents the colorful banner with the app logo that is displayed when the about command is executed

To generate a file like this, you would need to design your logo or banner using characters and spaces, then add ANSI escape codes to colorize it. There are online tools available that can help you design ANSI art and generate the corresponding escape codes.

The recommended way for generating application ANSI logos is to use the following site with the Unicode + True Color options enabled:

[Image to ANSI](https://dom111.github.io/image-to-ansi/)

🤓 PRO TIP: If you convert your notification icon into an ANSI logo using the above site, you can use the same image for both the logo and the notification icon, giving your application a consistent look.

## Extending a theme

If you want to create a new theme that is based on an existing theme but with some modifications, you can extend the existing theme class and override the `getStyles` method to add or modify styles.

Here's an example:

```php
namespace Ninja\Cosmic\Terminal\Theme;

class MyExtendedTheme extends ExistingTheme
{
    public function getName(): string
    {
        return 'my-extended-theme';
    }

    public function getStyles(): array
    {
        // Get the styles from the parent theme
        $styles = parent::getStyles();

        // Modify an existing style
        $styles['highlight']->setBackground('green');

        // Add a new style
        $styles['warning'] = new OutputFormatterStyle('black', 'yellow');

        return $styles;
    }
}
```

## Enabling a Theme at Runtime

To enable a theme at runtime, you can use the `Terminal::withTheme` method and pass an instance of your theme. This will set the theme for the current `Terminal` instance.

```php
Terminal::withTheme(new MyTheme());
```

## The `--theme` Global Parameter

The `--theme` global parameter allows you to specify the theme to use when running the application from the command line. This overrides the theme set in the environment variable.

To use this parameter, pass it to the application with the name of your theme:

```bash
php my-app.php --theme=my-theme
```

The application will then use the specified theme for its output. If the theme does not exist, the application will fall back to the default theme.

## ThemeLoader

The `ThemeLoader` class is responsible for managing the themes in the application. It provides methods to add a theme (`addTheme`), enable a theme (`enableTheme`), and get the currently enabled theme (`getEnabledTheme`). It also provides a method to load all themes from a directory (`loadDirectory`), which can be useful if you have multiple theme classes in your project.

To add a theme to the `ThemeLoader`, you can use the `addTheme` method and pass an instance of your theme:

```php
ThemeLoader::addTheme(new MyTheme());
```

To enable a theme, you can use the `enableTheme` method and pass the name of your theme:

```php
ThemeLoader::enableTheme('my-theme');
```

The `getEnabledTheme` method returns the currently enabled theme:

```php
$theme = ThemeLoader::getEnabledTheme();
```

The `loadDirectory` method loads all theme classes from a specified directory:

```php
ThemeLoader::loadDirectory('/path/to/themes');
```

This method uses the PHP `glob` function to find all PHP files in the specified directory, and then it includes each file and adds the theme to the `ThemeLoader`. The theme classes in the directory should be defined in the global namespace and their names should match their file names.
