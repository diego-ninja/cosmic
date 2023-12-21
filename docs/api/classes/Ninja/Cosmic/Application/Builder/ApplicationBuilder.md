***

# ApplicationBuilder

Class ApplicationBuilder

Responsible for building the application, including setting up the environment, compiling, and restoring.

* Full name: `\Ninja\Cosmic\Application\Builder\ApplicationBuilder`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**



## Properties


### compiler



```php
private \Ninja\Cosmic\Application\Compiler\BoxCompiler $compiler
```






***

## Methods


### __construct

ApplicationBuilder constructor.

```php
public __construct(\Ninja\Cosmic\Application\Compiler\BoxCompiler $compiler): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$compiler` | **\Ninja\Cosmic\Application\Compiler\BoxCompiler** | The BoxCompiler instance for compiling the application. |





***

### build

Build the application for the specified environment.

```php
public build(string $environment): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$environment` | **string** | The environment for which to build the application. |


**Return Value:**

True if the build process is successful, false otherwise.



**Throws:**

- [`ReflectionException`](../../../../ReflectionException.md)

- [`Exception`](../../../../Exception.md)



***

### setup

Set up the environment for building the application.

```php
private setup(string $env): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$env` | **string** | The environment for which to set up. |


**Return Value:**

True if the setup process is successful, false otherwise.



**Throws:**

- [`Exception`](../../../../Exception.md)



***

### restore

Restore the initial environment after building the application.

```php
private restore(): bool
```









**Return Value:**

True if the restore process is successful, false otherwise.



**Throws:**

- [`Exception`](../../../../Exception.md)



***


***
> Automatically generated on 2023-12-21
