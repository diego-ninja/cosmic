***

# SelectHandler





* Full name: `\Ninja\Cosmic\Terminal\Input\Select\Handler\SelectHandler`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`SIMPLE_CTR`|public| |0x1|
|`COMPLEX_CTR`|public| |0x2|
|`DEFAULT_CTR`|public| |self::COMPLEX_CTR|

## Properties


### row



```php
protected int $row
```






***

### column



```php
protected int $column
```






***

### firstRun



```php
protected bool $firstRun
```






***

### question



```php
protected \Ninja\Cosmic\Terminal\Input\Select\Input\SelectInputInterface&amp;\Ninja\Cosmic\Terminal\Input\Select\Input\ColumnAwareInterface $question
```






***

### output



```php
protected \Symfony\Component\Console\Output\OutputInterface $output
```






***

### stream



```php
protected mixed $stream
```






***

### columns



```php
protected ?int $columns
```






***

### terminalWidth



```php
protected ?int $terminalWidth
```






***

## Methods


### __construct



```php
public __construct(\Ninja\Cosmic\Terminal\Input\Select\Input\SelectInputInterface&amp;\Ninja\Cosmic\Terminal\Input\Select\Input\ColumnAwareInterface $question, \Symfony\Component\Console\Output\OutputInterface $output, resource $stream = STDIN, ?int $columns = null, ?int $terminalWidth = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$question` | **\Ninja\Cosmic\Terminal\Input\Select\Input\SelectInputInterface&\Ninja\Cosmic\Terminal\Input\Select\Input\ColumnAwareInterface** |  |
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |
| `$stream` | **resource** |  |
| `$columns` | **?int** |  |
| `$terminalWidth` | **?int** |  |





***

### handle

Navigates through option items.

```php
public handle(): list&lt;string&gt;
```












***

### exists



```php
public exists(int $row, int $column): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **int** |  |
| `$column` | **int** |  |





***

### up



```php
protected up(): void
```












***

### down



```php
protected down(): void
```












***

### left



```php
protected left(): void
```












***

### right



```php
protected right(): void
```












***

### select



```php
protected select(): void
```












***

### navigate



```php
protected navigate(string $char, int $ctrlMode = self::DEFAULT_CTR): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$char` | **string** |  |
| `$ctrlMode` | **int** |  |





***

### repaint



```php
public repaint(): void
```












***

### clear



```php
public clear(): void
```












***

### finalClear



```php
public finalClear(): void
```












***

### message



```php
protected message(): string
```












***

### makeRow



```php
protected makeRow(array $entries, int $activeColumn, int $columnSpace): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$entries` | **array** |  |
| `$activeColumn` | **int** |  |
| `$columnSpace` | **int** |  |





***

### makeCell



```php
protected makeCell(string $option, bool $hasCursor = false, int $maxWidth = 20): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$option` | **string** |  |
| `$hasCursor` | **bool** |  |
| `$maxWidth` | **int** |  |





***

### checkbox



```php
protected checkbox(string $name, bool $selected, bool $hasCursor, int $maxWidth): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$selected` | **bool** |  |
| `$hasCursor` | **bool** |  |
| `$maxWidth` | **int** |  |





***

### radio



```php
protected radio(string $name, bool $selected, bool $hasCursor, int $maxWidth): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$selected` | **bool** |  |
| `$hasCursor` | **bool** |  |
| `$maxWidth` | **int** |  |





***

### terminalWidth



```php
public terminalWidth(): int
```












***

### getColumns



```php
public getColumns(): int
```












***

### columnSize



```php
public columnSize(): int
```












***

### isMultiple



```php
protected isMultiple(): bool
```












***


***
> Automatically generated on 2023-12-21
