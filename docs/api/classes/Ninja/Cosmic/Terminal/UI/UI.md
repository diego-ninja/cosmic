***

# UI

Class UI

A utility class for building and rendering various UI elements in the terminal.

* Full name: `\Ninja\Cosmic\Terminal\UI\UI`


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`DEFAULT_OUTPUT_WIDTH`|public| |80|
|`DEFAULT_HEADER_BACKGROUND_COLOR`|public| |&#039;default&#039;|
|`DEFAULT_RULE_COLOR`|public| |&#039;white&#039;|
|`DEFAULT_LIST_TYPE`|public| |\Ninja\Cosmic\Terminal\UI\Element\UnorderedList::TYPE|
|`DEFAULT_LIST_ITEM_COLOR`|public| |&#039;white&#039;|


## Methods


### header

Display a header in the terminal.

```php
public static header(string $message, string $backgroundColor = self::DEFAULT_HEADER_BACKGROUND_COLOR, int $width = self::DEFAULT_OUTPUT_WIDTH): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The header message. |
| `$backgroundColor` | **string** | The background color of the header (default is &#039;default&#039;). |
| `$width` | **int** | The width of the header (default is 80). |





***

### list

Display a list in the terminal.

```php
public static list(array $items, string $itemColor = self::DEFAULT_LIST_ITEM_COLOR, string $type = self::DEFAULT_LIST_TYPE): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$items` | **array** | The list items. |
| `$itemColor` | **string** | The color of the list items (default is &#039;white&#039;). |
| `$type` | **string** | The type of the list (default is UnorderedList::TYPE). |





***

### table

Display a table in the terminal.

```php
public static table(array $header, array $data): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$header` | **array** | The table header. |
| `$data` | **array** | The table data. |





***

### summary

Display a summary in the terminal.

```php
public static summary(array $data, int $width = self::DEFAULT_OUTPUT_WIDTH, string|null $title = null): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** | The summary data. |
| `$width` | **int** | The width of the summary (default is 80). |
| `$title` | **string&#124;null** | The title of the summary (optional). |





***

### p

Display a paragraph in the terminal.

```php
public static p(string $message, int $width = self::DEFAULT_OUTPUT_WIDTH): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The paragraph message. |
| `$width` | **int** | The width of the paragraph (default is 80). |





***

### rule

Display a rule in the terminal.

```php
public static rule(int $width = self::DEFAULT_OUTPUT_WIDTH, string $color = self::DEFAULT_RULE_COLOR): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$width` | **int** | The width of the rule (default is 80). |
| `$color` | **string** | The color of the rule (default is &#039;white&#039;). |





***

### title

Display a title in the terminal.

```php
public static title(string $message, string|null $subtitle = null, int $width = self::DEFAULT_OUTPUT_WIDTH): void
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | **string** | The title message. |
| `$subtitle` | **string&#124;null** | The subtitle (optional). |
| `$width` | **int** | The width of the title (default is 80). |





***


***
> Automatically generated on 2023-12-21
