# 📝 Question Facade Documentation

The `Question` class is a facade that provides a simple interface to complex subsystems in a system. It provides a set of methods that delegate the work to other classes and methods. This class is part of the `Ninja\Cosmic\Terminal\Input` namespace.

## `ask(string $message, ?string $default = null, array $autoComplete = [], bool $decorated = true): ?string`

This method asks a question with a given message, default answer, autocomplete options, and a flag indicating whether the message should be decorated.

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$default`: The default answer to the question.
- `$autoComplete`: The autocomplete options for the question.
- `$decorated`: A flag indicating whether the message should be decorated.

**Example:**
```php
Question::ask('What is your name?', 'John Doe', ['John Doe', 'Jane Doe'], true);
```

## `confirm(string $message, bool $default = true, bool $decorated = true): bool`

This method asks a confirmation question with a given message, default answer, and a flag indicating whether the message should be decorated.

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$default`: The default answer to the question.
- `$decorated`: A flag indicating whether the message should be decorated.

**Example:**
```php
Question::confirm('Do you want to continue?', true, true);
```

## `hidden(string $message, bool $decorated = true): ?string`

This method asks a question with a hidden answer with a given message and a flag indicating whether the message should be decorated.

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$decorated`: A flag indicating whether the message should be decorated.

**Example:**
```php
Question::hidden('Enter your password:', true);
```

## `select(string $message, array $options, bool $allowMultiple = true, ?int $columns = null, ?int $maxWidth = null): array`

This method asks a selection question with a given message, options, a flag indicating whether multiple selections are allowed, the number of columns, and the maximum width.

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$options`: The options for the question.
- `$allowMultiple`: A flag indicating whether multiple selections are allowed.
- `$columns`: The number of columns.
- `$maxWidth`: The maximum width.

**Example:**
```php
Question::select('Choose your favorite colors:', ['Red', 'Green', 'Blue'], true, 3, 100);
```
