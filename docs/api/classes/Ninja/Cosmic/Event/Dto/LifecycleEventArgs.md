***

# LifecycleEventArgs

Class LifecycleEventArgs

Represents the arguments associated with a lifecycle event.

* Full name: `\Ninja\Cosmic\Event\Dto\LifecycleEventArgs`
* This class is marked as **final** and can't be subclassed
* This class is a **Final class**



## Properties


### lifecycle_event



```php
private string $lifecycle_event
```






***

### args



```php
private array $args
```






***

### lifecycle_id



```php
private ?\Ramsey\Uuid\UuidInterface $lifecycle_id
```






***

## Methods


### __construct



```php
public __construct(string $lifecycle_event, array $args, \Ramsey\Uuid\UuidInterface|null $lifecycle_id): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$lifecycle_event` | **string** | The name of the lifecycle event. |
| `$args` | **array** | The arguments associated with the event. |
| `$lifecycle_id` | **\Ramsey\Uuid\UuidInterface&#124;null** | The UUID associated with the lifecycle event, if any. |





***

### getLifecycleId

Gets the UUID associated with the lifecycle event.

```php
public getLifecycleId(): \Ramsey\Uuid\UuidInterface|null
```









**Return Value:**

The UUID associated with the lifecycle event.




***

### getLifecycleEvent

Gets the name of the lifecycle event.

```php
public getLifecycleEvent(): string
```









**Return Value:**

The name of the lifecycle event.




***

### getArgs

Gets the arguments associated with the event.

```php
public getArgs(): array
```









**Return Value:**

The arguments associated with the event.




***

### __call

Magic method to support getter and setter methods dynamically.

```php
public __call(string $method, array $args): mixed|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** | The method name. |
| `$args` | **array** | The method arguments. |


**Return Value:**

The value if it exists, otherwise null.




***

### isSetter

Checks if the method is a setter.

```php
private isSetter(string $method): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** | The method name. |


**Return Value:**

Whether the method is a setter.




***

### isGetter

Checks if the method is a getter.

```php
private isGetter(string $method): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** | The method name. |


**Return Value:**

Whether the method is a getter.




***

### getProperty

Converts a method name to a property name.

```php
protected getProperty(string $method): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$method` | **string** | The method name. |


**Return Value:**

The property name.




***


***
> Automatically generated on 2023-12-21
