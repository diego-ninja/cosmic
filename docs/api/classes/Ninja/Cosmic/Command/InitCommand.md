***

# InitCommand





* Full name: `\Ninja\Cosmic\Command\InitCommand`
* Parent class: [`\Ninja\Cosmic\Command\CosmicCommand`](./CosmicCommand.md)
* This class is marked as **final** and can't be subclassed
* This class implements:
[`\Ninja\Cosmic\Notifier\NotifiableInterface`](../Notifier/NotifiableInterface.md)
* This class is a **Final class**


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`LICENSES`|public| |[&quot;Apache-2.0&quot;, &quot;BSD-2-Clause&quot;, &quot;BSD-3-Clause&quot;, &quot;GPL-2.0-only&quot;, &quot;GPL-2.0-or-later&quot;, &quot;GPL-3.0-only&quot;, &quot;GPL-3.0-or-later&quot;, &quot;LGPL-2.1-only&quot;, &quot;LGPL-2.1-or-later&quot;, &quot;LGPL-3.0-only&quot;, &quot;LGPL-3.0-or-later&quot;, &quot;MIT&quot;, &quot;MPL-2.0&quot;, &quot;Proprietary&quot;, &quot;Unlicensed&quot;]|

## Properties


### replacements



```php
private static array $replacements
```



* This property is **static**.


***

### summary



```php
private static array $summary
```



* This property is **static**.


***

## Methods


### __invoke



```php
public __invoke(?string $name, ?string $path): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **?string** |  |
| `$path` | **?string** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)

- [`Exception`](../../../Exception.md)



***

### expandApplication



```php
private expandApplication(): bool
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### replacePlaceholders



```php
private replacePlaceholders(): bool
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### installApplicationDependencies



```php
private installApplicationDependencies(): bool
```












***

### renameApplication



```php
private renameApplication(): bool
```











**Throws:**

- [`Exception`](../../../Exception.md)



***

### askPackageName



```php
private askPackageName(): void
```












***

### askApplicationPath



```php
private askApplicationPath(): void
```












***

### askApplicationDescription



```php
private askApplicationDescription(): void
```












***

### askApplicationAuthor



```php
private askApplicationAuthor(): void
```












***

### askApplicationWebsite



```php
private askApplicationWebsite(): void
```












***

### askApplicationLicense



```php
private askApplicationLicense(): void
```












***

### askSudoPassword



```php
private askSudoPassword(): void
```












***

### displaySummary



```php
private displaySummary(): void
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
