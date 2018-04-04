<?php

namespace Siteworx\ProgressBar;

/**
 * Class CliProgressBar
 * @package Siteworx\ProgressBar
 */
class CliProgressBar
{
    private const COLOR_CODE_FORMAT = "\033[%dm";

    /**
     * @var int
     */
    protected $barLength = 40;

    /**
     * @var array|bool
     */
    protected $color = false;

    /**
     * @var int
     */
    protected $steps = 100;

    /**
     * @var int
     */
    protected $currentStep = 0;

    /**
     * @var string
     */
    protected $detail = '';

    /**
     * @var string
     */
    protected $charEmpty = '░';

    /**
     * @var string
     */
    protected $charFull = '▓';
    /**
     * @var string
     */
    protected $defaultCharEmpty = '░';

    /**
     * @var string
     */
    protected $defaultCharFull = '▓';

    /**
     * @var string
     */
    protected $alternateCharEmpty = '_';

    /**
     * @var string
     */
    protected $alternateCharFull = 'X';

    /**
     * @var bool 
     */
    protected $displayTimeRemaining = false;

    /**
     * @var int
     */
    private $lastIterationTime;

    /**
     * @var float
     */
    private $totalTime = 0;

    /**
     * CliProgressBar constructor.
     * 
     * @param int $steps
     * @param int $currentStep
     * @param string $details
     * @param bool $forceDefaultProgressBar
     * @throws \Exception
     */
    public function __construct($steps = 100, $currentStep = 0, $details = '', $forceDefaultProgressBar = false)
    {
        $this->setSteps($steps);
        $this->setProgressTo($currentStep);
        $this->setDetails($details);

        // Windows terminal is unable to display utf characters and colors
        if (!$forceDefaultProgressBar && strtoupper(strpos(PHP_OS,'WIN') !== 0)) {
            $this->displayDefaultProgressBar();
        }
    }

    /**
     * @return CliProgressBar
     */
    public function displayTimeRemaining(): CliProgressBar
    {
        $this->displayTimeRemaining = true;
        return $this;
    }

    /**
     * @param int $currentStep
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setProgressTo($currentStep): CliProgressBar
    {
        $this->setCurrentStep($currentStep);
        return $this;
    }

    /**
     * @return $this
     */
    public function displayDefaultProgressBar(): CliProgressBar
    {
        $this->charEmpty = $this->defaultCharEmpty;
        $this->charFull = $this->defaultCharFull;
        return $this;
    }

    /**
     * @return $this
     */
    public function setColorToDefault(): CliProgressBar
    {
        $this->color = false;
        return $this;
    }

    public function setColorToBlack(): CliProgressBar
    {
        return $this->setColor(30, 39);
    }

    /**
     * @param $start
     * @param $end
     * @return $this
     */
    protected function setColor($start, $end): CliProgressBar
    {
        $this->color = array(
            sprintf(self::COLOR_CODE_FORMAT, $start),
            sprintf(self::COLOR_CODE_FORMAT, $end),
        );
        return $this;
    }

    public function setColorToRed(): CliProgressBar
    {
        return $this->setColor(31, 39);
    }

    public function setColorToGreen(): CliProgressBar
    {
        return $this->setColor(32, 39);
    }

    public function setColorToYellow(): CliProgressBar
    {
        return $this->setColor(33, 39);
    }

    public function setColorToBlue(): CliProgressBar
    {
        return $this->setColor(34, 39);
    }

    public function setColorToMagenta(): CliProgressBar
    {
        return $this->setColor(35, 39);
    }

    public function setColorToCyan(): CliProgressBar
    {
        return $this->setColor(36, 39);
    }

    public function setColorToWhite(): CliProgressBar
    {
        return $this->setColor(37, 39);
    }

    /**
     * @return string
     */
    public function getDefaultCharEmpty(): string
    {
        return $this->defaultCharEmpty;
    }

    /**
     * @param string $defaultCharEmpty
     */
    public function setDefaultCharEmpty($defaultCharEmpty): void
    {
        $this->defaultCharEmpty = $defaultCharEmpty;
    }

    /**
     * @return string
     */
    public function getDefaultCharFull(): string
    {
        return $this->defaultCharFull;
    }

    /**
     * @param string $defaultCharFull
     */
    public function setDefaultCharFull($defaultCharFull): void
    {
        $this->defaultCharFull = $defaultCharFull;
    }

    /**
     * @return $this
     */
    public function displayAlternateProgressBar(): CliProgressBar
    {
        $this->charEmpty = $this->alternateCharEmpty;
        $this->charFull = $this->alternateCharFull;
        return $this;
    }

