***

# DeserializableTrait





* Full name: `\Ninja\Cosmic\Serializer\DeserializableTrait`




## Methods


### fromArray



```php
public static fromArray(array $data): static
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)

- [`UnexpectedValueException`](../Exception/UnexpectedValueException.md)



***

### fromJson



```php
public static fromJson(string $json): static
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$json` | **string** |  |




**Throws:**

- [`ReflectionException`](../../../ReflectionException.md)

- [`UnexpectedValueException`](../Exception/UnexpectedValueException.md)

- [`JsonException`](../../../JsonException.md)



***

### getPropertyType



```php
protected getPropertyType(string $property, ?string $classname = null): ?string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$property` | **string** |  |
| `$classname` | **?string** |  |




**Throws:**

- [`UnexpectedValueException`](../Exception/UnexpectedValueException.md)



***

### isPropertyNullable



```php
protected isPropertyNullable(string $property, ?string $classname = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$property` | **string** |  |
| `$classname` | **?string** |  |




**Throws:**

- [`UnexpectedValueException`](../Exception/UnexpectedValueException.md)



***

### hasMethod



```php
protected hasMethod(string $method): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** |  |





***

### isDateTime



```php
protected isDateTime(string $class): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$class` | **string** |  |





***

### getNormalizedValue



```php
protected getNormalizedValue(string $type, mixed $value): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **string** |  |
| `$value` | **mixed** |  |





***

### getDateTimeObject



```php
private getDateTimeObject(string $type, mixed $value): ?\DateTimeInterface
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **string** |  |
| `$value` | **mixed** |  |





***

***
> Automatically generated on 2023-12-21

