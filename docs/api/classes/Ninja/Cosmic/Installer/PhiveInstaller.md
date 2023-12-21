***

# PhiveInstaller

Class PhiveInstaller

An installer for PHP tools using Phive.

* Full name: `\Ninja\Cosmic\Installer\PhiveInstaller`
* Parent class: [`\Ninja\Cosmic\Installer\AbstractInstaller`](./AbstractInstaller.md)


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`PHIVE_INSTALLATION_PATH`|public| |&quot;/usr/local/bin/phive&quot;|

## Properties


### allowed_keys



```php
private static array $allowed_keys
```



* This property is **static**.


***

## Methods


### __construct

PhiveInstaller constructor.

```php
public __construct(\Symfony\Component\Console\Output\OutputInterface $output): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** | The output interface. |





***

### install

Installs the specified packages using Phive.

```php
public install(): bool
```









**Return Value:**

True if the installation is successful, false otherwise.



**Throws:**

- [`BinaryNotFoundException`](../Exception/BinaryNotFoundException.md)



***

### installPackages

Installs the specified packages using Phive.

```php
public installPackages(): bool
```









**Return Value:**

True if the installation is successful, false otherwise.



**Throws:**

- [`BinaryNotFoundException`](../Exception/BinaryNotFoundException.md)



***

### isPackageInstalled

Checks if a package is installed using Phive.

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

### installPhive

Installs Phive.

```php
public installPhive(): bool
```









**Return Value:**

True if the installation is successful, false otherwise.



**Throws:**

- [`Exception`](../../../Exception.md)



***

### downloadPhive

Downloads Phive binary.

```php
public downloadPhive(): bool
```









**Return Value:**

True if the download is successful, false otherwise.



**Throws:**

- [`Exception`](../../../Exception.md)



***

### addPhiveGpgKey

Adds Phive GPG key.

```php
public addPhiveGpgKey(): bool
```









**Return Value:**

True if the key is added successfully, false otherwise.



**Throws:**

- [`Exception`](../../../Exception.md)



***

### verifyPhive

Verifies Phive binary.

```php
private verifyPhive(): bool
```









**Return Value:**

True if the verification is successful, false otherwise.



**Throws:**

- [`RuntimeException`](../../../RuntimeException.md)



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
