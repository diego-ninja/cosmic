***

# SelectInputInterface





* Full name: `\Ninja\Cosmic\Terminal\Input\Select\Input\SelectInputInterface`



## Methods


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

### controlMode



```php
public controlMode(): int
```












***


***
> Automatically generated on 2023-12-21
