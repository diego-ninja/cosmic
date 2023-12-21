***

# LifecycleTrait

Trait LifecycleTrait

Provides methods to register and dispatch lifecycle events.

* Full name: `\Ninja\Cosmic\Event\LifecycleTrait`




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

