***

# AbstractStyle





* Full name: `\Ninja\Cosmic\Terminal\Theme\Element\Style\AbstractStyle`
* Parent class: [`\Ninja\Cosmic\Terminal\Theme\Element\AbstractThemeElement`](../AbstractThemeElement.md)
* This class is an **Abstract class**


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`TERMWIND_STYLE`|public| |&quot;termwind&quot;|
|`SYMFONY_STYLE`|public| |&quot;symfony&quot;|


## Methods


### fromArray



```php
public static fromArray(array $input): self
```



* This method is **static**.
* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$input` | **array** |  |





***

### load



```php
public load(\Symfony\Component\Console\Output\OutputInterface $output): void
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





***


## Inherited methods


### toJson



```php
public toJson(): string
```











**Throws:**

- [`JsonException`](../../../../../../JsonException.md)



***

### toArray



```php
public toArray(): ?array
```












***

### jsonSerialize



```php
public jsonSerialize(): ?array
```












***


***
> Automatically generated on 2023-12-21
