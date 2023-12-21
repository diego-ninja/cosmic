***

# LifecycleAwareInterface

Interface LifecycleAwareInterface

Defines the contract for an object that is aware of its lifecycle events.

* Full name: `\Ninja\Cosmic\Event\LifecycleAwareInterface`



## Methods


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

### getLifecycleId

Get the unique identifier associated with the object's lifecycle.

```php
public getLifecycleId(): \Ramsey\Uuid\UuidInterface|null
```









**Return Value:**

The UUID representing the lifecycle, or null if not set.




***


***
> Automatically generated on 2023-12-21
