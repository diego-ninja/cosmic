***

# ColorCollection





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection`
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

### toArray



```php
public toArray(): array
```












***

### fromArray



```php
public static fromArray(array $input): \Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





***

### color



```php
public color(string $name): ?\Ninja\Cosmic\Terminal\Theme\Element\Color\Color
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





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

### fromFile



```php
public static fromFile(string $file): \Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$file` | **string** |  |




**Throws:**

- [`JsonException`](../../../../../../JsonException.md)



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
