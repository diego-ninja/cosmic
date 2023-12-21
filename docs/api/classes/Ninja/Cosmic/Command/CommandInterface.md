***

# CommandInterface





* Full name: `\Ninja\Cosmic\Command\CommandInterface`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`LIFECYCLE_COMMAND_RUN`|public| |&#039;command.run&#039;|
|`LIFECYCLE_COMMAND_SUCCESS`|public| |&#039;command.success&#039;|
|`LIFECYCLE_COMMAND_FAILURE`|public| |&#039;command.failure&#039;|
|`LIFECYCLE_COMMAND_ERROR`|public| |&#039;command.error&#039;|

## Methods


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

### getAliases



```php
public getAliases(): array
```












***

### isHidden



```php
public isHidden(): bool
```












***

### register



```php
public register(\Ninja\Cosmic\Application\Application $app): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$app` | **\Ninja\Cosmic\Application\Application** |  |





***

### isDecorated



```php
public isDecorated(): bool
```












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


***
> Automatically generated on 2023-12-21
