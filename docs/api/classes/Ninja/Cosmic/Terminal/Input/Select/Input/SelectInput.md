***

# SelectInput





* Full name: `\Ninja\Cosmic\Terminal\Input\Select\Input\SelectInput`
* Parent class: [`\Ninja\Cosmic\Terminal\Input\Select\Input\RadioInput`](./RadioInput.md)
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**




## Methods


### __construct



```php
public __construct(string $message, array $options): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** |  |
| `$options` | **array** |  |





***

### controlMode



```php
public controlMode(): int
```












***

### getFirstSelection



```php
public getFirstSelection(): string
```












***


## Inherited methods


### select



```php
public select(string $option): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$option` | **string** |  |





***

### deselect



```php
public deselect(string $option): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$option` | **string** |  |





***

### __construct



```php
public __construct(string $message, array $options, array $defaultSelection = []): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** |  |
| `$options` | **array** |  |
| `$defaultSelection` | **array** |  |





***

### getMessage



```php
public getMessage(): string
```












***

### getOptions



```php
public getOptions(): array
```












***

### getSelections



```php
public getSelections(): array
```












***

### hasSelections



```php
public hasSelections(): bool
```












***

### isSelected



```php
public isSelected(string $option): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$option` | **string** |  |





***

### controlMode



```php
public controlMode(): int
```












***

### getColumns



```php
public getColumns(int $columnSize = null): array&lt;int,list&lt;string&gt;&gt;
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$columnSize` | **int** |  |





***

### getColumnAt



```php
public getColumnAt(int $index): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$index` | **int** |  |





***

### getColumnCount



```php
public getColumnCount(): int
```












***

### hasEntryAt



```php
public hasEntryAt(int $rowIndex, int $colIndex): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rowIndex` | **int** |  |
| `$colIndex` | **int** |  |





***

### getEntryAt



```php
public getEntryAt(int $rowIndex, int $colIndex): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rowIndex` | **int** |  |
| `$colIndex` | **int** |  |





***


***
> Automatically generated on 2023-12-21
