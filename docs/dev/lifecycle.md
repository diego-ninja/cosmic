# 🔫 Lifecycle Events

## What is a Lifecycle Event?

In the context of the `Ninja\Cosmic\Event` namespace, a lifecycle event is a significant occurrence in the life of an object that is part of the application. These events are defined and dispatched by the application, and they can be listened to by other parts of the application.

Lifecycle events are identified by a string name, and they can carry additional data in the form of an associative array. The lifecycle event name and its data are encapsulated in a `LifecycleEventArgs` object.

## What is a Listener?

A listener is a callable or an object implementing the `LifecycleEventListenerInterface` that is registered to a lifecycle event. When the event is dispatched, the listener is invoked with the `LifecycleEventArgs` object as an argument.

## Dispatching an event

Dispatching a lifecycle event is done by calling the `dispatchLifecycleEvent` static method on the `LifecycleAwareInterface` with the event name and an array of event arguments. The method will iterate over all registered listeners for the event and invoke them with a `LifecycleEventArgs` object.

## Registering and executing a listener

A listener can be registered to a lifecycle event by calling the `registerListener` static method on the `LifecycleAwareInterface`. The method accepts the event name (or an array of event names) and the listener. The listener can be a callable or an object implementing the `LifecycleEventListenerInterface`.

When a lifecycle event is dispatched, all its registered listeners are executed. Each listener is invoked with a `LifecycleEventArgs` object.

## Defined Lifecycle Events

The specific lifecycle events that are defined and when they occur depend on the application. They are registered by calling the `registerLifecycleEvents` static method on the `LifecycleAwareInterface` with an array of event names.

The `LifecycleAwareInterface` does not define any specific lifecycle events. Instead, it provides the mechanism for registering and dispatching lifecycle events. The actual lifecycle events are defined by the application.

It is a recommended practice to register a minimal set of lifecycle events on application bootstrap time, the applications generated with cosmic init command, define the following set of events in the file lifecycle.php located in /bootstrap folder

```php
<?php  
  
declare(strict_types=1);  
  
use Ninja\Cosmic\Application\Application;  
use Ninja\Cosmic\Command\CommandInterface;  
use Ninja\Cosmic\Event\Lifecycle;  
use Ramsey\Uuid\Uuid;  
  
(static function (): void {  
    Lifecycle::withLifecycleId(Uuid::uuid7());  
    Lifecycle::registerLifecycleEvents([  
        Application::LIFECYCLE_APP_BOOT,  
        Application::LIFECYCLE_APP_SHUTDOWN,  
        Application::LIFECYCLE_APP_BUILD,  
        Application::LIFECYCLE_APP_INSTALL,  
        CommandInterface::LIFECYCLE_COMMAND_RUN,  
        CommandInterface::LIFECYCLE_COMMAND_SUCCESS,  
        CommandInterface::LIFECYCLE_COMMAND_FAILURE,  
        CommandInterface::LIFECYCLE_COMMAND_ERROR  
    ]);  
})();
```


## LifecycleEventArgs class

This is used to encapsulate the lifecycle event name, its arguments, and an optional lifecycle ID. It is used as payload to transfer data among the lifecycle event and its listeners.

###  Class Properties

The class has three properties:

- `lifecycle_event`: A string representing the lifecycle event name.
- `args`: An array holding the arguments of the lifecycle event.
- `lifecycle_id`: An optional UUID representing the lifecycle ID.

## Magic Methods

The class uses the magic `__call` method to provide dynamic getter and setter methods for the `args` array.

### The `__call` Method

The `__call` method is invoked when an inaccessible method is called on the object. It checks if the method name starts with "get" or "set". If it does, it treats the method as a getter or setter for the `args` array.

- If the method is a getter (i.e., starts with "get"), it returns the value from the `args` array corresponding to the property name.
- If the method is a setter (i.e., starts with "set"), it sets the value in the `args` array corresponding to the property name.

The property name is derived from the method name by removing the "get" or "set" prefix and converting the remaining string to snake case.

## Other Methods

The class also has getter methods for the `lifecycle_event` and `lifecycle_id` properties, and a `getArgs` method to return the entire `args` array.

Here is a brief example of how you can use the `LifecycleEventArgs` class:

```php
// Instantiate the LifecycleEventArgs class
$eventArgs = new LifecycleEventArgs('onUserLogin', ['username' => 'diego-ninja'], $uuid);

// Use the magic __call method to set a new argument
$eventArgs->setEmail('diego-ninja@example.com');

// Use the magic __call method to get an argument
$email = $eventArgs->getEmail();
```

In the above example, the `setEmail` and `getEmail` methods do not actually exist in the `LifecycleEventArgs` class. They are handled by the `__call` method, which treats them as a setter and getter for the `args` array, respectively.

## Working example

First, let's define a new lifecycle event. We'll call it `onUserLogin`. We can register this event by calling the `registerLifecycleEvents` method on the `LifecycleAwareInterface`.

```php
LifecycleAwareInterface::registerLifecycleEvents(['onUserLogin']);
```

Next, let's define a listener for this event. A listener can be any callable or an object implementing the `LifecycleEventListenerInterface`. For this example, let's create a simple callable listener that prints a message when the event is dispatched.

```php
$listener = function (LifecycleEventArgs $args) {
    echo "User with ID {$args->getUser()->getName()} has logged in.";
};
```

Now we can register this listener to the `onUserLogin` event by calling the `registerListener` method on the `LifecycleAwareInterface`.

```php
LifecycleAwareInterface::registerListener('onUserLogin', $listener);
```

Now, whenever the `onUserLogin` event is dispatched, our listener will be invoked and print a message to the console.
