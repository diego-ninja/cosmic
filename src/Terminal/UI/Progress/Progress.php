<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Progress;

use Stringable;
use InvalidArgumentException;
use Khill\Duration\Duration;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Output\ConsoleSectionOutput;

/**
 * Class Progress
 *
 * This class is used to display a progress bar in the terminal.
 *
 * @package Ninja\Cosmic\Terminal\UI
 */
class Progress implements Stringable
{
    /**
     * The current step of the progress.
     */
    protected int $currentStep = 0;

    /**
     * The start time of the progress.
     */
    protected int $startTime;

    /**
     * The last progress advancement.
     */
    protected float $lastProgressAdvancement;

    /**
     * The timings of the progress advancements.
     * @var float[]
     */
    protected array $advancementTimings = [];

    /**
     * The maximum number of advancement timings.
     */
    protected float $maxAdvancementTimings = 50;

    /**
     * The section of the console where the progress is displayed.
     */
    protected ConsoleSectionOutput $section;

    /**
     * Progress constructor.
     *
     * @param int $steps The total number of steps in the progress.
     * @param ProgressConfig $config The configuration for the progress display.
     * @param string $details Additional details to display with the progress.
     */
    public function __construct(
        protected int $steps,
        protected ProgressConfig $config,
        protected string $details = ""
    ) {
        $this->startTime               = time();
        $this->lastProgressAdvancement = microtime(true);

        $this->section = Terminal::output()->section();

        $this->setSteps($steps);
        $this->setDetails($details);
    }

    /**
     * Set the current step of the progress to a specific value.
     *
     * @param int $currentStep The current step of the progress.
     *
     * @return $this
     */
    public function setProgressTo(int $currentStep): static
    {
        $this->setCurrentstep($currentStep);
        return $this;
    }

    /**
     * Add a specific value to the current step of the progress.
     *
     * @param int $currentStep The value to add to the current step of the progress.
     *
     * @return $this
     */
    public function addCurrentStep(int $currentStep): static
    {
        $this->currentStep += $currentStep;
        return $this;
    }

    /**
     * Set the details of the progress.
     *
     * @param string $details The details of the progress.
     *
     * @return $this
     */
    public function setDetails(string $details): static
    {
        $this->details = $details;
        return $this;
    }

    /**
     * Get the details of the progress.
     *
     * @return string The details of the progress.
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * Advance the progress by a specific number of steps and optionally display the progress.
     *
     * @param int $step The number of steps to advance the progress.
     * @param bool $display Whether to display the progress.
     *
     * @return $this
     */
    public function progress(int $step = 1, bool $display = true): static
    {
        $this->setCurrentstep($this->getCurrentStep() + $step);

        if ($display) {
            $this->display();
        }

        return $this;
    }

    /**
     * Get the current step of the progress.
     *
     * @return int The current step of the progress.
     */
    public function getCurrentStep(): int
    {
        return $this->currentStep;
    }

    /**
     * Set the current step of the progress to a specific value.
     *
     * @param int $currentStep The current step of the progress.
     *
     * @return $this
     */
    public function setCurrentStep(int $currentStep): static
    {
        if ($currentStep < 0) {
            throw new InvalidArgumentException('Current step must be 0 or above');
        }

        $now                           = microtime(true);
        $this->advancementTimings[]    = $now - $this->lastProgressAdvancement;
        $this->lastProgressAdvancement = $now;

        if (count($this->advancementTimings) > $this->maxAdvancementTimings) {
            array_shift($this->advancementTimings);
        }

        $this->currentStep = $currentStep;
        if ($this->currentStep > $this->getSteps()) {
            $this->currentStep = $this->getSteps();
        }

        return $this;
    }

    public function getSteps(): int
    {
        return $this->steps;
    }

    /**
     * @return $this
     */
    public function setSteps(int $steps): static
    {
        if ($steps < 0) {
            throw new InvalidArgumentException('Steps amount must be 0 or above');
        }

        $this->setCurrentStep($this->getCurrentStep());
        $this->maxAdvancementTimings = $steps * 0.1;

        return $this;
    }

    public function __toString(): string
    {
        return $this->draw();
    }

    public function end(): void
    {
        $this->section->writeln("");
        Terminal::restoreCursor();
    }

    /**
     * Display the progress.
     */
    public function display(): void
    {
        Terminal::hideCursor();
        $this->section->clear();
        $this->section->writeln("");
        $this->section->write($this->draw());
    }

