<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Renderer;

use Ninja\Cosmic\Config\Env;
use Ninja\Cosmic\Parser\MarkdownParser;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Command\Command;

use function Termwind\render;
use function Termwind\style;

class CommandHelpRenderer
{
    protected array $default_styles = [
        'container'    => 'w-120 ml-2',
        'footer'       => 'w-120 pt-2 ml-2',
        'emphasis'     => 'font-bold text-comment italic',
        'strong'       => 'font-bold text-info',
        'strike'       => 'line-through',
        'hr'           => 'text-info',
        'h1'           => 'invisible',
        'heading'      => 'text-info pt-1',
        'language-sh'  => 'w-120',
        'language-php' => 'w-120',
        'language-js'  => 'w-120',
        'language-sql' => 'w-120',
        'language'     => 'w-120',
        'link'         => 'text-info underline',
        'icon'         => 'mr-2',
    ];

    public function __construct(private readonly array $help_directories, private readonly MarkdownParser $parser) {}

    public function render(Command $command): void
    {
        if ($this->findCommandHelpFile($command)) {
            $this->renderCommandHelpFile($command);
            return;
        }

        $this->renderCommandInlineHelp($command);
    }

    private function findCommandHelpFile(Command $command): ?string
    {
        foreach ($this->help_directories as $help_directory) {
            $help_file = sprintf("%s/%s.md", $help_directory, $command->getName());
            if (file_exists($help_file)) {
                return $help_file;
            }
        }

        return null;
    }

    private function renderCommandHelpFile(Command $command): void
    {
        $help_file = $this->findCommandHelpFile($command);

        $html = $this->parser->text(file_get_contents($help_file));
        $this->renderHeader();
        render('<div class="container">' . $html . '</div>');
        $this->renderFooterForCommand($command);
    }

    private function renderCommandInlineHelp(Command $command): void
    {
        $description = $command->getDescription();
        $help        = $command->getProcessedHelp();

        if ($help && $help !== $description) {
            $this->renderHeader();
            Terminal::output()->write("\n");
            Terminal::output()->write('  ' . str_replace("\n", "\n  ", $help));
            Terminal::output()->write("\n");
        }
    }

    private function renderFooterForCommand(Command $command): void
    {
        $help_file_url = sprintf("%s/%s.md", "https://diego.ninja", $command->getName());
        $content       = sprintf(
            'Generated by <span class="icon">%s</span><strong class="strong">%s</strong>. Online version of this help page can be found <a href="%s" class="link">here</a>', //phpcs:ignore
            Terminal::getTheme()->getAppIcon(),
            Env::get("APP_NAME"),
            $help_file_url
        );

        $html = '<div class="footer">';
        $html .= sprintf('<span>%s</span>', $content);
        $html .= '</div>';

        render($html);
    }

    private function renderHeader(): void
    {
        Terminal::output()->write("\n");
        Terminal::output()->write("<comment>Help:</comment>");
        Terminal::output()->write("\n");
    }

    public function loadStyles(array $custom_styles = []): void
    {
        $styles = array_merge($this->default_styles, $custom_styles);
        foreach ($styles as $name => $style) {
            style($name)->apply($style);
        }
    }
}
