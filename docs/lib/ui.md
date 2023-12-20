# UI Facade Documentation

The `UI` class is a facade that provides a simple interface to complex subsystems in a system. It provides a set of methods that delegate the work to other classes and methods. This class is part of the `Ninja\Cosmic\Terminal\UI` namespace.

## `header(string $message, string $backgroundColor = self::DEFAULT_HEADER_BACKGROUND_COLOR, int $width = self::DEFAULT_OUTPUT_WIDTH): void`

This method creates a header with a given message, background color, and width. It delegates the work to the `Header` class.

**Parameters:**
- `$message`: The message to be displayed in the header.
- `$backgroundColor`: The background color of the header. Defaults to `self::DEFAULT_HEADER_BACKGROUND_COLOR`.
- `$width`: The width of the header. Defaults to `self::DEFAULT_OUTPUT_WIDTH`.

**Example:**
```php
UI::header('Welcome to the Application', 'blue', 100);
```

## `list(array $items, string $itemColor = self::DEFAULT_LIST_ITEM_COLOR, string $type = self::DEFAULT_LIST_TYPE): void`

This method creates a list with given items, item color, and type. It delegates the work to either the `UnorderedList` or `OrderedList` class based on the `$type` parameter.

**Parameters:**
- `$items`: The items to be displayed in the list.
- `$itemColor`: The color of the items. Defaults to `self::DEFAULT_LIST_ITEM_COLOR`.
- `$type`: The type of the list. Defaults to `self::DEFAULT_LIST_TYPE`.

**Example:**
```php
UI::list(['Item 1', 'Item 2', 'Item 3'], 'green', OrderedList::TYPE);
```

## `table(array $header, array $data): void`

This method creates a table with a given header and data. It delegates the work to the `Table` class.

**Parameters:**
- `$header`: The header of the table.
- `$data`: The data to be displayed in the table.

**Example:**
```php
UI::table(['Name', 'Age'], [['John Doe', 30], ['Jane Doe', 25]]);
```

## `summary(array $data, int $width = self::DEFAULT_OUTPUT_WIDTH, ?string $title = null): void`

This method creates a summary with given data, width, and title. It delegates the work to the `Summary` class.

**Parameters:**
- `$data`: The data to be displayed in the summary.
- `$width`: The width of the summary. Defaults to `self::DEFAULT_OUTPUT_WIDTH`.
- `$title`: The title of the summary.

**Example:**
```php
UI::summary(['Total Users' => 100, 'Active Users' => 80], 100, 'User Statistics');
```

## `p(string $message, int $width = self::DEFAULT_OUTPUT_WIDTH): void`

This method creates a paragraph with a given message and width. It delegates the work to the `Paragraph` class.

**Parameters:**
- `$message`: The message to be displayed in the paragraph.
- `$width`: The width of the paragraph. Defaults to `self::DEFAULT_OUTPUT_WIDTH`.

**Example:**
```php
UI::p('This is a sample paragraph.', 80);
```

## `rule(int $width = self::DEFAULT_OUTPUT_WIDTH, string $color = self::DEFAULT_RULE_COLOR): void`

This method creates a rule with a given width and color. It delegates the work to the `Rule` class.

**Parameters:**
- `$width`: The width of the rule. Defaults to `self::DEFAULT_OUTPUT_WIDTH`.
- `$color`: The color of the rule. Defaults to `self::DEFAULT_RULE_COLOR`.

**Example:**
```php
UI::rule(100, 'red');
```

## `title(string $message, ?string $subtitle = null, int $width = self::DEFAULT_OUTPUT_WIDTH): void`

This method creates a title with a given message, subtitle, and width. It delegates the work to the `Title` class.

**Parameters:**
- `$message`: The message to be displayed in the title.
- `$subtitle`: The subtitle of the title.
- `$width`: The width of the title. Defaults to `self::DEFAULT_OUTPUT_WIDTH`.

**Example:**
```php
UI::title('Welcome', 'This is a subtitle', 100);
```
