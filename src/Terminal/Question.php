<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal;

use Ninja\Cosmic\Terminal\Helper\QuestionHelper;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Question\Question as SymfonyQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Termwind\HtmlRenderer;
use Termwind\Termwind;

class Question
{
    private static StreamableInputInterface|null $streamableInput;

    private SymfonyQuestionHelper $helper;

    public function __construct(SymfonyQuestionHelper $helper = null)
    {
        $this->helper = $helper ?? new QuestionHelper();
    }

    public static function setStreamableInput(StreamableInputInterface|null $streamableInput): void
    {
        self::$streamableInput = $streamableInput ?? new ArgvInput();
    }

    public static function getStreamableInput(): StreamableInputInterface
    {
        return self::$streamableInput ??= new ArgvInput();
    }

    /**
     * @throws ReflectionException
     */
    public function ask(string $question, bool $hideAnswer, mixed $defaultAnswer, iterable $autocomplete = null): mixed
    {
        $html = (new HtmlRenderer())->parse($question)->toString();

        $question = new SymfonyQuestion($html, $defaultAnswer);
        $question->setHidden($hideAnswer);

        if ($autocomplete !== null) {
            $question->setAutocompleterValues($autocomplete);
        }

        $output = Termwind::getRenderer();

        if ($output instanceof SymfonyStyle) {
            $property = (new ReflectionClass(SymfonyStyle::class))
                ->getProperty('questionHelper');

            $property->setAccessible(true);

            $currentHelper = $property->isInitialized($output)
                ? $property->getValue($output)
                : new SymfonyQuestionHelper();

            $property->setValue($output, new QuestionHelper());

            try {
                return $output->askQuestion($question);
            } finally {
                $property->setValue($output, $currentHelper);
            }
        }

        return $this->helper->ask(
            self::getStreamableInput(),
            Termwind::getRenderer(),
            $question,
        );
    }
}
