***

# SignCommand





* Full name: `\Ninja\Cosmic\Command\SignCommand`
* Parent class: [`\Ninja\Cosmic\Command\CosmicCommand`](./CosmicCommand.md)




## Methods


### __invoke



```php
public __invoke(string $binary, ?string $user, ?string $keyId): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$binary` | **string** |  |
| `$user` | **?string** |  |
| `$keyId` | **?string** |  |




**Throws:**

- [`BinaryNotFoundException`](../Exception/BinaryNotFoundException.md)



***

### hasGPG



```php
private hasGPG(): bool
```











**Throws:**

- [`BinaryNotFoundException`](../Exception/BinaryNotFoundException.md)



***

### tryUserSign



```php
private tryUserSign(string $binary, string $user): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$binary` | **string** |  |
| `$user` | **string** |  |





***

### selectKey



```php
private selectKey(array $keys): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$keys` | **array** |  |





***

### tryKeyIdSign



```php
private tryKeyIdSign(string $binary, string $keyId): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$binary` | **string** |  |
| `$keyId` | **string** |  |





***

### tryDefaultSign



```php
private tryDefaultSign(string $binary): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$binary` | **string** |  |





***

### sign



```php
private sign(string $binary, \Ninja\Cosmic\Crypt\AbstractKey $key): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$binary` | **string** |  |
| `$key` | **\Ninja\Cosmic\Crypt\AbstractKey** |  |





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
