***

# CosmicCommand





* Full name: `\Ninja\Cosmic\Command\CosmicCommand`
* This class implements:
[`\Ninja\Cosmic\Command\CommandInterface`](./CommandInterface.md), [`\Ninja\Cosmic\Command\EnvironmentAwareInterface`](./EnvironmentAwareInterface.md)



## Properties


### executionResult



```php
protected bool $executionResult
```






***

### reflector



```php
protected \ReflectionClass $reflector
```






***

### application



```php
protected \Ninja\Cosmic\Application\Application $application
```






***

## Methods


### __construct



```php
public __construct(): mixed
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### register



```php
public register(\Ninja\Cosmic\Application\Application $app): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$app` | **\Ninja\Cosmic\Application\Application** |  |




**Throws:**

- [`NotCallableException`](../../../Invoker/Exception/NotCallableException.md)

- [`InvocationException`](../../../Invoker/Exception/InvocationException.md)

- [`ReflectionException`](../../../ReflectionException.md)



***

### getApplication



```php
public getApplication(): \Ninja\Cosmic\Application\Application
```












***

### setApplication



```php
public setApplication(\Ninja\Cosmic\Application\Application $app): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$app` | **\Ninja\Cosmic\Application\Application** |  |





***

### success



```php
protected success(): int
```












***

### failure



```php
protected failure(): int
```












***

### exit



```php
protected exit(): int
```












***


## Inherited methods


### getCommandName



```php
public getCommandName(): string
```












***

### getSignature



```php
public getSignature(): string
```












***

### getCommandDescription



```php
public getCommandDescription(): string
```












***

### getArgumentDescriptions



```php
public getArgumentDescriptions(): array
```












***

### getDefaults



```php
public getDefaults(): array
```












***

### getAliases



```php
public getAliases(): array
```












***

### getCommandIcon



```php
public getCommandIcon(): string
```












***

### getCommandHelp



```php
public getCommandHelp(): ?string
```












***

### isAvailableIn



```php
public isAvailableIn(string $environment): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$environment` | **string** |  |





***

### getAvailableEnvironments



```php
public getAvailableEnvironments(): array
```












***

### isHidden



```php
public isHidden(): bool
```












***

### isDecorated



```php
public isDecorated(): bool
```












***

### getName



```php
public getName(): string
```












***


***
> Automatically generated on 2023-12-21
