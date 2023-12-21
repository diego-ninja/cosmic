***

# KeyCollection

Class KeyCollection

A collection of AbstractKey objects.

* Full name: `\Ninja\Cosmic\Crypt\KeyCollection`
* Parent class: [`AbstractCollection`](../../../Ramsey/Collection/AbstractCollection.md)
* This class implements:
[`\Ninja\Cosmic\Serializer\SerializableInterface`](../Serializer/SerializableInterface.md)




## Methods


### getType

Get the type of elements in the collection.

```php
public getType(): string
```









**Return Value:**

The type of elements in the collection.




***

### getById

Get an AbstractKey object by its ID.

```php
public getById(string $id): \Ninja\Cosmic\Crypt\AbstractKey|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$id` | **string** | The ID of the AbstractKey object. |


**Return Value:**

The AbstractKey object if found, null otherwise.



**Throws:**
<p>If an error occurs.</p>

- [`Exception`](../../../Exception.md)



***

### getByEmail

Get an AbstractKey object(s) by the associated email.

```php
public getByEmail(string $email): array|\Ninja\Cosmic\Crypt\AbstractKey
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$email` | **string** | The email associated with the AbstractKey object(s). |


**Return Value:**

The AbstractKey object(s) if found.



**Throws:**
<p>If an error occurs.</p>

- [`Exception`](../../../Exception.md)



***

### toJson

Convert the collection to a JSON string.

```php
public toJson(): string
```









**Return Value:**

The JSON string representation of the collection.



**Throws:**
<p>If an error occurs during JSON encoding.</p>

- [`JsonException`](../../../JsonException.md)



***

### jsonSerialize

Specify data which should be serialized to JSON.

```php
public jsonSerialize(): array
```









**Return Value:**

The data to be serialized to JSON.




***


***
> Automatically generated on 2023-12-21
