***

# TableColumn





* Full name: `\Ninja\Cosmic\Terminal\Table\Column\TableColumn`
* This class implements:
[`\Ninja\Cosmic\Terminal\Table\Column\TableColumnInterface`](./TableColumnInterface.md)



## Properties


### manipulators



```php
private \Ninja\Cosmic\Terminal\Table\Manipulator\ManipulatorCollection $manipulators
```






***

### name



```php
public string $name
```






***

### key



```php
public string $key
```






***

### color



```php
public ?string $color
```






***

## Methods


### __construct



```php
public __construct(string $name, string $key, ?string $color = TableConfig::DEFAULT_FIELD_COLOR): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$key` | **string** |  |
| `$color` | **?string** |  |





***

### create



```php
public static create(string $name, string $key, ?string $color): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$key` | **string** |  |
| `$color` | **?string** |  |





***

### getName



```php
public getName(): string
```












***

### getKey



```php
public getKey(): string
```












***

### getColor



```php
public getColor(): ?string
```












***

### getManipulators



```php
public getManipulators(): \Ninja\Cosmic\Terminal\Table\Manipulator\ManipulatorCollection
```












***

### addManipulator



```php
public addManipulator(\Ninja\Cosmic\Terminal\Table\Manipulator\TableManipulatorInterface $manipulator): self
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$manipulator` | **\Ninja\Cosmic\Terminal\Table\Manipulator\TableManipulatorInterface** |  |





***


***
> Automatically generated on 2023-12-21
