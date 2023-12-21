***

# GithubClientPublisher

Class GithubClientPublisher

Implementation of the PublisherInterface that uses the GitHub CLI to publish releases and assets.

* Full name: `\Ninja\Cosmic\Application\Publisher\GithubClientPublisher`
* This class implements:
[`\Ninja\Cosmic\Application\Publisher\PublisherInterface`](./PublisherInterface.md)




## Methods


### publish

Publish a release on GitHub.

```php
public publish(\Ninja\Cosmic\Application\Publisher\Release\Release $release): \Ninja\Cosmic\Application\Publisher\Release\Release|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$release` | **\Ninja\Cosmic\Application\Publisher\Release\Release** | The release to publish. |


**Return Value:**

The published release, or null if the operation fails.



**Throws:**

- [`BinaryNotFoundException`](../../Exception/BinaryNotFoundException.md)

- [`JsonException`](../../../../JsonException.md)



***

### get

Get information about a release on GitHub.

```php
public get(string $tag): \Ninja\Cosmic\Application\Publisher\Release\Release|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tag` | **string** | The tag of the release. |


**Return Value:**

The release information, or null if the operation fails.



**Throws:**
<p>If the GitHub CLI binary is not found.</p>

- [`JsonException`](../../../../JsonException.md)



***

### update

Update an existing release on GitHub.

```php
public update(\Ninja\Cosmic\Application\Publisher\Release\Release $release): \Ninja\Cosmic\Application\Publisher\Release\Release|null
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$release` | **\Ninja\Cosmic\Application\Publisher\Release\Release** | The release to update. |


**Return Value:**

The updated release, or null if the operation fails.



**Throws:**

- [`JsonException`](../../../../JsonException.md)



***

### delete

Delete a release on GitHub.

```php
public delete(string $tag): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tag` | **string** | The tag of the release to delete. |


**Return Value:**

True if the deletion is successful, false otherwise.



**Throws:**

- [`JsonException`](../../../../JsonException.md)



***

### uploadAsset

Upload an asset to a GitHub release.

```php
private uploadAsset(string $name, \Ninja\Cosmic\Application\Publisher\Asset\Asset $asset, bool $overwrite): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** | The name of the release. |
| `$asset` | **\Ninja\Cosmic\Application\Publisher\Asset\Asset** | The asset to upload. |
| `$overwrite` | **bool** | Whether to overwrite the asset if it already exists. |


**Return Value:**

True if the upload is successful, false otherwise.




***


***
> Automatically generated on 2023-12-21
