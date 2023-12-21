***

# AptInstaller

Class AptInstaller

An installer implementation for Debian-based systems using apt package manager.

* Full name: `\Ninja\Cosmic\Installer\AptInstaller`
* Parent class: [`\Ninja\Cosmic\Installer\AbstractInstaller`](./AbstractInstaller.md)




## Methods


### isPackageInstalled

Checks if a package is installed.

```php
public isPackageInstalled(string $package): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$package` | **string** | The name of the package to check. |


**Return Value:**

True if the package is installed, false otherwise.




***

### install

Installs the specified packages using apt package manager.

```php
public install(): bool
```









**Return Value:**

True if the installation is successful, false otherwise.



**Throws:**

- [`BinaryNotFoundException`](../Exception/BinaryNotFoundException.md)



***

### updateApt

Updates the apt package manager's sources.

```php
protected updateApt(): bool
```









**Return Value:**

True if the update is successful, false otherwise.



**Throws:**

- [`BinaryNotFoundException`](../Exception/BinaryNotFoundException.md)



***


## Inherited methods


### __construct

AbstractInstaller constructor.

```php
public __construct(\Symfony\Component\Console\Output\OutputInterface $output): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** | The output interface. |





***

### preInstall

Sets the callback function to execute before installation.

```php
public preInstall(\Closure|null $callback = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **\Closure&#124;null** | The callback function to execute before installation. |





***

### postInstall

Sets the callback function to execute after installation.

```php
public postInstall(\Closure|null $callback = null): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **\Closure&#124;null** | The callback function to execute after installation. |





***

### isInstalled

Checks if the installation process is completed.

```php
public isInstalled(): bool
```









**Return Value:**

True if the installation is completed, false otherwise.




***

### addPackage

Adds a package to the list of packages to be installed.

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
