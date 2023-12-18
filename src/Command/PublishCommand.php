<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Application\Publisher\Asset\Asset;
use Ninja\Cosmic\Application\Publisher\GithubClientPublisher;
use Ninja\Cosmic\Application\Publisher\Release\Release;
use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Argument;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Terminal\Input\Question;
use Ninja\Cosmic\Terminal\Spinner\SpinnerFactory;
use Ninja\Cosmic\Terminal\Terminal;

use function Cosmic\is_git;

#[Icon("🚀")]
#[Name("publish")]
#[Description("Publish a new release for the current application.")]
#[Signature("publish [tag] [--name=] [--description=] [--prerelease] [--draft]")]
#[Argument("tag", description: "The tag to publish. It will create a new tag if it does not exist.")]
#[Option("--name", description: "The name of the release. Cosmic will generate one by default if omitted.")]
#[Option("--description", description: "The brief description of the release.")]
#[Option("--prerelease", description: "Mark the release a pre-release release. (<cyan>Not ready for production</cyan>)")]
#[Option("--draft", description: "Mark the release a draft release. (<cyan>Not ready for production</cyan>)")]
#[Alias("app:publish")]
final class PublishCommand extends CosmicCommand
{
    public function __invoke(string $tag, ?string $name, ?string $description, bool $prerelease, bool $draft): int
    {
        if (!is_git()) {
            Terminal::output()->writeln("<error>Git</error> is not initialized in this project.");
            return $this->failure();
        }

        $releaseName = $name ?? sprintf("%s %s", Env::get("APP_NAME"), $tag);
        $release     = new Release(
            name: $releaseName,
            tagName: $tag,
            description: $description,
            isDraft: $draft,
            isPrerelease: $prerelease,
        );
        $release->addAsset(new Asset(
            name: sprintf("%s %s", Env::get("APP_NAME"), $tag),
            path: Env::basePath(sprintf("builds/%s.phar", Env::get("APP_NAME")))
        ));

        $this->displayRelease($release);

        if (Question::confirm(message: "Do you want to publish the release?")) {
            $this->executionResult = SpinnerFactory::for(
                callable:  static function () use ($release): bool {
                    return (new GithubClientPublisher())->publish($release) !== null;

                },
                message: sprintf("Publishing <info>GitHub</info> release <comment>%s</comment>", $releaseName)
            );

            return $this->exit();
        }

        return $this->success();

    }

    private function displayRelease(Release $release): void
    {
        Terminal::output()->writeln("");
        Terminal::output()->writeln(sprintf("  The following release will be published to GitHub: <cyan>%s</cyan>", $release->name));
        Terminal::output()->writeln("");

        $release->render(Terminal::output());

    }
}
