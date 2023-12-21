***

# TableConfig





* Full name: `\Ninja\Cosmic\Terminal\Table\TableConfig`
* Parent class: [`Config`](../../../../PHLAK/Config/Config.md)


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`DEFAULT_TABLE_COLOR`|public| |&quot;white&quot;|
|`DEFAULT_HEADER_COLOR`|public| |&quot;white&quot;|
|`DEFAULT_TITLE_COLOR`|public| |&quot;notice&quot;|
|`DEFAULT_FIELD_COLOR`|public| |&quot;white&quot;|
|`DEFAULT_SHOW_HEADER`|public| |true|
|`DEFAULT_ITEM_NAME`|public| |&quot;row&quot;|
|`DEFAULT_PADDING`|public| |1|
|`DEFAULT_CENTER_CONTENT`|public| |false|
|`DEFAULT_CHARSET`|public| |&quot;double&quot;|


## Methods


### __construct



```php
public __construct(array|string $context = null, string $prefix = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$context` | **array&#124;string** |  |
| `$prefix` | **string** |  |





***

### getCharset



```php
public getCharset(): array
```












***

### getChar



```php
public getChar(string $char): ?string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$char` | **string** |  |





***

### hasChar



```php
public hasChar(string $char): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$char` | **string** |  |





***

### setCharset



```php
public setCharset(array $charset): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$charset` | **array** |  |





***

### getItemName



```php
public getItemName(): string
```












***

### setItemName



```php
public setItemName(string $itemName): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$itemName` | **string** |  |





***

### getTableColor



```php
public getTableColor(): string
```












***

### setTableColor



```php
public setTableColor(string $tableColor): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tableColor` | **string** |  |





***

### getHeaderColor



```php
public getHeaderColor(): string
```












***

### setHeaderColor



```php
public setHeaderColor(string $headerColor): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$headerColor` | **string** |  |





***

### getTitleColor



```php
public getTitleColor(): string
```












***

### setTitleColor



```php
public setTitleColor(string $titleColor): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$titleColor` | **string** |  |





***

### getFieldColor



```php
public getFieldColor(): string
```












***

### setFieldColor



```php
public setFieldColor(string $fieldColor): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$fieldColor` | **string** |  |





***

### getShowHeader



```php
public getShowHeader(): bool
```












***

### setShowHeader



```php
public setShowHeader(bool $showHeader): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$showHeader` | **bool** |  |





***

### getPadding



```php
public getPadding(): int
```












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

### getCenterContent



```php
public getCenterContent(): bool
```












***

### setCenterContent



```php
public setCenterContent(bool $centerContent): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$centerContent` | **bool** |  |





***

### getDefaultConfig



```php
private getDefaultConfig(): array
```












***


***
> Automatically generated on 2023-12-21
