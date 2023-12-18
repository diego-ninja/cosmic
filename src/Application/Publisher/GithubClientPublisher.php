<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher;

use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\Release\Release;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Symfony\Component\Process\Process;

use function Cosmic\find_binary;

class GithubClientPublisher implements PublisherInterface
{
    public function publish(Release $release): ?Release
    {
        try {
            $command = sprintf(
                '%s release create %s',
                find_binary("gh"),
                $release->tagName
            );

            if ($release->name) {
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
            throw new \RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                previous: $e
            );
        }

    }

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
            throw new \RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                previous: $e
            );
        }
    }

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
            throw new \RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                previous: $e
            );
        }

    }

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
            throw new \RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                previous: $e
            );
        }
    }

    private function uploadAsset(string $name, Asset $asset, bool $overwrite): bool
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
            throw new \RuntimeException(
                message: "Github CLI binary not found. Please install it and try again.",
                previous: $e
            );
        }
    }
}
