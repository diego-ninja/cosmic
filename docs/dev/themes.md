## Overview
Themes are used to customize the appearance of the terminal output. A theme is a set of files that define the colors, styles, and icons used by the terminal. Cosmic can load themes from a directory or a zipped theme file (`.zth`). By default, themes are stored in the `themes` directory of the application but can be stored anywhere.

Cosmic comes with a set of default themes that can be used out of the box, but you can create your own themes or customize the default themes to fit your needs.

The three default themes are:
- 🛸 cosmic, an outer-space theme with a vibrant palette.
- 🧝‍ nord based in the world-famous top-notch [nord palette](https://www.nordtheme.com/).
- 🧛 dracula, based in the popular [dracula theme](https://draculatheme.com/).

## Theme structure
Nevertheless, the theme is loaded from a directory or a zipped theme file, the structure of the theme is the same. The theme directory or zipped theme file must contain the following files:

### Theme file
The theme file is a json file that defines the theme name, version, and description. The theme file is named `theme.json` and must be located in the root of the theme directory or zipped theme file. Besides the theme metadata info, this file holds the configuration object for the theme.

The config objects defines miscellaneous options for the theme, like the terminal width, the left spacing or the default configurations for several UI elements like table, summaries or progress bars.

This file has not to comply with any schema, you can add any property you want to the config object, but the following properties are the ones used by the application:

```json  
{  
    "name": "cosmic",  
    "version": "1.0.0",  
    "description": "Cosmic theme",
	"config": {  
	    "width": 80,  
	    "spacing": 1,  
	    "icons_enabled": true,  
	    "spinner": {  
	        "style": "dots2",  
	        "color": "cyan"  
	    },  
	    "progress": {  
	        "bar_color": "cyan",  
	        "text_color": "white",  
	        "apply_gradient": false,  
	        "use_segments": true,  
	        "char_empty": "░",  
	        "char_full": "█",  
	        "width": 40,  
	        "format": " {detail}{nl} {bar} {percentage} {steps}",  
	        "spacing": 1  
	    },  
	    "table": {  
	        "charset": "double",  
	        "table_color": "white",  
	        "header_color": "notice",  
	        "field_color": "text"  
	    },  
	    "summary": {  
	        "charset": "double",  
	        "table_color": "white",  
	        "header_color": "notice",  
	        "title_color": "info",  
	        "field_color": "text",  
	        "show_header": false  
	    }  
	}      
}  
```
you can access the theme config object using the `getConfig` method from the `Theme` class.

```php
$config = Terminal::getTheme()->getConfig("summary");
```  

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

later in the code you can apply the style to a text using the following code:

```php  
  
$styled = Terminal::render(sprintf('<critical>%s</>', $text));  
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

and for example, you can apply the style to a text using the following code:

```php  
\Termwind\render(sprintf("<span class='critical'>%s</span>", $text));  
```  

please, refer to the [Termwind documentation](https://github.com/nunomaduro/termwind) to learn more about the Termwind styles.

At the time of writing this documentation there is a way to translate some Symfony based styles to Termwind based styles using the `termwindize` function from the helpers, but there is no way to translate Termwind based styles to Symfony based styles. But a full two-way translation is planned for the future.

### Icons
Icons are used to customize the terminal output, icons are defined in the `icons.json` file. At the time of writing this documentation, Cosmic just supports [ANSI](https://www.alt-codes.net/) and [Unicode](https://apps.timwhitlock.info/emoji/tables/unicode) icons, but more icon types are planned for the future detecting and using hacked fonts.

Icons are used mainly to decorate the terminal output when requesting input or displaying information. At the time of writing this documentation, Cosmic uses the following icons:

```json  
{  
    "icons": {  
        "application": "🛸",  
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

you can add as many icons as you want to the icons file, and use them in your code using the following code:

```php  
  
$bullet = Terminal::getTheme()->getIcon('bullet');  
$anotherIcon = Terminal::getTheme()->getIcon('anotherIcon');  
```  

### Charsets

A charset, short for character set, is a specific set of characters that are used to display tables and frames in the application.

Charsets are defined in the charsets.json file, all three Cosmic themes has four different charsets: square, rounded, double, and heavy.

Each charset has a unique set of characters for different parts of a layout, such as the top, bottom, left, right, and middle sections. These characters are used to create borders and intersections in the layout.

#### Square
This charset uses simple line characters to create a clean, square-edged layout.

#### Rounded
The rounded charset is similar to the square charset, but it uses rounded characters for the top-left and top-right corners, giving the layout a softer appearance.

#### Double
This charset uses double line characters, creating a more pronounced and bold layout.

#### Heavy
This last charset uses thicker line characters, giving the layout a heavier and more substantial appearance.  Each charset is unique and can be used to create a different visual effect in the layout.

The choice of charset can significantly impact the overall look and feel of the theme.

Probably you will not need to create your own charsets, but if you want to do it, you can use the following format:

```json  
{  
    "charsets": {  
        "new-charset" : {  
            "top": "─",  
            "top-mid": "┬",  
            "top-left": "╭",  
            "top-right": "╮",  
            "bottom": "─",  
            "bottom-mid": "┴",  
            "bottom-left": "╰",  
            "bottom-right": "╯",  
            "left": "│",  
            "left-mid": "├",  
            "mid": "─",  
            "mid-mid": "┼",  
            "right": "│",  
            "right-mid": "┤",  
            "middle": "│"  
        }  
    }  
}  
```  

### Spinners
The `spinners.json` file defines the spinners used by the application. A spinner is a set of characters that are displayed in a loop to indicate that the application is busy. The spinners are used to indicate that the application is busy and to provide a visual cue that the application is still running.

Each spinner is defined by the interval and the frames, by default Cosmic come with a lot of different spinners defined ready to use, so it is very uncommon that you need to change this file, anyway, if you need to add a new spinner definition, you can use the following format:

```json  
{  
    "spinners": {  
        "new-spinner": {  
            "interval": 100,  
            "frames": [  
                "⠋",  
                "⠙",  
                "⠹",  
                "⠸",  
                "⠼",  
                "⠴",  
                "⠦",  
                "⠧",  
                "⠇",  
                "⠏"  
            ]  
        }  
    }  
}  
```  


### Notification icon
The notification icon is used as part of the os-based notifications. The notification icon should be a png image with a transparent background. The notification icon is stored in the notification.png file.  
The recommended size for the notification icon is **512x512** pixels.

### Theme logo
The theme logo is a piece of ANSI art that is displayed as part of the about command. The theme logo is stored in the logo.php file. The logo is a PHP file that returns a string with the ANSI art.

By the time of writing this documentation, there are not a local tool or mechanism to generate ANSI logos from images. But you can use online resources like [image2ansi](https://dom111.github.io/image-to-ansi/) to generate your own logos. In a near future, Cosmic will include a local tool to generate ANSI logos from images.

**🤓 PRO TIP**: You can convert your notification icon to ANSI art using the [image2ansi](https://dom111.github.io/image-to-ansi/) tool and use it as your logo. This way you will have a consistent look and feel in your application.
