***

# Env

Class Env

Provides utility methods for interacting with environment variables.

* Full name: `\Ninja\Cosmic\Environment\Env`



## Properties


### putenv

Whether to use putenv for setting environment variables.

```php
protected static bool $putenv
```



* This property is **static**.


***

### repository

The Dotenv repository instance.

```php
protected static \Dotenv\Repository\RepositoryInterface|null $repository
```



* This property is **static**.


***

## Methods


### enablePutenv

Enable the use of putenv for setting environment variables.

```php
public static enablePutenv(): void
```



* This method is **static**.








***

### disablePutenv

Disable the use of putenv for setting environment variables.

```php
public static disablePutenv(): void
```



* This method is **static**.








***

### getRepository

Get the Dotenv repository instance.

```php
public static getRepository(): \Dotenv\Repository\RepositoryInterface
```



* This method is **static**.








***

### basePath

Get the base path, optionally with a subdirectory appended.

```php
public static basePath(string|null $dir = null): string|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$dir` | **string&#124;null** | Subdirectory (optional) |





***

### buildPath

Get the build path, optionally with a subdirectory appended.

```php
public static buildPath(string|null $dir = null): string|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$dir` | **string&#124;null** | Subdirectory (optional) |





***

### helpPath

Get the help path for commands, optionally with a subdirectory appended.

```php
public static helpPath(string|null $dir = null): string|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$dir` | **string&#124;null** | Subdirectory (optional) |





***

### isDebug

Check if the application is in debug mode.

```php
public static isDebug(): bool
```



* This method is **static**.








***

### env

Get the current environment.

```php
public static env(): string
```



* This method is **static**.








***

### appVersion

Get the application version.

```php
public static appVersion(): string
```



* This method is **static**.








***

### appName

Get the application name.

```php
public static appName(): string
```



* This method is **static**.








***

### shell

Get the shell executable, optionally with an icon.

```php
public static shell(string|null $icon = null): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$icon` | **string&#124;null** | Shell icon (optional) |





***

### dump

Dump the environment variables along with some additional information.

```php
public static dump(): array
```



* This method is **static**.








***

### get

Get the value for a given key from the environment, with a default value if not set.

```php
public static get(string $key, mixed $default = null): mixed
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | The key to retrieve |
| `$default` | **mixed** | Default value if the key is not set |





***


***
> Automatically generated on 2023-12-21
