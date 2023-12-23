<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Progress;

use Khill\Duration\Duration;
use RuntimeException;

class PercentProgressBar
{
    public const CARRIAGE_RETURN_CHARACTER = "\r";
    public const NEW_LINE_CHARACTER = PHP_EOL;
    public const PROGRESS_INCOMPLETE_CHARACTER = '░';
    public const PROGRESS_COMPLETE_CHARACTER = '▓';

    private int $progress = 0;
    private int $maxProgress = 100;
    private string $message = 'Working...';
    private int $barWidth = 20;

    private int $startTime;

    private float $lastProgressAdvancement;
    private array $advancementTimings = [];
    private float $maxAdvancementTimings = 50;

    public function __construct()
    {
        $this->startTime = time();
        $this->lastProgressAdvancement = microtime(true);
    }

    public function setProgress($progress): static
    {
        $this->progress = $progress;
        return $this;
    }

    public function setMaxProgress($maxProgress): static
    {
        if ($maxProgress <= 0) {
            throw new RuntimeException('Max progress can not be zero or below.');
        }

        $this->maxProgress = $maxProgress;
        $this->maxAdvancementTimings = $maxProgress * 0.1;

        return $this;
    }

    public function setMessage($message): static
    {
        $this->message = $message;
        return $this;
    }

    public function setBarWidth($barWidth): static
    {
        $this->barWidth = $barWidth;
        return $this;
    }

    public function advance(): static
    {
        $now = microtime(true);
        $this->advancementTimings[] = $now - $this->lastProgressAdvancement;
        $this->lastProgressAdvancement = $now;

        if (count($this->advancementTimings) > $this->maxAdvancementTimings) {
            array_shift($this->advancementTimings);
        }

        $this->progress++;
        return $this;
    }

    private function getPercentage(): string
    {
        return number_format(($this->progress / $this->maxProgress) * 100, 1, '.', '');
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

        return round($averageAdvancementTiming * ($this->maxProgress - $this->progress));
    }

    private function getHumanReadableTimeRemaining(): string
    {
        return $this->getTimeRemaining() >= 1 ? (new Duration($this->getTimeRemaining()))->humanize() : '-';
    }

    public function display(): void
    {
        echo self::CARRIAGE_RETURN_CHARACTER;

        echo $this->message;
        echo '   ';

        $percentage = $this->getPercentage();

        echo $percentage . '%';
        echo '   ';

        echo $this->progress . '/' . $this->maxProgress;
        echo '   ';

        echo 'ETC: ';
        echo $this->getHumanReadableTimeRemaining();
        echo '   ';

        echo 'Elapsed: ';
        echo $this->getHumanReadableTimeElapsed();
        echo '   ';

        $barCompleteWidth = ceil($this->barWidth * (int) $percentage / 100);
        $barIncompleteWidth = floor($this->barWidth * (100 - (int) $percentage) / 100);

        if ($barCompleteWidth > $this->barWidth) {
            $barCompleteWidth = $this->barWidth;
        }

        if ($barIncompleteWidth < 0) {
            $barIncompleteWidth = 0;
        }

        if($barCompleteWidth + $barIncompleteWidth > $this->barWidth) {
            $barIncompleteWidth -= $this->barWidth - $barCompleteWidth;
        }

        echo str_repeat(self::PROGRESS_COMPLETE_CHARACTER, (int) $barCompleteWidth);
        echo str_repeat(self::PROGRESS_INCOMPLETE_CHARACTER, (int) $barIncompleteWidth);

        echo '   ';
    }

    public function complete(): void
    {
        $this->setProgress($this->maxProgress)->display();

        echo self::NEW_LINE_CHARACTER;
    }
}
