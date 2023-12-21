# 🗓️ Tables

## Overview

The `Ninja\Cosmic\Terminal\Table` namespace provides a set of classes for creating and manipulating tables in the terminal. The main class is `Table`, which represents a table. Other classes in the namespace provide additional functionality, such as defining columns (`TableColumn`), manipulating column data (`TableManipulatorInterface`), and managing collections of columns (`ColumnCollection`).

## Creating a Table

To create a table, you first need to instantiate the `Table` class. The constructor of the `Table` class accepts an instance of `ColumnCollection`, which represents the columns of the table.

```php
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\Column\ColumnCollection;

$columns = new ColumnCollection();
$table = new Table($columns);
```

## Defining Columns

Columns are defined using the `TableColumn` class. The constructor of this class accepts the name of the column, the key (which is used to fetch the data from the row), and an optional color.

```php
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;

$column = new TableColumn('Name', 'name', 'green');
```

You can add the column to the `ColumnCollection` using the `add` method.

```php
$columns->add($column);
```

## Manipulating Column Data

You can manipulate the data of a column using a manipulator. A manipulator is an object that implements the `TableManipulatorInterface`. This interface requires you to implement a `manipulate` method, which accepts a value and returns a manipulated value.

For example, the `DateManipulator` class converts a timestamp into a formatted date string.

```php
use Ninja\Cosmic\Terminal\Table\Manipulator\DateManipulator;

$dateManipulator = new DateManipulator();
```

You can add the manipulator to a column using the `addManipulator` method.

```php
$column->addManipulator($dateManipulator);
```

## Adding Data to the Table

You can add data to the table using the `addRow` method. This method accepts an associative array, where the keys match the keys of the columns.

```php
$table->addRow(['name' => 'John Doe', 'date' => 1633024862]);
```

## Rendering the Table

Finally, you can render the table using the `render` method.

```php
$table->render();
```

## Configuring the table
The appearance of the table headers can be customized by modifying the `TableConfig` object that is passed to the `Table` class. The `TableConfig` object contains various settings that control the appearance of the table, including the header.
At the time of writing, the following settings and their defaults values are available:
```php
        return [
            "charset"        => self::DEFAULT_CHARSET, // the characters used to draw the table
            "item_name"      => self::DEFAULT_ITEM_NAME, // the name of the item in the table, defaults to "row"
            "table_color"    => self::DEFAULT_TABLE_COLOR, // the color of the table border, defaults to "white"
            "header_color"   => self::DEFAULT_HEADER_COLOR, // the color of the header text, defaults to "cyan"
            "show_header"    => self::DEFAULT_SHOW_HEADER, // whether to show the header, defaults to true
            "padding"        => self::DEFAULT_PADDING, // the padding between the terminal border and the table, defaults to 1
            "center_content" => self::DEFAULT_CENTER_CONTENT, // whether to center the content of the table, defaults to false
        ];
```

Here's an example of how you can customize the appearance of the table headers:

```php
use Ninja\Cosmic\Terminal\Table\Table;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Ninja\Cosmic\Terminal\Table\Column\TableColumn;

// Create a new TableConfig object with custom settings
$config = new TableConfig([
    'header_color' => 'blue',  // Set the color of the header to blue
    'table_color' => 'grey',  // Set the color of the table border to white
    'padding' => 2,            // Set the padding between the terminal border and the table to 2
]);

// Create a new Table object with the custom TableConfig
$table = new Table([], [], $config);

// Add columns to the table
$table->addColumn(new TableColumn('Name', 'name'));
$table->addColumn(new TableColumn('Date', 'date'));

// Add data to the table
$table->addRow(['name' => 'John Doe', 'date' => '2023-01-01']);
$table->addRow(['name' => 'Jane Doe', 'date' => '2023-02-01']);

// Render the table
$table->render();
```

In this example, the `header_color` and `table_color` settings in the `TableConfig` object are used to customize the appearance of the table headers. The `header_color` setting controls the color of the header text, and the `table_color` setting controls the table border color.

This will output the table to the terminal, with the data formatted according to the defined columns and manipulators.
