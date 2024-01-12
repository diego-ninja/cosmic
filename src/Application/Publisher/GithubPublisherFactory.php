<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher;

use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Exception\CosmicException;

use function Cosmic\find_binary;

class GithubPublisherFactory
{
    /**
     * @throws CosmicException
     * @throws BinaryNotFoundException
     */
    public static function create(): ?PublisherInterface
    {
        $publishMethod = Env::get('GITHUB_PUBLISH_METHOD', PublisherInterface::PUBLISH_METHOD_API);

        if ($publishMethod === PublisherInterface::PUBLISH_METHOD_API) {
            $token = Env::get('GITHUB_TOKEN') or throw new CosmicException(message: "No GitHub token found.");
            return new GithubApiPublisher($token);
        }

        if ($publishMethod === PublisherInterface::PUBLISH_METHOD_CLIENT) {
            try {
                find_binary('gh');
                return new GithubClientPublisher();
            } catch (BinaryNotFoundException $e) {
                throw new CosmicException(message: "Could not find a suitable GitHub publisher.", previous: $e);
            }
        }

        return null;
    }
}
