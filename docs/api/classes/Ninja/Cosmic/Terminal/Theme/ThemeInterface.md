***

# ThemeInterface





* Full name: `\Ninja\Cosmic\Terminal\Theme\ThemeInterface`
* Parent interfaces: [`JsonSerializable`](../../../../JsonSerializable.md)


## Methods


### load



```php
public load(\Symfony\Component\Console\Output\OutputInterface $output): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





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
public getSpinner(string $spinnerName): ?\Ninja\Cosmic\Terminal\Theme\Element\Spinner\Spinner
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
