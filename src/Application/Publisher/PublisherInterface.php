<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Application\Publisher;

use Ninja\Cosmic\Application\Publisher\Release\Release;

interface PublisherInterface
{
    public function publish(Release $release): ?Release;
    public function update(Release $release): ?Release;
    public function get(string $tag): ?Release;
    public function delete(string $tag): bool;

}
