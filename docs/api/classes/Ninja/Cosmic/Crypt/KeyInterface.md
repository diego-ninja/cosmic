***

# KeyInterface

Interface KeyInterface

Provides methods for handling cryptographic keys.

* Full name: `\Ninja\Cosmic\Crypt\KeyInterface`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`GPG_TYPE_PUBLIC`|public| |&#039;pub&#039;|
|`GPG_TYPE_SECRET`|public| |&#039;sec&#039;|
|`GPG_TYPE_SUB`|public| |&#039;sub&#039;|
|`GPG_TYPE_SS`|public| |&#039;ssb&#039;|
|`GPG_PUB_REGEX_PATTERN`|public| |&#039;/(?P&lt;cypher&gt;\\w+)\\/(?P&lt;key_id&gt;\\w+)\\s(?P&lt;created_at&gt;\\d{4}-\\d{2}-\\d{2})\\s\\[(?P&lt;usage&gt;\\w+)\\](?:\\s\\[expires:\\s(?P&lt;expires_at&gt;\\d{4}-\\d{2}-\\d{2})\\])?/&#039;|
|`GPG_UID_REGEX_PATTERN`|public| |&#039;/^uid\\s+\\[\\s*(?P&lt;trustLevel&gt;.*?)\\]\\s*(?P&lt;name&gt;.*?)\\s*&lt;(?P&lt;email&gt;.*?)&gt;$/&#039;|
|`GPG_USAGE_SIGN`|public| |&#039;S&#039;|
|`GPG_USAGE_ENCRYPT`|public| |&#039;E&#039;|
|`GPG_USAGE_AUTH`|public| |&#039;A&#039;|
|`GPG_USAGE_CERT`|public| |&#039;C&#039;|
|`GPG_TRUST_LEVEL_UNKNOWN`|public| |&#039;unknown&#039;|
|`GPG_TRUST_LEVEL_NEVER`|public| |&#039;never&#039;|
|`GPG_TRUST_LEVEL_MARGINAL`|public| |&#039;marginal&#039;|
|`GPG_TRUST_LEVEL_FULLY`|public| |&#039;fully&#039;|
|`GPG_TRUST_LEVEL_ULTIMATE`|public| |&#039;ultimate&#039;|

## Methods


### isAbleTo

Check if the key is able to perform a specific usage.

```php
public isAbleTo(string $usage): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$usage` | **string** | The usage to check. |


**Return Value:**

True if the key is able to perform the usage, false otherwise.




***

### isAbleToSign

Check if the key is able to sign.

```php
public isAbleToSign(): bool
```









**Return Value:**

True if the key is able to sign, false otherwise.




***

### __toString

Get the string representation of the key.

```php
public __toString(): string
```









**Return Value:**

The string representation of the key.




***

### fromArray

Create a new instance of the key from an array of data.

```php
public static fromArray(array $data): static
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** | The data to create the key from. |


**Return Value:**

The new instance of the key.




***

### fromString

Create a new instance of the key from a string.

```php
public static fromString(string $string): static
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | The string to create the key from. |


**Return Value:**

The new instance of the key.




***

### addSubKey

Add a subkey to the key.

```php
public addSubKey(\Ninja\Cosmic\Crypt\KeyInterface $key): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **\Ninja\Cosmic\Crypt\KeyInterface** | The subkey to add. |





***


***
> Automatically generated on 2023-12-21
