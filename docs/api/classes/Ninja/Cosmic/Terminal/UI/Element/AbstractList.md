***

# AbstractList





* Full name: `\Ninja\Cosmic\Terminal\UI\Element\AbstractList`
* Parent class: [`\Ninja\Cosmic\Terminal\UI\Element\AbstractElement`](./AbstractElement.md)
* This class is an **Abstract class**


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`TYPE`|public| |\Ninja\Cosmic\Terminal\UI\UI::DEFAULT_LIST_TYPE|


## Methods


### __invoke



```php
public __invoke(array $items, string $itemColor = UI::DEFAULT_LIST_ITEM_COLOR): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$items` | **array** |  |
| `$itemColor` | **string** |  |





***

### getItems



```php
protected getItems(array $items, string $itemColor): array
```




* This method is **abstract**.



**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$items` | **array** |  |
| `$itemColor` | **string** |  |





***


## Inherited methods


### __construct



```php
public __construct(\Symfony\Component\Console\Output\OutputInterface $output): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$output` | **\Symfony\Component\Console\Output\OutputInterface** |  |





***


***
> Automatically generated on 2023-12-21
