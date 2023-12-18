<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\GithubClientPublisher;
use Ninja\Cosmic\Application\Publisher\Release\Release;
use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\Terminal;

#[Icon("🚀")]
#[Name("publish")]
#[Description("Publish a new release for the current the application.")]
#[Signature("publish [--tag=] [--prerelease] [--draft]")]
#[Option("--tag", description: "The tag to publish. It will create a new tag if it does not exist.")]
#[Option("--prerelease", description: "Mark the release a pre-release release. (<cyan>Not ready for production</cyan>)")]
#[Option("--draft", description: "Mark the release a draft release. (<cyan>Not ready for production</cyan>)")]
#[Alias("app:publish")]
final class PublishCommand extends CosmicCommand
{
    public function __invoke(?string $tag, bool $prerelease, bool $draft): int
    {
        $releaseName = sprintf("%s %s", Env::get("APP_NAME"), $tag);
        $release     = new Release(
            name: $releaseName,
            tagName: $tag,
            isDraft: $draft,
            isPrerelease: $prerelease,
        );
        $release->addAsset(new Asset(
            name: sprintf("%s %s", Env::get("APP_NAME"), $tag),
            path: Env::basePath(sprintf("builds/%s.phar", Env::get("APP_NAME")))
        ));

        $release->render(Terminal::output());

        $this->executionResult = SpinnerFactory::for(
            callable:  static function () use ($release): bool {
                return (new GithubClientPublisher())->publish($release) !== null;

            },
            message: sprintf("Publishing <info>GitHub</info> release <comment>%s</comment>", $releaseName)
        );

        return $this->exit();
    }
}
