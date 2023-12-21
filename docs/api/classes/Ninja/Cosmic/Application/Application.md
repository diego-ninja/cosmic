***

# Application

Class Application



* Full name: `\Ninja\Cosmic\Application\Application`
* Parent class: [`Application`](../../../Symfony/Component/Console/Application.md)
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`LIFECYCLE_APP_BOOT`|public| |&#039;app.boot&#039;|
|`LIFECYCLE_APP_SHUTDOWN`|public| |&#039;app.shutdown&#039;|
|`LIFECYCLE_APP_BUILD`|public| |&#039;app.build&#039;|
|`LIFECYCLE_APP_INSTALL`|public| |&#039;app.install&#039;|

## Properties


### parser



```php
private \Ninja\Cosmic\Command\Parser\ExpressionParser $parser
```






***

### invoker



```php
private \Invoker\InvokerInterface $invoker
```






***

### container



```php
private ?\Psr\Container\ContainerInterface $container
```






***

## Methods


### __construct

Application constructor.

```php
public __construct(string $name = &#039;UNKNOWN&#039;, string $version = &#039;UNKNOWN&#039;, \DI\Container|null $container = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$version` | **string** |  |
| `$container` | **\DI\Container&#124;null** |  |




**Throws:**

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)

- [`RuntimeException`](../../../RuntimeException.md)

- [`InvocationException`](../../../Invoker/Exception/InvocationException.md)

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)

- [`ReflectionException`](../../../ReflectionException.md)

- [`ContainerExceptionInterface`](../../../Psr/Container/ContainerExceptionInterface.md)

- [`NotFoundExceptionInterface`](../../../Psr/Container/NotFoundExceptionInterface.md)



***

### run

Run the application. This is the entry point of the application.

```php
public run(\Symfony\Component\Console\Input\InputInterface|null $input = null, \Symfony\Component\Console\Output\OutputInterface|null $output = null): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **\Symfony\Component\Console\Input\InputInterface&#124;null** |  |
| `$output` | **\Symfony\Component\Console\Output\OutputInterface&#124;null** |  |




**Throws:**

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)

- [`RuntimeException`](../../../RuntimeException.md)

- [`InvocationException`](../../../Invoker/Exception/InvocationException.md)

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)

- [`ReflectionException`](../../../ReflectionException.md)

- [`Throwable`](../../../Throwable.md)



***

### getDefaultInputDefinition

Creates an InputDefinition with the default arguments and options.

```php
public getDefaultInputDefinition(): \Symfony\Component\Console\Input\InputDefinition
```











**Throws:**

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)

- [`RuntimeException`](../../../RuntimeException.md)

- [`Throwable`](../../../Throwable.md)



***

### doRunCommand

Runs the current command.

```php
public doRunCommand(\Symfony\Component\Console\Command\Command $command, \Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |
| `$input` | **\Symfony\Component\Console\Input\InputInterface** |  |
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |




**Throws:**

- [`Throwable`](../../../Throwable.md)



***

### registerCommand

Register a command into the application.

```php
public registerCommand(\Ninja\Cosmic\Command\CommandInterface $command): \Ninja\Cosmic\Application\Application
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Ninja\Cosmic\Command\CommandInterface** |  |




**Throws:**

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)

- [`InvocationException`](../../../Invoker/Exception/InvocationException.md)

- [`ReflectionException`](../../../ReflectionException.md)

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)



***

### registerCommandInEnvironment

Register a command into the application if the command is available in the specified environment.

```php
private registerCommandInEnvironment(\Ninja\Cosmic\Command\CommandInterface&amp;\Ninja\Cosmic\Command\EnvironmentAwareInterface $command, string $environment): \Ninja\Cosmic\Application\Application
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Ninja\Cosmic\Command\CommandInterface&\Ninja\Cosmic\Command\EnvironmentAwareInterface** |  |
| `$environment` | **string** |  |




**Throws:**

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)

- [`InvocationException`](../../../Invoker/Exception/InvocationException.md)

- [`ReflectionException`](../../../ReflectionException.md)



***

### registerCommands

Register commands from the specified paths.

```php
public registerCommands(array $command_paths): \Ninja\Cosmic\Application\Application
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command_paths` | **array** |  |




**Throws:**

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)

- [`NotFoundExceptionInterface`](../../../Psr/Container/NotFoundExceptionInterface.md)

- [`InvocationException`](../../../Invoker/Exception/InvocationException.md)

- [`ReflectionException`](../../../ReflectionException.md)

- [`ContainerExceptionInterface`](../../../Psr/Container/ContainerExceptionInterface.md)



***

### withContainer

Set the container to use for resolving command dependencies.

```php
public withContainer(\Psr\Container\ContainerInterface $container, bool $byTypeHint = false, bool $byParameterName = false): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$container` | **\Psr\Container\ContainerInterface** |  |
| `$byTypeHint` | **bool** |  |
| `$byParameterName` | **bool** |  |




**Throws:**

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)



***

### command

Creates a new command from a callable.

```php
public command(string $expression, callable|string $callable, array $aliases = []): \Ninja\Cosmic\Command\Command
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$expression` | **string** |  |
| `$callable` | **callable&#124;string** |  |
| `$aliases` | **array** |  |




**Throws:**

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)

- [`ReflectionException`](../../../ReflectionException.md)



***

### createCommand



```php
private createCommand(string $expression, callable $callable): \Ninja\Cosmic\Command\Command
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$expression` | **string** |  |
| `$callable` | **callable** |  |





***

### createInvoker



```php
private static createInvoker(): \Invoker\InvokerInterface
```



* This method is **static**.








***

### createParser



```php
private static createParser(): \Ninja\Cosmic\Command\Parser\ExpressionParser
```



* This method is **static**.








***

### createParameterResolver



```php
private static createParameterResolver(): \Invoker\ParameterResolver\ResolverChain
```



* This method is **static**.








***

### defaultsViaReflection



```php
private defaultsViaReflection(\Ninja\Cosmic\Command\Command $command, mixed $callable): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Ninja\Cosmic\Command\Command** |  |
| `$callable` | **mixed** |  |




**Throws:**

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)

- [`ReflectionException`](../../../ReflectionException.md)



***

### assertCallableIsValid



```php
private assertCallableIsValid(mixed $callable): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callable` | **mixed** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)



***

### isStaticCallToNonStaticMethod



```php
private isStaticCallToNonStaticMethod(mixed $callable): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callable` | **mixed** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)



***

### fromCamelCase



```php
private fromCamelCase(string $input): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **string** |  |





***

### getThemeName



```php
private getThemeName(): string
```












***

### enableTheme



```php
private enableTheme(string $theme): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$theme` | **string** |  |





***


***
> Automatically generated on 2023-12-21
