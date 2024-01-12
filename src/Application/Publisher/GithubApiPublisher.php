<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher;

use Github\Api\Repo;
use Github\AuthMethod;
use Github\Client;
use Github\Exception\ErrorException;
use Github\Exception\MissingArgumentException;
use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\Asset\Signature;
use Ninja\Cosmic\Application\Publisher\Release\Release;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Exception\CosmicException;

use function Cosmic\git_repo;

class GithubApiPublisher implements PublisherInterface
{
    private Client $client;

    private string $username;

    private string $repo;

    private Repo $api;
    /**
     * @throws CosmicException
     * @throws BinaryNotFoundException
     */
    public function __construct(private readonly string $token)
    {
        $this->client = new Client();
        $this->client->authenticate(tokenOrLogin: $this->token, authMethod: AuthMethod::ACCESS_TOKEN);

        /** @var Repo $api */
        $api       = $this->client->api('repo');
        $this->api = $api;

        $repo                          = git_repo() or throw new CosmicException("Could not determine the GitHub repository from the git remote.");
        [$this->username, $this->repo] = explode("/", $repo);
    }

    /**
     * @throws MissingArgumentException
     * @throws ErrorException
     */
    public function publish(Release $release): ?Release
    {
        $response = $this->api
            ->releases()->create($this->username, $this->repo, [
                'tag_name'   => $release->tagName,
                'name'       => $release->name,
                'body'       => $release->description ?: '',
                'draft'      => $release->isDraft,
                'prerelease' => $release->isPrerelease,
            ]);

        $releaseId = (Release::fromApiResponse($response))->getId();
        foreach ($release->getAssets() as $asset) {
            if ($releaseId && $asset->isVerified()) {
                $this->uploadAsset($releaseId, $asset);
                if ($asset->getSignature() !== null) {
                    $this->uploadAsset($releaseId, $asset->getSignature());
                }
            }
        }

        return $releaseId ? $this->getReleaseById($releaseId) : null;

    }

    /**
     * @throws ErrorException
     * @throws MissingArgumentException
     */
    public function update(Release $release): ?Release
    {
        $releaseId = $release->getId();

        if ($releaseId === null) {
            return null;
        }

        $release = $this->getReleaseById($releaseId);
        if ($release) {
            $this->api
                ->releases()->edit($this->username, $this->repo, $releaseId, [
                    'name'       => $release->name,
                    'body'       => $release->description ?: '',
                    'draft'      => $release->isDraft,
                    'prerelease' => $release->isPrerelease,
                ]);

            foreach ($release->getAssets() as $asset) {
                if ($releaseId && $asset->isVerified()) {
                    $this->uploadAsset($releaseId, $asset);
                    if ($asset->getSignature() !== null) {
                        $this->uploadAsset($releaseId, $asset->getSignature());
                    }
                }
            }

            return $releaseId ? $this->getReleaseById($releaseId) : null;

        }

        return null;
    }

    public function get(string $tag): ?Release
    {
        $releaseData = $this->api->releases()->tag($this->username, $this->repo, $tag);
        return Release::fromApiResponse($releaseData);

    }

    public function delete(string $tag): bool
    {
        $release = $this->get($tag);
        if ($release === null) {
            return false;
        }

        $response = $release->getId() ? $this->api->releases()->remove($this->username, $this->repo, $release->getId()) : null;
        if ($response === null) {
            return false;
        }

        return true;

    }

    /**
     * @throws ErrorException
     * @throws MissingArgumentException
     */
    private function uploadAsset(int $releaseId, Asset|Signature $asset): void
    {
        $contentType = $asset->getContentType();
        $content     = $asset->getContent();

        if ($contentType && $content) {
            $this->api->releases()->assets()->create(
                $this->username,
                $this->repo,
                $releaseId,
                $asset->getName(),
                $contentType,
                $content
            );
        }
    }

    private function getReleaseById(int $id): ?Release
    {
        try {
            $releaseData = $this->api->releases()->show($this->username, $this->repo, $id);
            return Release::fromApiResponse($releaseData);
        } catch (\Exception $e) {
            return null;
        }
    }
}
