***

# CommandHelpRenderer





* Full name: `\Ninja\Cosmic\Terminal\Renderer\CommandHelpRenderer`



## Properties


### default_styles



```php
protected array $default_styles
```






***

### help_directories



```php
private array $help_directories
```






***

### parser



```php
private \Ninja\Cosmic\Parser\MarkdownParser $parser
```






***

## Methods


### __construct



```php
public __construct(array $help_directories, \Ninja\Cosmic\Parser\MarkdownParser $parser): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$help_directories` | **array** |  |
| `$parser` | **\Ninja\Cosmic\Parser\MarkdownParser** |  |





***

### render



```php
public render(\Symfony\Component\Console\Command\Command $command): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |





***

### findCommandHelpFile



```php
private findCommandHelpFile(\Symfony\Component\Console\Command\Command $command): ?string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |





***

### renderCommandHelpFile



```php
private renderCommandHelpFile(\Symfony\Component\Console\Command\Command $command): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |





***

### renderCommandInlineHelp



```php
private renderCommandInlineHelp(\Symfony\Component\Console\Command\Command $command): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |





***

### renderFooterForCommand



```php
private renderFooterForCommand(\Symfony\Component\Console\Command\Command $command): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$command` | **\Symfony\Component\Console\Command\Command** |  |





***

### renderHeader



```php
private renderHeader(): void
```












***

### loadStyles



```php
public loadStyles(array $custom_styles = []): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$custom_styles` | **array** |  |





***


***
> Automatically generated on 2023-12-21
