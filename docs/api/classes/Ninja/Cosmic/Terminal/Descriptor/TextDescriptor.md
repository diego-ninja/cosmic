***

# TextDescriptor





* Full name: `\Ninja\Cosmic\Terminal\Descriptor\TextDescriptor`
* Parent class: [`\Ninja\Cosmic\Terminal\Descriptor\AbstractDescriptor`](./AbstractDescriptor.md)



## Properties


### help_renderer



```php
private \Ninja\Cosmic\Terminal\Renderer\CommandHelpRenderer $help_renderer
```






***

## Methods


### __construct



```php
public __construct(\Ninja\Cosmic\Terminal\Renderer\CommandHelpRenderer $help_renderer): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$help_renderer` | **\Ninja\Cosmic\Terminal\Renderer\CommandHelpRenderer** |  |





***

### describeInputArgument

Describes an InputArgument instance.

```php
protected describeInputArgument(\Symfony\Component\Console\Input\InputArgument $argument, array $options = []): void
```








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








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$application` | **\Symfony\Component\Console\Application** |  |
| `$options` | **array** |  |





***

### writeText



```php
private writeText(string $content, array $options = []): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** |  |
| `$options` | **array** |  |





***

### getCommandAliasesText

Formats command aliases to show them in the command description.

```php
private getCommandAliasesText(\Symfony\Component\Console\Command\Command $command): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |





***

### formatDefaultValue



```php
private formatDefaultValue(mixed $default): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$default` | **mixed** |  |





***

### getColumnWidth



```php
private getColumnWidth((\Symfony\Component\Console\Command\Command|string)[] $commands): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$commands` | **(\Symfony\Component\Console\Command\Command&#124;string)[]** |  |





***

### calculateTotalWidthForOptions



```php
private calculateTotalWidthForOptions(\Symfony\Component\Console\Input\InputOption[] $options): int
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$options` | **\Symfony\Component\Console\Input\InputOption[]** |  |





***


## Inherited methods


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
