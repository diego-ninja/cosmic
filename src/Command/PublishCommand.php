<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Exception;
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
use Ninja\Cosmic\Crypt\KeyRing;
use Ninja\Cosmic\Crypt\SignerInterface;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Exception\MissingInterfaceException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Input\Question;
use Ninja\Cosmic\Terminal\UI\Spinner\SpinnerFactory;

use Symfony\Component\Process\Process;

use function Cosmic\find_binary;
use function Cosmic\is_git;

#[Icon("🚀")]
#[Name("publish")]
#[Description("Publish a new release for the current application.")]
#[Signature("publish [tag] [--name=] [--description=] [-b|--build] [-s|--sign] [--prerelease] [--draft]")]
#[Argument("tag", description: "The tag to publish. It will create a new tag if it does not exist.")]
#[Option("--name", description: "The name of the release. Cosmic will generate one by default if omitted.")]
#[Option("--description", description: "The brief description of the release.")]
#[Option("--prerelease", description: "Mark the release a pre-release release. (<info>Not ready for production</info>)")]
#[Option("--draft", description: "Mark the release a draft release. (<info>Not ready for production</info>)")]
#[Option("--build", description: "Build the binary before publishing it.")]
#[Option("--sign", description: "Sign the binary before publishing it. A key for the author email must exist in the keychain.")]
#[Alias("app:publish")]
final class PublishCommand extends CosmicCommand
{
    /**
     * @throws UnexpectedValueException
     * @throws MissingInterfaceException
     * @throws BinaryNotFoundException
     * @throws Exception
     */
    public function __invoke(?string $tag, ?string $name, ?string $description, bool $prerelease, bool $draft, bool $build, bool $sign): int
    {
        if (!is_git()) {
            Terminal::output()->writeln("<error>Git</error> is not initialized in this project.");
            return $this->failure();
        }

        $tag ??= Env::get("APP_VERSION");

        Terminal::output()->writeln("");

        if ($build) {
            $this->build();
        }

        if ($sign) {
            $this->sign(Env::basePath(sprintf("builds/%s.phar", Env::get("APP_NAME"))));
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
                callable:  static fn(): bool => (new GithubClientPublisher())->publish($release) instanceof Release,
                message: sprintf("Publishing <info>GitHub</info> release <comment>%s</comment>", $releaseName)
            );

            return $this->exit();
        }

        return $this->success();

    }

    /**
     * @throws BinaryNotFoundException
     */
    private function build(): bool
    {
        $command = sprintf(
            "%s %s %s",
            find_binary("php"),
            $_SERVER['argv'][0],
            "build --env " . Env::env()
        );

        return SpinnerFactory::for(
            callable: (Process::fromShellCommandline($command))->setWorkingDirectory(Env::basePath()),
            message: sprintf(
                'Building <info>%s</info> (<info>%s</info>) binary',
                Env::appName(),
                Env::appVersion()
            )
        );
    }

    /**
     * @throws Exception
     */
    private function sign(string $binary): bool
    {
        $keyring = KeyRing::public();

        $key = Env::get("APP_SIGNING_KEY") ?
            $keyring->all()->getById(Env::get("APP_SIGNING_KEY")) :
            $keyring->all()->getByEmail(Env::get("APP_AUTHOR_EMAIL"));

        if (!$key) {
            Terminal::output()->writeln("<error>Signing key</error> not found in the keychain.");
            return false;
        }

        $key = is_array($key) ? $key[0] : $key;

        return SpinnerFactory::for(
            callable: static function () use ($key, $binary): bool {
                if ($key instanceof SignerInterface) {
                    return $key->sign($binary);
                }

                return false;
            },
            message: sprintf("Signing binary <info>%s</info> with key 🔑 <comment>%s</comment>", basename($binary), $key->id),
        );

    }

    /**
     * @throws UnexpectedValueException
     * @throws MissingInterfaceException
     */
    private function displayRelease(Release $release): void
    {
        Terminal::output()->writeln("");
        Terminal::output()->writeln(sprintf("  The following release will be published to GitHub: <info>%s</info>", $release->name));
        Terminal::output()->writeln("");

        $release->render(Terminal::output());

    }
}
