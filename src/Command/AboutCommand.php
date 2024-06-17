<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Decorated;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Exception\BinaryNotFoundException;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\UI\Table\Manipulator\BoolManipulator;
use Ninja\Cosmic\Terminal\UI\Table\Table;
use Ninja\Cosmic\Terminal\UI\Table\TableConfig;

use function Termwind\render;

#[Icon("❤️")]
#[Name('about')]
#[Signature('about')]
#[Description('Show information about <info>{env.app_name}</info> application')]
#[Decorated(false)]
#[Alias("info")]
final class AboutCommand extends CosmicCommand
{
    /**
     * @throws BinaryNotFoundException
     */
    public function __invoke(): int
    {

        $this->displayLogo();
        render(
            sprintf(
                "
                    <div class='w-50 justify-center'>
                        <span class='mr-1'>%s</span><span class='mr-1'>%s</span><span class='text-info font-bold'>%s</span>
                    </div>
                    ",
                Terminal::getTheme()?->getAppIcon(),
                Env::get("APP_NAME"),
                Env::appVersion()
            )
        );
        render(
            sprintf(
                "<div class='w-52 justify-center'><span> - %s - </span></div>",
                Env::get("APP_DESCRIPTION")
            )
        );

        render(
            sprintf(
                "
                    <div class='w-50 justify-center'>
                        <span>by <span class='text-notice mr-1'>%s</span>
                        <span>[<a class='link' href='%s'>%s</a>]</span></span>
                    </div>
                    ",
                Env::get("APP_AUTHOR"),
                Env::get("APP_URL"),
                Env::get("APP_AUTHOR_EMAIL")
            )
        );

        if (Env::isDebug()) {
            render(
                sprintf(
                    "<div class='w-50 justify-center'><span>Theme: <span class='text-notice mr-1'>%s</span></span></div>",
                    Terminal::getTheme()?->getName()
                )
            );

            $this->renderEnvironmentVariables();
        }

        return $this->success();
    }

    private function renderEnvironmentVariables(): void
    {
        $tableConfig = new TableConfig(Terminal::getTheme()?->getConfig("table"));

        $table = (new Table(data: Env::dump(), columns: [], config: $tableConfig))
            ->addColumn(new TableColumn(name: 'ENV VAR', key: 'key', color: 'cyan'))
            ->addColumn((new TableColumn(name: 'VALUE', key: 'value'))->addManipulator(new BoolManipulator()));

        Terminal::output()->writeln("");

        $table->display(Terminal::output());
    }

    private function displayLogo(): void
    {
        $logo = Terminal::getTheme()?->getLogo();

        if ($logo === null || $logo === '' || $logo === '0') {
            return;
        }

        Terminal::output()->writeln("");
        Terminal::output()->write(Terminal::getTheme()?->getLogo() ?? "");
        Terminal::output()->writeln("\n");
    }
}
