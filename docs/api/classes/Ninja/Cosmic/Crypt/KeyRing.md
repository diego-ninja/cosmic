***

# KeyRing

Class KeyRing

A collection of cryptographic keys.

* Full name: `\Ninja\Cosmic\Crypt\KeyRing`



## Properties


### keys



```php
private \Ninja\Cosmic\Crypt\KeyCollection $keys
```






***

### type



```php
private string $type
```






***

## Methods


### __construct

KeyRing constructor.

```php
public __construct(string $type = KeyInterface::GPG_TYPE_PUBLIC): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **string** | The type of keys in the keyring. |





***

### public

Create a new public keyring.

```php
public static public(): self
```



* This method is **static**.





**Return Value:**

The new public keyring.



**Throws:**

- [`Exception`](../../../Exception.md)



***

### secret

Create a new secret keyring.

```php
public static secret(): self
```



* This method is **static**.





**Return Value:**

The new secret keyring.



**Throws:**

- [`Exception`](../../../Exception.md)



***

### type

Get the type of keys in the keyring.

```php
public type(): string
```









**Return Value:**

The type of keys in the keyring.




***

### count

Get the number of keys in the keyring.

```php
public count(): int
```









**Return Value:**

The number of keys in the keyring.




***

### isEmpty

Check if the keyring is empty.

```php
public isEmpty(): bool
```









**Return Value:**

True if the keyring is empty, false otherwise.




***

### all

Get all keys in the keyring.

```php
public all(): \Ninja\Cosmic\Crypt\KeyCollection
```









**Return Value:**

The collection of keys in the keyring.




***

### add

Add a key to the keyring.

```php
public add(\Ninja\Cosmic\Crypt\KeyInterface $key): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **\Ninja\Cosmic\Crypt\KeyInterface** | The key to add. |





***

### get

Get a key from the keyring by its ID.

```php
public get(string $id): \Ninja\Cosmic\Crypt\KeyInterface
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$id` | **string** | The ID of the key. |


**Return Value:**

The key with the given ID.



**Throws:**

- [`Exception`](../../../Exception.md)



***

### has

Check if the keyring contains a specific key.

```php
public has(\Ninja\Cosmic\Crypt\KeyInterface $key): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **\Ninja\Cosmic\Crypt\KeyInterface** | The key to check for. |


**Return Value:**

True if the keyring contains the key, false otherwise.




***


***
> Automatically generated on 2023-12-21
