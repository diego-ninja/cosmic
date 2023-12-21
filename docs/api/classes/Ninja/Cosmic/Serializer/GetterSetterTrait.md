***

# GetterSetterTrait





* Full name: `\Ninja\Cosmic\Serializer\GetterSetterTrait`




## Methods


### __call



```php
public __call(string $method, array $args): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** |  |
| `$args` | **array** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)



***

### isSetter



```php
protected isSetter(string $method): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** |  |





***

### isGetter



```php
protected isGetter(string $method): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** |  |





***

### getProperty



```php
protected getProperty(string $method): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** |  |





***

### isScalarOrValidObject



```php
protected isScalarOrValidObject(mixed $object, string $property): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$object` | **mixed** |  |
| `$property` | **string** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)



***

### isUninitialized



```php
protected isUninitialized(object $object): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$object` | **object** |  |





***

***
> Automatically generated on 2023-12-21

