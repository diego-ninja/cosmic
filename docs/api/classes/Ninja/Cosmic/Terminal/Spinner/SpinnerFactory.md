***

# SpinnerFactory





* Full name: `\Ninja\Cosmic\Terminal\Spinner\SpinnerFactory`
* Parent class: [`\Ninja\Cosmic\Terminal\Spinner\Spinner`](./Spinner.md)




## Methods


### for



```php
public static for(\Symfony\Component\Process\Process|callable $callable, string $message, ?string $style = null): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callable` | **\Symfony\Component\Process\Process&#124;callable** |  |
| `$message` | **string** |  |
| `$style` | **?string** |  |




**Throws:**

- [`Exception`](../../../../Exception.md)



***

### forProcess



```php
private static forProcess(\Symfony\Component\Process\Process $process, string $message, ?string $style = null): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$process` | **\Symfony\Component\Process\Process** |  |
| `$message` | **string** |  |
| `$style` | **?string** |  |




**Throws:**

- [`Exception`](../../../../Exception.md)



***

### forCallable



```php
private static forCallable(callable $callback, string $message, ?string $style = Spinner::DEFAULT_SPINNER_STYLE): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |
| `$message` | **string** |  |
| `$style` | **?string** |  |




**Throws:**

- [`Exception`](../../../../Exception.md)



***


## Inherited methods


### __construct



```php
public __construct(?string $style = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$style` | **?string** |  |





***

### setMessage



```php
public setMessage(string $message): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** |  |





***

### setPadding



```php
public setPadding(int $padding): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$padding` | **int** |  |





***

### getSpinnerFrames



```php
private getSpinnerFrames(): string[]
```












***

### loopSpinnerFrames



```php
private loopSpinnerFrames(): void
```












***

### addPadding



```php
private addPadding(): string
```












***

### reset



```php
private reset(): void
```












***

### keyboardInterrupts



```php
private keyboardInterrupts(): void
```












***

### callback



```php
public callback(callable $callback): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |




**Throws:**

- [`Exception`](../../../../Exception.md)



***

### runCallBack



```php
private runCallBack(callable $callback): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |





***

### success



```php
private success(): void
```












***

### failure



```php
private failure(): void
```












***


***
> Automatically generated on 2023-12-21
