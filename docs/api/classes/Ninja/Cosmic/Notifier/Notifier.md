***

# Notifier

Class Notifier

Provides a simple interface for sending notifications.

* Full name: `\Ninja\Cosmic\Notifier\Notifier`



## Properties


### instance



```php
private static ?self $instance
```



* This property is **static**.


***

### notifier



```php
private \Joli\JoliNotif\Notifier $notifier
```






***

## Methods


### __construct

Notifier constructor.

```php
private __construct(): mixed
```











**Throws:**

- [`InvalidNotificationException`](../../../Joli/JoliNotif/Exception/InvalidNotificationException.md)



***

### getInstance

Gets an instance of the Notifier.

```php
public static getInstance(): \Ninja\Cosmic\Notifier\Notifier
```



* This method is **static**.





**Return Value:**

The Notifier instance.




***

### success

Sends a success notification with the specified message.

```php
public static success(string $message): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The notification message. |





***

### error

Sends an error notification with the specified message.

```php
public static error(string $message): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The notification message. |





***

### notify

Sends a generic notification with the specified message.

```php
public static notify(string $message): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The notification message. |





***

### notifySuccess

Sends a success notification with the specified message.

```php
private notifySuccess(string $message): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The notification message. |





***

### notifyError

Sends an error notification with the specified message.

```php
private notifyError(string $message): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The notification message. |





***

### getSuccessNotification

Gets a success notification with the specified message.

```php
private getSuccessNotification(string $message): \Joli\JoliNotif\Notification
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The notification message. |


**Return Value:**

The success notification.




***

### getErrorNotification

Gets an error notification with the specified message.

```php
private getErrorNotification(string $message): \Joli\JoliNotif\Notification
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The notification message. |


**Return Value:**

The error notification.




***


***
> Automatically generated on 2023-12-21
