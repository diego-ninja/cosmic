***

# AbstractInstaller

Class AbstractInstaller

An abstract base class implementing the InstallerInterface with common functionality.

* Full name: `\Ninja\Cosmic\Installer\AbstractInstaller`
* This class implements:
[`\Ninja\Cosmic\Installer\InstallerInterface`](./InstallerInterface.md)
* This class is an **Abstract class**



## Properties


### packages

The list of packages to be installed.

```php
protected array&lt;string,string&gt; $packages
```






***

### pre_install

The callback function to execute before installation.

```php
protected \Closure|null $pre_install
```






***

### post_install

The callback function to execute after installation.

```php
protected \Closure|null $post_install
```






***

### is_installed

Indicates whether the installation process is completed.

```php
protected bool $is_installed
```






***

### output



```php
protected \Symfony\Component\Console\Output\OutputInterface $output
```






***

## Methods


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
