***

# Theme





* Full name: `\Ninja\Cosmic\Terminal\Theme\Theme`
* This class implements:
[`\Ninja\Cosmic\Terminal\Theme\ThemeInterface`](./ThemeInterface.md)


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`THEME_SECTION_COLORS`|public| |&quot;colors&quot;|
|`THEME_SECTION_CHARSETS`|public| |&quot;charsets&quot;|
|`THEME_SECTION_STYLES`|public| |&quot;styles&quot;|
|`THEME_SECTION_ICONS`|public| |&quot;icons&quot;|
|`THEME_SECTION_SPINNERS`|public| |&quot;spinners&quot;|

## Properties


### sections



```php
private static array $sections
```



* This property is **static**.


***

### name



```php
private string $name
```






***

### colors



```php
private \Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection $colors
```






***

### styles



```php
private \Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection $styles
```






***

### icons



```php
private \Ninja\Cosmic\Terminal\Theme\Element\Icon\IconCollection $icons
```






***

### charsets



```php
private \Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection $charsets
```






***

### spinners



```php
private \Ninja\Cosmic\Terminal\Theme\Element\Spinner\SpinnerCollection $spinners
```






***

### config



```php
private array $config
```






***

### logo



```php
private ?string $logo
```






***

### notification



```php
private ?string $notification
```






***

### parent



```php
private ?\Ninja\Cosmic\Terminal\Theme\ThemeInterface $parent
```






***

## Methods


### __construct



```php
public __construct(string $name, \Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection $colors, \Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection $styles, \Ninja\Cosmic\Terminal\Theme\Element\Icon\IconCollection $icons, \Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection $charsets, \Ninja\Cosmic\Terminal\Theme\Element\Spinner\SpinnerCollection $spinners, array $config, ?string $logo = null, ?string $notification = null, ?\Ninja\Cosmic\Terminal\Theme\ThemeInterface $parent = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$colors` | **\Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection** |  |
| `$styles` | **\Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection** |  |
| `$icons` | **\Ninja\Cosmic\Terminal\Theme\Element\Icon\IconCollection** |  |
| `$charsets` | **\Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection** |  |
| `$spinners` | **\Ninja\Cosmic\Terminal\Theme\Element\Spinner\SpinnerCollection** |  |
| `$config` | **array** |  |
| `$logo` | **?string** |  |
| `$notification` | **?string** |  |
| `$parent` | **?\Ninja\Cosmic\Terminal\Theme\ThemeInterface** |  |





***

### fromThemeFolder



```php
public static fromThemeFolder(string $folder): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$folder` | **string** |  |




**Throws:**

- [`JsonException`](../../../../JsonException.md)



***

### loadFile



```php
public loadFile(string $filename): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filename` | **string** |  |




**Throws:**

- [`JsonException`](../../../../JsonException.md)



***

### load



```php
public load(\Symfony\Component\Console\Output\OutputInterface $output): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





***

### fromFile



```php
public static fromFile(string $filename): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$filename` | **string** |  |





***

### fromJson



```php
public static fromJson(string $json): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$json` | **string** |  |





***

### fromArray



```php
public static fromArray(array $data): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |





***

### jsonSerialize



```php
public jsonSerialize(): array
```












***

### toArray



```php
public toArray(): array
```












***

### getName



```php
public getName(): string
```












***

### getLogo



```php
public getLogo(): ?string
```












***

### getIcons



```php
public getIcons(): \Ninja\Cosmic\Terminal\Theme\Element\Icon\IconCollection
```












***

### getIcon



```php
public getIcon(string $iconName): ?\Ninja\Cosmic\Terminal\Theme\Element\Icon\Icon
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$iconName` | **string** |  |





***

### getAppIcon



```php
public getAppIcon(): ?\Ninja\Cosmic\Terminal\Theme\Element\Icon\Icon
```












***

### getConfig



```php
public getConfig(?string $key = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **?string** |  |





***

### getColors



```php
public getColors(): \Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection
```












***

### getColor



```php
public getColor(string $colorName): ?\Ninja\Cosmic\Terminal\Theme\Element\Color\Color
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$colorName` | **string** |  |





***

### getStyles



```php
public getStyles(): \Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection
```












***

### getStyle



```php
public getStyle(string $styleName): ?\Ninja\Cosmic\Terminal\Theme\Element\Style\AbstractStyle
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$styleName` | **string** |  |





***

### getCharsets



```php
public getCharsets(): \Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection
```












***

### getCharset



```php
public getCharset(string $charsetName): ?\Ninja\Cosmic\Terminal\Theme\Element\Charset\Charset
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$charsetName` | **string** |  |





***

### getSpinners



```php
public getSpinners(): \Ninja\Cosmic\Terminal\Theme\Element\Spinner\SpinnerCollection
```












***

### getSpinner



```php
public getSpinner(string $spinnerName): \Ninja\Cosmic\Terminal\Theme\Element\Spinner\Spinner
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$spinnerName` | **string** |  |





***

### getNotificationIcon



```php
public getNotificationIcon(): ?string
```












***

### setNotificationIcon



```php
public setNotificationIcon(string $icon): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$icon` | **string** |  |





***

### setLogo



```php
public setLogo(string $logo): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$logo` | **string** |  |





***

### getParent



```php
public getParent(): ?\Ninja\Cosmic\Terminal\Theme\ThemeInterface
```












***

### setParent



```php
public setParent(\Ninja\Cosmic\Terminal\Theme\ThemeInterface $theme): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$theme` | **\Ninja\Cosmic\Terminal\Theme\ThemeInterface** |  |





***


***
> Automatically generated on 2023-12-21
