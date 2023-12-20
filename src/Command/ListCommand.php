<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Command;

use Ninja\Cosmic\Command\Attribute\Argument;
use Ninja\Cosmic\Command\Attribute\Decorated;
use Ninja\Cosmic\Command\Attribute\Description;
use Ninja\Cosmic\Command\Attribute\Hidden;
use Ninja\Cosmic\Command\Attribute\Name;
use Ninja\Cosmic\Command\Attribute\Option;
use Ninja\Cosmic\Command\Attribute\Signature;
use Ninja\Cosmic\Environment\Env;
use Ninja\Cosmic\Parser\MarkdownParser;
use Ninja\Cosmic\Terminal\Descriptor\TextDescriptor;
use Ninja\Cosmic\Terminal\Renderer\CommandHelpRenderer;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Helper\DescriptorHelper;

#[Name("list")]
#[Description("")]
#[Signature("list [--raw] [--format=] [--short] [namespace]")]
#[Option("--raw", "To output raw command help")]
#[Option("--format", "The output format (txt, xml, json, or md)", "txt")]
#[Option("--short", "To skip describing commands arguments")]
#[Argument("namespace", "The namespace name")]
#[Hidden]
#[Decorated(false)]
final class ListCommand extends CosmicCommand
{
    public function __invoke(bool $raw, string $format, bool $short, ?string $namespace = null): int //phpcs:ignore
    {
        $helper = new DescriptorHelper();
        $helper->register('txt', new TextDescriptor($this->getCommandHelperRenderer()));
        $helper->describe(Terminal::output(), $this->application, [
            'format'    => $format,
            'raw_text'  => $raw,
            'namespace' => $namespace,
            'short'     => $short,
        ]);

        return $this->success();
    }

    private function getCommandHelperRenderer(): CommandHelpRenderer
    {
        // $renderer->loadStyles(Terminal::getTheme()->getStyles()->toArray());

        return new CommandHelpRenderer([Env::helpPath()], new MarkdownParser());
    }

    public function getCommandHelp(): ?string
    {
        return
            <<<'EOF'
        The <info>%command.name%</info> command lists all commands:

          <info>%command.full_name%</info>

        You can also display the commands for a specific namespace:

          <info>%command.full_name% test</info>

        You can also output the information in other formats by using the <comment>--format</comment> option:

          <info>%command.full_name% --format=xml</info>

        It's also possible to get raw list of commands (useful for embedding command runner):

          <info>%command.full_name% --raw</info>
        EOF;
    }
}
