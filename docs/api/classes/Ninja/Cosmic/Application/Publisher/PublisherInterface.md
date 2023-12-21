***

# PublisherInterface

Interface PublisherInterface



* Full name: `\Ninja\Cosmic\Application\Publisher\PublisherInterface`



## Methods


### publish

Publish a release.

```php
public publish(\Ninja\Cosmic\Application\Publisher\Release\Release $release): \Ninja\Cosmic\Application\Publisher\Release\Release|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$release` | **\Ninja\Cosmic\Application\Publisher\Release\Release** | The release to be published. |


**Return Value:**

The published release, or null if the operation fails.




***

### update

Update a published release.

```php
public update(\Ninja\Cosmic\Application\Publisher\Release\Release $release): \Ninja\Cosmic\Application\Publisher\Release\Release|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$release` | **\Ninja\Cosmic\Application\Publisher\Release\Release** | The release to be updated. |


**Return Value:**

The updated release, or null if the operation fails.




***

### get

Get information about a specific release.

```php
public get(string $tag): \Ninja\Cosmic\Application\Publisher\Release\Release|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tag` | **string** | The tag of the release. |


**Return Value:**

The release information, or null if the release is not found.




***

### delete

Delete a release.

```php
public delete(string $tag): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tag` | **string** | The tag of the release to be deleted. |


**Return Value:**

True if the release is successfully deleted, false otherwise.




***


***
> Automatically generated on 2023-12-21
