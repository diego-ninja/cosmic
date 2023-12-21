***

# ThemeLoader





* Full name: `\Ninja\Cosmic\Terminal\Theme\ThemeLoader`
* This class is marked as **final** and can't be subclassed
* This class implements:
[`\Ninja\Cosmic\Terminal\Theme\ThemeLoaderInterface`](./ThemeLoaderInterface.md)
* This class is a **Final class**



## Properties


### theme



```php
private \Ninja\Cosmic\Terminal\Theme\ThemeInterface $theme
```






***

### themes



```php
private array $themes
```






***

### output



```php
private \Symfony\Component\Console\Output\OutputInterface $output
```






***

## Methods


### __construct



```php
public __construct(array $themes, \Symfony\Component\Console\Output\OutputInterface $output): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$themes` | **array** |  |
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





***

### loadDirectory



```php
public loadDirectory(string $directory): \Ninja\Cosmic\Terminal\Theme\ThemeLoaderInterface
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$directory` | **string** |  |




**Throws:**

- [`JsonException`](../../../../JsonException.md)



***

### addTheme



```php
public addTheme(\Ninja\Cosmic\Terminal\Theme\ThemeInterface $theme): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$theme` | **\Ninja\Cosmic\Terminal\Theme\ThemeInterface** |  |





***

### getEnabledTheme



```php
public getEnabledTheme(): \Ninja\Cosmic\Terminal\Theme\ThemeInterface
```












***

### enableTheme



```php
public enableTheme(string $themeName): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$themeName` | **string** |  |





***


***
> Automatically generated on 2023-12-21
