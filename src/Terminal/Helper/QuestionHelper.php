<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Helper;

use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use function strlen;
use function rtrim;
use function str_replace;
use function str_repeat;
use function str_ends_with;

final class QuestionHelper extends SymfonyQuestionHelper
{
    protected function writePrompt(OutputInterface $output, Question $question): void
    {
        $text = self::escapeTrailingBackslash($question->getQuestion());
        $output->write($text);
    }

    private static function escapeTrailingBackslash(string $text): string
    {
        if (str_ends_with($text, '\\')) {
            $len = strlen($text);
            $text = rtrim($text, '\\');
            $text = str_replace("\0", '', $text);
            $text .= str_repeat("\0", $len - strlen($text));
        }

        return $text;
    }
}
