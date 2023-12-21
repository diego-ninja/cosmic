***

# EnvironmentBuilder

Class EnvironmentBuilder

Utility class for building environment files based on an example file.

* Full name: `\Ninja\Cosmic\Environment\EnvironmentBuilder`




## Methods


### build

Build the environment file based on the provided directory.

```php
public static build(string $directory): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$directory` | **string** | The directory containing the example environment file. |


**Return Value:**

True on success, false otherwise.



**Throws:**
<p>If the example environment file is not found.</p>

- [`EnvironmentNotFoundException`](./Exception/EnvironmentNotFoundException.md)



***

### buildFrom

Build environment files based on the provided example file.

```php
public buildFrom(string $example_file): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$example_file` | **string** | The path to the example environment file. |


**Return Value:**

True on success, false otherwise.




***

### extractVariables

Extract environment variables from the provided example file.

```php
private extractVariables(string $example_file, bool $get_values = false): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$example_file` | **string** | The path to the example environment file. |
| `$get_values` | **bool** | Whether to retrieve values along with keys. |


**Return Value:**

The list of environment variables.




***

### buildEnvFile

Build an environment file based on the provided example file.

```php
private buildEnvFile(string $example_file, string $env_file): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$example_file` | **string** | The path to the example environment file. |
| `$env_file` | **string** | The path to the target environment file. |


**Return Value:**

True on success, false otherwise.




***


***
> Automatically generated on 2023-12-21
