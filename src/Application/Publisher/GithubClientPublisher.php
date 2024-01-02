<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher;

use RuntimeException;
use JsonException;
use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\Release\Release;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

/**
 * Class GithubClientPublisher
 *
 * Implementation of the PublisherInterface that uses the GitHub CLI to publish releases and assets.
 * @package Ninja\Cosmic\Application
 */
class GithubClientPublisher implements PublisherInterface
{
    /**
     * Publish a release on GitHub.
     *
     * @param Release $release The release to publish.
     *
     * @return Release|null The published release, or null if the operation fails.
     *
     * @throws BinaryNotFoundException
     * @throws JsonException
     */
    public function publish(Release $release): ?Release
    {
        try {
            $command = sprintf(
                '%s release create %s',
                find_binary("gh"),
                $release->tagName
            );

            if ($release->name !== '' && $release->name !== '0') {
                $command .= sprintf(' --title "%s"', $release->name);
            }

            if ($release->description) {
                $command .= sprintf(' --notes "%s"', $release->description);
            }

            if ($release->isDraft) {
                $command .= ' --draft';
            }

            if ($release->isPrerelease) {
                $command .= ' --prerelease';
            }

            $process = Process::fromShellCommandline($command);
            if ($process->mustRun()->isSuccessful()) {
                foreach ($release->getAssets() as $asset) {
                    if ($asset->isVerified()) {
                        $this->uploadAsset($release->tagName, $asset, true);
                        $this->uploadAsset($release->tagName, $asset->getSignature(), true);
                    }
                }

                return $this->get($release->tagName);
            }

            return null;

        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                code: $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * Get information about a release on GitHub.
     *
     * @param string $tag The tag of the release.
     *
     * @return Release|null The release information, or null if the operation fails.
     *
     * @throws JsonException If the GitHub CLI binary is not found.
     */
    public function get(string $tag): ?Release
    {
        try {
            $command = sprintf(
                '%s release view %s --json name,tagName,tarballUrl,isDraft,isPrerelease,createdAt,publishedAt,assets',
                find_binary("gh"),
                $tag
            );

            $process = Process::fromShellCommandline($command);
            if ($process->mustRun()->isSuccessful()) {
                return Release::fromJson($process->getOutput());
            }

            return null;

        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                code: $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * Update an existing release on GitHub.
     *
     * @param Release $release The release to update.
     *
     * @return Release|null The updated release, or null if the operation fails.
     *
     * @throws JsonException
     */
    public function update(Release $release): ?Release
    {
        try {
            $command = sprintf(
                '%s release edit %s',
                find_binary("gh"),
                $release->tagName
            );

            $process = Process::fromShellCommandline($command);
            if ($process->mustRun()->isSuccessful()) {
                foreach ($release->getAssets() as $asset) {
                    $this->uploadAsset($release->tagName, $asset, true);
                }

                return $this->get($release->tagName);
            }

            return null;

        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                code: $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * Delete a release on GitHub.
     *
     * @param string $tag The tag of the release to delete.
     *
     * @return bool True if the deletion is successful, false otherwise.
     *
     * @throws JsonException
     */
    public function delete(string $tag): bool
    {
        try {
            $release = $this->get($tag);
            $command = sprintf(
                '%s release delete --yes %s',
                find_binary("gh"),
                $tag
            );

            if (!$release?->isDraft()) {
                $command .= ' --cleanup-tag';
            }

            $process = Process::fromShellCommandline($command);
            return $process->mustRun()->isSuccessful();

        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                code: $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * Upload an asset to a GitHub release.
     *
     * @param string $name      The name of the release.
     * @param mixed  $asset     The asset to upload.
     * @param bool   $overwrite Whether to overwrite the asset if it already exists.
     *
     * @return bool True if the upload is successful, false otherwise.
     *
     */
    private function uploadAsset(string $name, mixed $asset, bool $overwrite): bool
    {
        try {
            $command = sprintf(
                '%s release upload %s %s %s',
                find_binary("gh"),
                $overwrite ? '--clobber' : '',
                $name,
                sprintf("'%s#%s'", $asset->path, $asset->name)
            );

            $process = Process::fromShellCommandline($command);
            return $process->mustRun()->isSuccessful();

        } catch(BinaryNotFoundException $e) {
            throw new RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                code: $e->getCode(),
                previous: $e
            );
        }
    }
}
