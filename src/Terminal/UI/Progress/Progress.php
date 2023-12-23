<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Progress;

use Khill\Duration\Duration;
use Ninja\Cosmic\Terminal\Terminal;
use Symfony\Component\Console\Output\ConsoleSectionOutput;

class Progress
{
    protected int $currentStep = 0;

    protected int $startTime;
    protected float $lastProgressAdvancement;
    protected array $advancementTimings = [];
    protected float $maxAdvancementTimings = 50;

    protected ConsoleSectionOutput $section;

    public function __construct(
        protected int $steps,
        protected ProgressConfig $config,
        protected string $details = ""
    ) {
        $this->startTime = time();
        $this->lastProgressAdvancement = microtime(true);

        $this->section = Terminal::output()->section();

        $this->setSteps($steps);
        $this->setDetails($details);
    }

    public function setProgressTo(int $currentStep): static
    {
        $this->setCurrentstep($currentStep);
        return $this;
    }


    public function addCurrentStep(int $currentStep): static
    {
        $this->currentStep += $currentStep;
        return $this;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;
        return $this;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function progress(int $step = 1, bool $display = true): static
    {
        $this->setCurrentstep($this->getCurrentStep() + $step);

        if ($display) {
            $this->display();
        }

        return $this;
    }

    public function getCurrentStep(): int
    {
        return $this->currentStep;
    }

    public function setCurrentStep(int $currentStep): static
    {
        if ($currentStep < 0) {
            throw new \InvalidArgumentException('Current step must be 0 or above');
        }

        $now = microtime(true);
        $this->advancementTimings[] = $now - $this->lastProgressAdvancement;
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

    public function display(): void
    {
        Terminal::hideCursor();
        $this->section->clear();
        $this->section->writeln("");
        $this->section->write($this->draw());
    }

    /**
     * @return string
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
                "\n"
            ],
            $format
        );
    }

    private function drawBar(): string
    {
        $fullValue = floor($this->getCurrentStep() / $this->getSteps() * $this->config->getLength());
        $emptyValue = $this->config->getLength() - $fullValue;
        $percent = ($this->getCurrentStep() / $this->getSteps()) * 100;

        $color = $this->getBarColor($percent);

        return sprintf(
            "<%3\$s>%1\$s%2\$s</%3\$s>",
            str_repeat($this->config->getCharFull(), (int) $fullValue),
            str_repeat($this->config->getCharEmpty(), (int) $emptyValue),
            $color
        );
    }

    private function drawPercent(): string
    {
        $percent = ($this->getCurrentStep() / $this->getSteps()) * 100;
        $formatted_percent = number_format($percent, 1, '.', ' ');

        return sprintf(
            "<%2\$s>%1\$.1f%%</%2\$s>",
            $formatted_percent,
            $this->getBarColor($percent)
        );
    }

    private function drawSteps(): string
    {
        return sprintf(
            "<%3\$s>(%1\$d/%2\$d)</%3\$s>",
            $this->getCurrentStep(),
            $this->getSteps(),
            $this->config->getTextColor()
        );
    }

    private function drawDetails(): string
    {
        return $this->details ?
            sprintf(
                "<%2\$s>%1\$s</%2\$s>",
                $this->getDetails(),
                $this->config->getTextColor()
            ) : "";
    }

    private function getIntensity(float $percent): int
    {

        $intensities = array_reverse([100, 100, 200, 300, 400, 500, 600, 700, 800, 900, 900]);
        return $intensities[(int) floor($percent / 10)];
    }

    private function getBarColor(float $percent): string
    {
        if ($this->config->getUseSegments()) {
            match (true) {
                $percent < 25 => $color = 'red',
                $percent < 50 => $color = 'orange',
                $percent < 75 => $color = 'yellow',
                default => $color = 'green',
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

    private function getTimeElapsed(): int
    {
        return time() - $this->startTime;
    }

    private function getHumanReadableTimeElapsed(): string
    {
        return $this->getTimeElapsed() >= 1 ? (new Duration($this->getTimeElapsed()))->humanize() : '-';
    }

    private function getTimeRemaining(): float|int
    {
        if (count($this->advancementTimings) === 0) {
            return 0;
        }

        $averageAdvancementTiming = array_sum($this->advancementTimings) / count($this->advancementTimings);

        return round($averageAdvancementTiming * ($this->steps - $this->currentStep));
    }

    private function getHumanReadableTimeRemaining(): string
    {
        return $this->getTimeRemaining() >= 1 ? (new Duration($this->getTimeRemaining()))->humanize() : '-';
    }


    public function getSteps(): int
    {
        return $this->steps;
    }

    /**
     * @param int $steps
     * @return $this
     */
    public function setSteps(int $steps): static
    {
        if ($steps < 0) {
            throw new \InvalidArgumentException('Steps amount must be 0 or above');
        }

        $this->setCurrentStep($this->getCurrentStep());
        $this->maxAdvancementTimings = $steps * 0.1;

        return $this;
    }

    public function __toString()
    {
        return $this->draw();
    }

    public function end(): void
    {
        $this->section->writeln("");
        Terminal::restoreCursor();
    }
}
