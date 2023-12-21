***

# AbstractDescriptor





* Full name: `\Ninja\Cosmic\Terminal\Descriptor\AbstractDescriptor`
* This class implements:
[`\Symfony\Component\Console\Descriptor\DescriptorInterface`](../../../../Symfony/Component/Console/Descriptor/DescriptorInterface.md)
* This class is an **Abstract class**



## Properties


### output



```php
protected \Symfony\Component\Console\Output\OutputInterface $output
```






***

## Methods


### describe



```php
public describe(\Symfony\Component\Console\Output\OutputInterface $output, object $object, array $options = []): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |
| `$object` | **object** |  |
| `$options` | **array** |  |





***

### write



```php
protected write(string $content, bool $decorated = false): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** |  |
| `$decorated` | **bool** |  |





***

### describeInputArgument

Describes an InputArgument instance.

```php
protected describeInputArgument(\Symfony\Component\Console\Input\InputArgument $argument, array $options = []): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$argument` | **\Symfony\Component\Console\Input\InputArgument** |  |
| `$options` | **array** |  |





***

### describeInputOption

Describes an InputOption instance.

```php
protected describeInputOption(\Symfony\Component\Console\Input\InputOption $option, array $options = []): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$option` | **\Symfony\Component\Console\Input\InputOption** |  |
| `$options` | **array** |  |





***

### describeInputDefinition

Describes an InputDefinition instance.

```php
protected describeInputDefinition(\Symfony\Component\Console\Input\InputDefinition $definition, array $options = []): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$definition` | **\Symfony\Component\Console\Input\InputDefinition** |  |
| `$options` | **array** |  |





***

### describeCommand

Describes a Command instance.

```php
protected describeCommand(\Symfony\Component\Console\Command\Command $command, array $options = []): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |
| `$options` | **array** |  |





***

### describeApplication

Describes an Application instance.

```php
protected describeApplication(\Symfony\Component\Console\Application $application, array $options = []): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$application` | **\Symfony\Component\Console\Application** |  |
| `$options` | **array** |  |





***


***
> Automatically generated on 2023-12-21
