***

# Table





* Full name: `\Ninja\Cosmic\Terminal\Table\Table`



## Properties


### columns



```php
protected \Ninja\Cosmic\Terminal\Table\Column\ColumnCollection $columns
```






***

### data



```php
protected array $data
```






***

### config



```php
protected \Ninja\Cosmic\Terminal\Table\TableConfig $config
```






***

### title



```php
protected ?string $title
```






***

## Methods


### __construct



```php
public __construct(array $data, ?array $columns, \Ninja\Cosmic\Terminal\Table\TableConfig $config, ?string $title = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |
| `$columns` | **?array** |  |
| `$config` | **\Ninja\Cosmic\Terminal\Table\TableConfig** |  |
| `$title` | **?string** |  |





***

### injectData



```php
public injectData(array $data): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |





***

### getData



```php
public getData(): ?array
```












***

### getColumns



```php
public getColumns(): \Ninja\Cosmic\Terminal\Table\Column\ColumnCollection
```












***

### addColumn



```php
public addColumn(\Ninja\Cosmic\Terminal\Table\Column\TableColumn $field): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$field` | **\Ninja\Cosmic\Terminal\Table\Column\TableColumn** |  |





***

### addRow



```php
public addRow(array $row): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$row` | **array** |  |





***

### setTitle



```php
public setTitle(?string $title): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$title` | **?string** |  |





***

### display



```php
public display(?\Symfony\Component\Console\Output\OutputInterface $output): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **?\Symfony\Component\Console\Output\OutputInterface** |  |





***

### render



```php
public render(): string
```












***

### formatRow



```php
protected formatRow(array $rowData, array $columnLengths, bool $header = false): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$rowData` | **array** |  |
| `$columnLengths` | **array** |  |
| `$header` | **bool** |  |





***

### getTitleTop



```php
protected getTitleTop(array $columnLengths): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$columnLengths` | **array** |  |





***

### formatTitleRow



```php
protected formatTitleRow(string $title, array $columnLengths): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$title` | **string** |  |
| `$columnLengths` | **array** |  |





***

### getTableTop



```php
protected getTableTop(array $columnLengths): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$columnLengths` | **array** |  |





***

### getTableBottom



```php
protected getTableBottom(array $columnLengths): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$columnLengths` | **array** |  |





***

### getTableSeparator



```php
protected getTableSeparator(array $columnLengths): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$columnLengths` | **array** |  |





***

### getChar



```php
protected getChar(string $type, int $length = 1): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **string** |  |
| `$length` | **int** |  |





***

### getPluralItemName



```php
protected getPluralItemName(): string
```












***


***
> Automatically generated on 2023-12-21
