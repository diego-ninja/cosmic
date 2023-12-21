***

# ExpressionParser





* Full name: `\Ninja\Cosmic\Command\Parser\ExpressionParser`




## Methods


### parse



```php
public parse(mixed $expression): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$expression` | **mixed** |  |





***

### isOption



```php
private isOption(mixed $token): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$token` | **mixed** |  |





***

### parseArgument



```php
private parseArgument(mixed $token): \Ninja\Cosmic\Command\Input\Argument
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$token` | **mixed** |  |





***

### parseOption



```php
private parseOption(mixed $token): \Ninja\Cosmic\Command\Input\Option
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$token` | **mixed** |  |





***


***
> Automatically generated on 2023-12-21
