***

# KeyParser





* Full name: `\Ninja\Cosmic\Crypt\Parser\KeyParser`




## Methods


### extractKeys



```php
public extractKeys(string $keyType): \Ninja\Cosmic\Crypt\KeyCollection
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$keyType` | **string** |  |





***

### stripHeader



```php
private stripHeader(string $string, int $length): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** |  |
| `$length` | **int** |  |





***

### parseKeyInfo



```php
private parseKeyInfo(string $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **string** |  |





***

### buildKey



```php
private buildKey(array $keyData): \Ninja\Cosmic\Crypt\KeyInterface
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$keyData` | **array** |  |





***

### parseUidInfo



```php
private parseUidInfo(string $string): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** |  |





***

### getCommand



```php
private getCommand(string $keyType): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$keyType` | **string** |  |





***


***
> Automatically generated on 2023-12-21
