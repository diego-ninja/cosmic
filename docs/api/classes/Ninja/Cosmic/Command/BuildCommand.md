***

# BuildCommand





* Full name: `\Ninja\Cosmic\Command\BuildCommand`
* Parent class: [`\Ninja\Cosmic\Command\CosmicCommand`](./CosmicCommand.md)
* This class is marked as **final** and can't be subclassed
* This class implements:
[`\Ninja\Cosmic\Notifier\NotifiableInterface`](../Notifier/NotifiableInterface.md)
* This class is a **Final class**



## Properties


### builder



```php
private \Ninja\Cosmic\Application\Builder\ApplicationBuilder $builder
```






***

## Methods


### __construct



```php
public __construct(\Ninja\Cosmic\Application\Builder\ApplicationBuilder $builder): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$builder` | **\Ninja\Cosmic\Application\Builder\ApplicationBuilder** |  |





***

### __invoke



```php
public __invoke(?bool $sign): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$sign` | **?bool** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)



***

### extractEnvironments



```php
private extractEnvironments(): array
```












***

### getSuccessMessage

Get the success message for notifications.

```php
public getSuccessMessage(): string
```









**Return Value:**

The success message.




***

### getErrorMessage

Get the error message for notifications.

```php
public getErrorMessage(): string
```









**Return Value:**

The error message.




***


## Inherited methods


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
