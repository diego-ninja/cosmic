***

# Terminal

Class Terminal

A utility class for interacting with the terminal and managing themes.

* Full name: `\Ninja\Cosmic\Terminal\Terminal`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**



## Properties


### instance



```php
private static ?self $instance
```



* This property is **static**.


***

### themeLoader



```php
private static \Ninja\Cosmic\Terminal\Theme\ThemeLoaderInterface $themeLoader
```



* This property is **static**.


***

### output



```php
private \Symfony\Component\Console\Output\ConsoleOutput $output
```






***

### input



```php
private ?\Symfony\Component\Console\Input\StreamableInputInterface $input
```






***

## Methods


### getInstance

Get the singleton instance of the Terminal class.

```php
public static getInstance(): self
```



* This method is **static**.





**Return Value:**

The Terminal instance.




***

### withTheme

Configure and enable a theme for the terminal.

```php
public static withTheme(\Ninja\Cosmic\Terminal\Theme\ThemeInterface $theme): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$theme` | **\Ninja\Cosmic\Terminal\Theme\ThemeInterface** | The theme to enable. |


**Return Value:**

The Terminal instance.




***

### loadThemes

Load themes from a specified directory.

```php
public static loadThemes(string $directory): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$directory` | **string** | The directory containing theme configurations. |


**Return Value:**

The Terminal instance.



**Throws:**

- [`JsonException`](../../../JsonException.md)



***

### addTheme

Add a theme to the list of available themes.

```php
public static addTheme(\Ninja\Cosmic\Terminal\Theme\ThemeInterface $theme): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$theme` | **\Ninja\Cosmic\Terminal\Theme\ThemeInterface** | The theme to add. |


**Return Value:**

The Terminal instance.




***

### enableTheme

Enable a specific theme by name.

```php
public static enableTheme(string $themeName): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$themeName` | **string** | The name of the theme to enable. |


**Return Value:**

The Terminal instance.




***

### getTheme

Get the currently enabled theme.

```php
public static getTheme(): \Ninja\Cosmic\Terminal\Theme\ThemeInterface
```



* This method is **static**.





**Return Value:**

The currently enabled theme.




***

### output

Get the console output object.

```php
public static output(): \Symfony\Component\Console\Output\ConsoleOutput
```



* This method is **static**.





**Return Value:**

The console output object.




***

### input

Get the streamable input object.

```php
public static input(): \Symfony\Component\Console\Input\StreamableInputInterface
```



* This method is **static**.





**Return Value:**

The streamable input object.




***

### clear

Clear a specified number of lines from the terminal.

```php
public static clear(int $lines): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$lines` | **int** | The number of lines to clear. |





***

### reset

Reset the terminal using Termwind.

```php
public static reset(): void
```



* This method is **static**.








***

### render

Render a message using the terminal's formatter.

```php
public static render(string $message): string|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The message to render. |


**Return Value:**

The rendered message.




***

### width

Get the width of the terminal.

```php
public static width(): int
```



* This method is **static**.





**Return Value:**

The width of the terminal.




***

### color

Get a color style by name from the terminal formatter.

```php
public static color(string $colorName): \Symfony\Component\Console\Formatter\OutputFormatterStyleInterface
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$colorName` | **string** | The name of the color style. |


**Return Value:**

The color style.




***

### stream

Get the stream associated with the terminal input.

```php
public static stream(): mixed
```



* This method is **static**.





**Return Value:**

The input stream or STDIN if not available.




***

### __construct



```php
private __construct(\Symfony\Component\Console\Output\ConsoleOutput $output, ?\Symfony\Component\Console\Input\StreamableInputInterface $input = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\ConsoleOutput** |  |
| `$input` | **?\Symfony\Component\Console\Input\StreamableInputInterface** |  |





***


***
> Automatically generated on 2023-12-21
