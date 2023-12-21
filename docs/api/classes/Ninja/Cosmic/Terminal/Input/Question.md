***

# Question

Class Question

A utility class for handling user input through the terminal.

* Full name: `\Ninja\Cosmic\Terminal\Input\Question`




## Methods


### ask

Ask a question and get the user's input.

```php
public static ask(string $message, string|null $default = null, array $autoComplete = [], bool $decorated = true): string|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The message to display as the question. |
| `$default` | **string&#124;null** | The default value for the input (optional). |
| `$autoComplete` | **array** | An array of values for autocompletion (optional). |
| `$decorated` | **bool** | Whether to use decorated output (default is true). |


**Return Value:**

The user's input or null if no input is provided.




***

### confirm

Confirm a question with a yes or no answer.

```php
public static confirm(string $message, bool $default = true, bool $decorated = true): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The message to display as the confirmation question. |
| `$default` | **bool** | The default answer (true for yes, false for no, default is true). |
| `$decorated` | **bool** | Whether to use decorated output (default is true). |


**Return Value:**

The user's confirmation (true for yes, false for no).




***

### hidden

Get hidden input from the user.

```php
public static hidden(string $message, bool $decorated = true): string|null
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The message to display as the hidden input prompt. |
| `$decorated` | **bool** | Whether to use decorated output (default is true). |


**Return Value:**

The hidden input or null if no input is provided.




***

### select

Present a selection question to the user.

```php
public static select(string $message, array $options, bool $allowMultiple = true, int|null $columns = null, int|null $maxWidth = null): array
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The message to display as the selection question. |
| `$options` | **array** | The available options for selection. |
| `$allowMultiple` | **bool** | Whether to allow multiple selections (default is true). |
| `$columns` | **int&#124;null** | The number of columns to use for displaying options (optional). |
| `$maxWidth` | **int&#124;null** | The maximum width for displaying options (optional). |


**Return Value:**

The selected options.




***

### getAutocompleteOptions

Get autocomplete options with formatting for default value.

```php
private static getAutocompleteOptions(array $autocomplete, string $default): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$autocomplete` | **array** | An array of values for autocompletion. |
| `$default` | **string** | The default value to highlight. |


**Return Value:**

Formatted autocomplete options string.




***


***
> Automatically generated on 2023-12-21
