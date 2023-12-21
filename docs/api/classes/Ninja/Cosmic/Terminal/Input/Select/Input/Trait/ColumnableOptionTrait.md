***

# ColumnableOptionTrait





* Full name: `\Ninja\Cosmic\Terminal\Input\Select\Input\Trait\ColumnableOptionTrait`



## Properties


### columns



```php
protected array $columns
```






***

### columnSize



```php
protected int $columnSize
```






***

## Methods


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