    /**
     * @param int $currentStep
     * @return $this
     */
    public function addCurrentStep($currentStep): CliProgressBar
    {
        $this->currentStep += (int) $currentStep;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharEmpty(): string
    {
        return $this->charEmpty;
    }

    /**
     * @param string $charEmpty
     * @return $this
     */
    public function setCharEmpty($charEmpty): CliProgressBar
    {
        $this->charEmpty = $charEmpty;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharFull(): string
    {
        return $this->charFull;
    }

    /**
     * @param string $charFull
     * @return $this
     */
    public function setCharFull($charFull): CliProgressBar
    {
        $this->charFull = $charFull;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlternateCharEmpty(): string
    {
        return $this->alternateCharEmpty;
    }

    /**
     * @param string $alternateCharEmpty
     * @return $this
     */
    public function setAlternateCharEmpty($alternateCharEmpty): CliProgressBar
    {
        $this->alternateCharEmpty = $alternateCharEmpty;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlternateCharFull(): string
    {
        return $this->alternateCharFull;
    }

    /**
     * @param string $alternateCharFull
     * @return $this
     */
    public function setAlternateCharFull($alternateCharFull): CliProgressBar
    {
        $this->alternateCharFull = $alternateCharFull;
        return $this;
    }

    /**
     * @param string $details
     * @return $this
     */
    public function setDetails($details): CliProgressBar
    {
        $this->detail = $details;
        return $this;
    }

    public function getDetails(): string
    {
        return $this->detail;
    }

    /**
     * @param int $step
     * @param bool $display
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function progress($step = 1, $display = true): CliProgressBar
    {
        $step = (int) $step;
        $this->setCurrentStep($this->getCurrentStep() + $step);

        if ($display) {
            $this->display();
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentStep(): int
    {
        return $this->currentStep;
    }

    /**
     * @param int $currentStep
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setCurrentStep($currentStep): CliProgressBar
    {
        $currentStep = (int) $currentStep;
        if ($currentStep < 0) {
            throw new \InvalidArgumentException('Current step must be 0 or above');
        }

        $this->currentStep = $currentStep;
        if ($this->currentStep > $this->getSteps()) {
            $this->currentStep = $this->getSteps();
        }
        return $this;
    }

    public function display(): void
    {
        print $this->draw();
    }

    /**
     * @return string
     */
    public function draw(): string
    {
        $fullValue = $this->getSteps() !== 0 ? floor($this->getCurrentStep() / $this->getSteps() * $this->getBarLength()) : 0;
        $emptyValue = $this->getBarLength() - $fullValue;
        $prc = $this->getSteps() !== 0 ? number_format(($this->getCurrentStep() / $this->getSteps()) * 100, 1, '.', ' ') : 0.0;

        $colorStart = '';
        $colorEnd = '';
        $timeString = '';
        if ($this->color) {
            $colorStart = $this->color[0];
            $colorEnd = $this->color[1];
        }

        if ($this->displayTimeRemaining) {
            $timeString = $this->calculateTimeRemaining();
        }

        $userDetail = $this->getDetails();
        $userDetail = ((\strlen($userDetail) > 1) ? "{$userDetail} " : '');
        $bar = sprintf("%4\$s%5\$s %3\$.1f%% (%1\$d/%2\$d) ", $this->getCurrentStep(), $this->getSteps(), $prc, str_repeat($this->charFull, (int) $fullValue), str_repeat($this->charEmpty, (int) $emptyValue));
        return sprintf("\r%s%s%s%s%s", $colorStart, $userDetail, $bar, $timeString, $colorEnd);
    }

    private function calculateTimeRemaining(): string
    {
        $now = microtime(true);

        if ($this->lastIterationTime === null) {
            $this->lastIterationTime = $now;
            return '--:--:--';
        }

        $interval = $now - $this->lastIterationTime;
        $this->lastIterationTime = $now;

        $this->totalTime += $interval;

        if ($this->currentStep < 3) {
            return '--:--:--';
        }

        $stepsRemaining = $this->steps - $this->currentStep;

        if ($this->totalTime !== 0) {
            $avgPerStep = $this->totalTime / $this->currentStep;
        } else {
            $avgPerStep = $interval;
        }

        $timeRemaining = round($avgPerStep * $stepsRemaining, 0);

        $minutes = 0;
        while ($timeRemaining >= 60) {
            $minutes++;
            $timeRemaining -= 60;
        }

        $hours = 0;
        while ($minutes >= 60) {
            $hours++;
            $minutes -= 60;
        }

        $seconds = $timeRemaining;

        return str_pad($hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($minutes, 2, 0, STR_PAD_LEFT) . ':' . str_pad($seconds, 2, 0, STR_PAD_LEFT);
    }

    /**
     * @return int
     */
    public function getSteps(): int
    {
        return $this->steps;
    }

    /**
     * @param int $steps
     * @throws \Exception
     * @return $this
     */
    public function setSteps($steps): CliProgressBar
    {
        $steps = (int) $steps;
        if ($steps < 0) {
            throw new \InvalidArgumentException('Steps amount must be 0 or above');
        }

        $this->steps = (int) $steps;

        $this->setCurrentStep($this->getCurrentStep());

        return $this;
    }

    /**
     * @return int
     */
    public function getBarLength(): int
    {
        return $this->barLength;
    }

    /**
     * @param $barLength
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setBarLength($barLength): CliProgressBar
    {
        $barLength = (int) $barLength;
        if ($barLength < 1) {
            throw new \InvalidArgumentException('Progress bar length must be above 0');
        }
        $this->barLength = $barLength;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->draw();
    }

    /**
     * Alias to new line (nl)
     */
    public function end(): void
    {
        $this->nl();
    }

    /**
     * display new line
     */
    public function nl(): void
    {
        print "\n";
    }
}
