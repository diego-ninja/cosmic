***

# StyleCollection





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection`
* Parent class: [`\Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection`](../AbstractElementCollection.md)




## Methods


### getType



```php
public getType(): string
```












***

### load



```php
public load(\Symfony\Component\Console\Output\OutputInterface $output): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





***

### getCollectionType



```php
public getCollectionType(): string
```












***

### style



```php
public style(string $name): ?\Ninja\Cosmic\Terminal\Theme\Element\Style\AbstractStyle
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### fromFile



```php
public static fromFile(string $file): \Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$file` | **string** |  |





***

### fromArray



```php
public static fromArray(array $data): \Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |





***

### toArray



```php
public toArray(): array
```












***

### termwind



```php
public termwind(string $name): ?\Ninja\Cosmic\Terminal\Theme\Element\Style\TermwindStyle
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### symfony



```php
public symfony(string $name): ?\Ninja\Cosmic\Terminal\Theme\Element\Style\SymfonyStyle
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***


## Inherited methods


### getType



```php
public getType(): string
```




* This method is **abstract**.







***

### getCollectionType



```php
public getCollectionType(): string
```




* This method is **abstract**.







***


***
> Automatically generated on 2023-12-21
