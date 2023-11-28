<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Command\Attribute\Decorated;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Icon;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Parser\MarkdownParser;
use Ninja\Cosmic\Terminal\Descriptor\TextDescriptor;
use Ninja\Cosmic\Terminal\Renderer\CommandHelpRenderer;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Helper\DescriptorHelper;

#[Icon("🔮")]
#[Name("help")]
#[Description("Display help for a command")]
#[Signature("help [--raw] [--format=] [command_name]")]
#[Option("--raw", "To output raw command help")]
#[Option("--format", "The output format (txt, xml, json, or md)", "txt")]
#[Option("command_name", "The command name", "help")]
#[Decorated(false)]
final class HelpCommand extends CosmicCommand
{
    private ?Command $command = null;

    public function __invoke(bool $raw, string $format, string $command_name): int
    {
        $this->command ??= $this->getApplication()->find($command_name);

        $helper = new DescriptorHelper();
        $helper->register('txt', new TextDescriptor($this->getCommandHelperRenderer()));
        $helper->describe(Terminal::output(), $this->command, [
            'format' => $format,
            'raw_text' => $raw,
        ]);

        unset($this->command);

        return $this->success();
    }

    private function getCommandHelperRenderer(): CommandHelpRenderer
    {
        $renderer = new CommandHelpRenderer([Env::helpPath(),__DIR__ . "/../../resources/help"], new MarkdownParser());
        $renderer->loadStyles(Terminal::getTheme()->getStyles());

        return $renderer;
    }
}
