***

# InstallerInterface

Interface InstallerInterface

Represents an installer interface for managing package installations.

* Full name: `\Ninja\Cosmic\Installer\InstallerInterface`



## Methods


### install

Performs the installation process.

```php
public install(): bool
```









**Return Value:**

True if the installation was successful, false otherwise.




***

### isPackageInstalled

Checks if a package is installed.

```php
public isPackageInstalled(string $package): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$package` | **string** | The name of the package. |


**Return Value:**

True if the package is installed, false otherwise.




***

### preInstall

Executes a callback before the installation process.

```php
public preInstall(\Closure|null $callback = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **\Closure&#124;null** | The callback function to execute before installation. |





***

### postInstall

Executes a callback after the installation process.

```php
public postInstall(\Closure|null $callback = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **\Closure&#124;null** | The callback function to execute after installation. |





***

### addPackage

Adds a package to the installer.

```php
public addPackage(string $package, string|null $version = &quot;*&quot;): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$package` | **string** | The name of the package. |
| `$version` | **string&#124;null** | The version constraint for the package (default is &quot;*&quot;). |





***


***
> Automatically generated on 2023-12-21
