***

# Lifecycle

Class Lifecycle

Represents the lifecycle of an event in the application.

* Full name: `\Ninja\Cosmic\Event\Lifecycle`
* This class implements:
[`\Ninja\Cosmic\Event\LifecycleAwareInterface`](./LifecycleAwareInterface.md)



## Properties


### event_listeners



```php
protected static array&lt;string,callable[]&gt; $event_listeners
```



* This property is **static**.


***

### lifecycle_events



```php
protected static array&lt;string,array&gt; $lifecycle_events
```



* This property is **static**.


***

### instance



```php
protected static self|null $instance
```



* This property is **static**.


***

### lifecycle_id



```php
protected ?\Ramsey\Uuid\UuidInterface $lifecycle_id
```






***

## Methods


### __construct

Lifecycle constructor.

```php
public __construct(\Ramsey\Uuid\UuidInterface|null $lifecycle_id = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$lifecycle_id` | **\Ramsey\Uuid\UuidInterface&#124;null** | The UUID representing the lifecycle of an event. |





***

### getLifecycleId

Get the UUID representing the lifecycle of an event.

```php
public getLifecycleId(): \Ramsey\Uuid\UuidInterface|null
```









**Return Value:**

The UUID or null if not set.




***

### withLifecycleId

Create a new instance of Lifecycle with the given lifecycle ID.

```php
public static withLifecycleId(\Ramsey\Uuid\UuidInterface $lifecycle_id): \Ninja\Cosmic\Event\LifecycleAwareInterface
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$lifecycle_id` | **\Ramsey\Uuid\UuidInterface** | The UUID representing the lifecycle of an event. |


**Return Value:**

The new instance of Lifecycle.




***

### getInstance

Get the singleton instance of the Lifecycle class.

```php
public static getInstance(): \Ninja\Cosmic\Event\LifecycleAwareInterface
```



* This method is **static**.





**Return Value:**

The instance of Lifecycle.




***


## Inherited methods


### registerListener

Register a listener for one or multiple lifecycle events.

```php
public static registerListener(string|array $event_name, callable|\Ninja\Cosmic\Event\LifecycleEventListenerInterface $listener): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$event_name` | **string&#124;array** | The name or names of the lifecycle event(s). |
| `$listener` | **callable&#124;\Ninja\Cosmic\Event\LifecycleEventListenerInterface** | The listener to be registered. |




**Throws:**
<p>If the event name is not registered as a valid lifecycle event.</p>

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)



***

### register

Register a listener for a specific lifecycle event.

```php
private register(string $event_name, callable|\Ninja\Cosmic\Event\LifecycleEventListenerInterface $listener): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$event_name` | **string** | The name of the lifecycle event. |
| `$listener` | **callable&#124;\Ninja\Cosmic\Event\LifecycleEventListenerInterface** | The listener to be registered. |




**Throws:**
<p>If the event name is not registered as a valid lifecycle event.</p>

- [`InvalidArgumentException`](../../../InvalidArgumentException.md)



***

### registerLifecycleEvents

Register multiple lifecycle events.

```php
public static registerLifecycleEvents(array $lifecycle_events): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$lifecycle_events` | **array** | The array of lifecycle events to register. |





***

### dispatchLifecycleEvent

Dispatch a lifecycle event to its registered listeners.

```php
public static dispatchLifecycleEvent(string $event_name, array $event_args): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$event_name` | **string** | The name of the lifecycle event to dispatch. |
| `$event_args` | **array** | The arguments to pass to the event listeners. |





***


***
> Automatically generated on 2023-12-21
