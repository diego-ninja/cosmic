***

# Spinner





* Full name: `\Ninja\Cosmic\Terminal\Spinner\Spinner`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`DEFAULT_SPINNER_STYLE`|public| |&#039;dots8Bit&#039;|
|`DEFAULT_SPINNER_INTERVAL`|public| |1000|
|`DEFAULT_SPINNER_PADDING`|public| |2|
|`BLINK_OFF`|public| |&quot;\x1b[?25l&quot;|
|`BLINK_ON`|public| |&quot;\x1b[?25h&quot;|
|`CLEAR_LINE`|public| |&quot;\x1b[2K\r&quot;|
|`RETURN_TO_LEFT`|public| |&quot;\r&quot;|

## Properties


### child_pid



```php
private int $child_pid
```






***

### spinner



```php
private array $spinner
```






***

### padding



```php
private int $padding
```






***

### message



```php
private string $message
```






***

### style



```php
private ?string $style
```






***

## Methods


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
