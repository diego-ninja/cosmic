***

# CharsetCollection





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection`
* Parent class: [`\Ninja\Cosmic\Terminal\Theme\Element\AbstractElementCollection`](../AbstractElementCollection.md)




## Methods


### getType



```php
public getType(): string
```












***

### getCollectionType



```php
public getCollectionType(): string
```












***

### charset



```php
public charset(string $name): ?\Ninja\Cosmic\Terminal\Theme\Element\Charset\Charset
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### fromFile



```php
public static fromFile(string $file): \Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$file` | **string** |  |




**Throws:**

- [`JsonException`](../../../../../../JsonException.md)



***

### fromArray



```php
public static fromArray(array $input): \Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





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
