<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher;

use Ninja\Cosmic\Application\Publisher\Release\Release;

/**
 * Interface PublisherInterface
 *
 * @package Ninja\Cosmic\Application\Publisher
 */
interface PublisherInterface
{
    /**
     * Publish a release.
     *
     * @param Release $release The release to be published.
     *
     * @return Release|null The published release, or null if the operation fails.
     */
    public function publish(Release $release): ?Release;

    /**
     * Update a published release.
     *
     * @param Release $release The release to be updated.
     *
     * @return Release|null The updated release, or null if the operation fails.
     */
    public function update(Release $release): ?Release;

    /**
     * Get information about a specific release.
     *
     * @param string $tag The tag of the release.
     *
     * @return Release|null The release information, or null if the release is not found.
     */
    public function get(string $tag): ?Release;

    /**
     * Delete a release.
     *
     * @param string $tag The tag of the release to be deleted.
     *
     * @return bool True if the release is successfully deleted, false otherwise.
     */
    public function delete(string $tag): bool;
}
