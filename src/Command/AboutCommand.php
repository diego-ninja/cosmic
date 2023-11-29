<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use jc21\CliTable;
use Ninja\Cosmic\Command\Attribute\Alias;
use Ninja\Cosmic\Command\Attribute\Decorated;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Terminal\Terminal;

use function Termwind\render;

#[Icon("❤️ ")]
#[Name('about')]
#[Signature('about')]
#[Description('Show information about <info>{env.app_name}</info> application')]
#[Decorated(false)]
#[Alias("info")]
final class AboutCommand extends CosmicCommand
{
    public function __invoke(): int
    {

        $this->displayLogo();
        render(
            sprintf(
                "
                    <div class='w-50 justify-center'>
                        <span class='mr-1'>%s</span><span class='text-info font-bold'>%s</span>
                    </div>
                    ",
                Env::get("APP_NAME"),
                Env::appVersion()
            )
        );
        render(
            sprintf(
                "<div class='w-60 justify-center'><span> - %s - </span></div>",
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
                Env::get("APP_AUTHOR_URL"),
                Env::get("APP_AUTHOR_EMAIL")
            )
        );

        if (Env::get("APP_LICENSE")) {
            render(
                sprintf(
                    "<div class='w-50 justify-center'><span>License: <span class='text-notice mr-1'>%s</span></span></div>",
                    Env::get("APP_LICENSE")
                )
            );
        }

        if (Env::isDebug()) {
            $this->renderEnvironmentVariables();
        }

        return $this->success();
    }

    private function renderEnvironmentVariables(): void
    {
        $data  = Env::dump();
        $table = new CliTable();
        $table->setTableColor('white');
        $table->setHeaderColor('yellow');
        $table->addField(fieldName: 'Variable', fieldKey: 'key', color: 'green');
        $table->addField(fieldName: 'Value', fieldKey: 'value');
        $table->injectData($data);
        $rendered = $table->get();

        Terminal::footer()->writeln("");
        Terminal::footer()->writeln($rendered);
    }

    private function displayLogo(): void
    {
        $logo = Terminal::getTheme()->getLogo();

        if (empty($logo)) {
            return;
        }

        Terminal::output()->writeln("");
        Terminal::output()->write(Terminal::getTheme()->getLogo());
        Terminal::output()->writeln("\n");
    }
}