    /**
     * Draw the progress.
     *
     * @return string The drawn progress.
     */
    public function draw(): string
    {
        $format = $this->config->getFormat();
        return str_replace(
            [
                '{bar}',
                '{percentage}',
                '{steps}',
                '{detail}',
                '{elapsed}',
                '{remaining}',
                '{nl}',
            ],
            [
                $this->drawBar(),
                $this->drawPercent(),
                $this->drawSteps(),
                $this->drawDetails(),
                $this->getHumanReadableTimeElapsed(),
                $this->getHumanReadableTimeRemaining(),
                "\n",
            ],
            $format
        );
    }

    /**
     * Draw the bar of the progress.
     *
     * @return string The drawn bar of the progress.
     */
    private function drawBar(): string
    {
        $fullValue  = floor($this->getCurrentStep() / $this->getSteps() * $this->config->getWidth());
        $emptyValue = $this->config->getWidth() - $fullValue;
        $percent    = ($this->getCurrentStep() / $this->getSteps()) * 100;

        $color = $this->getBarColor($percent);

        return sprintf(
            "<%3\$s>%1\$s%2\$s</%3\$s>",
            str_repeat($this->config->getCharFull(), (int)$fullValue),
            str_repeat($this->config->getCharEmpty(), (int)$emptyValue),
            $color
        );
    }

    /**
     * Draw the percentage of the progress.
     *
     * @return string The drawn percentage of the progress.
     */
    private function drawPercent(): string
    {
        $percent           = ($this->getCurrentStep() / $this->getSteps()) * 100;
        $formatted_percent = number_format($percent, 1, '.', ' ');

        return sprintf(
            "<%2\$s>%1\$.1f%%</%2\$s>",
            $formatted_percent,
            $this->getBarColor($percent)
        );
    }

    /**
     * Draw the steps of the progress.
     *
     * @return string The drawn steps of the progress.
     */
    private function drawSteps(): string
    {
        return sprintf(
            "<%3\$s>(%1\$d/%2\$d)</%3\$s>",
            $this->getCurrentStep(),
            $this->getSteps(),
            $this->config->getTextColor()
        );
    }

    /**
     * Draw the details of the progress.
     *
     * @return string The drawn details of the progress.
     */
    private function drawDetails(): string
    {
        return $this->details !== '' && $this->details !== '0' ?
            sprintf(
                "<%2\$s>%1\$s</%2\$s>",
                $this->getDetails(),
                $this->config->getTextColor()
            ) : "";
    }

    /**
     * Get the intensity of the color of the progress bar.
     *
     * @param float $percent The percentage of the progress.
     *
     * @return int The intensity of the color of the progress bar.
     */
    private function getIntensity(float $percent): int
    {
        $intensities = array_reverse([100, 100, 200, 300, 400, 500, 600, 700, 800, 900, 900]);
        return $intensities[(int)floor($percent / 10)];
    }

    /**
     * Get the color of the progress bar. It can be a color or a color with an intensity.
     *
     * @param float $percent The percentage of the progress.
     *
     * @return string The color of the progress bar.
     */
    private function getBarColor(float $percent): string
    {
        $color = ''; //just to trick phpstan
        if ($this->config->getUseSegments()) {
            match (true) {
                $percent < 25 => $color = 'red',
                $percent < 50 => $color = 'orange',
                $percent < 75 => $color = 'yellow',
                default       => $color       = 'green',
            };
        } else {
            $color = $this->config->getBarColor();
        }

        if ($this->config->getApplyGradient()) {
            return sprintf(
                '%s%d',
                $color,
                $this->getIntensity($percent)
            );
        }

        return $color;
    }

    /**
     * Get the elapsed time of the progress.
     */
    private function getTimeElapsed(): int
    {
        return time() - $this->startTime;
    }

    /**
     * Get the elapsed time of the progress formatted to be readable by humans.
     */
    private function getHumanReadableTimeElapsed(): string
    {
        return $this->getTimeElapsed() >= 1 ? (new Duration($this->getTimeElapsed()))->humanize() : '-';
    }

    /**
     * Get the remaining time of the progress.
     */
    private function getTimeRemaining(): float|int
    {
        if ($this->advancementTimings === []) {
            return 0;
        }

        $averageAdvancementTiming = array_sum($this->advancementTimings) / count($this->advancementTimings);

        return round($averageAdvancementTiming * ($this->steps - $this->currentStep));
    }

    /**
     * Get the remaining time of the progress formatted to be readable by humans.
     */
    private function getHumanReadableTimeRemaining(): string
    {
        return $this->getTimeRemaining() >= 1 ? (new Duration($this->getTimeRemaining()))->humanize() : '-';
    }
}
