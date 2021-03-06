<?php

use Siteworx\ProgressBar\CliProgressBar;

class CliProgressBarTest extends \PHPUnit\Framework\TestCase
{

    public function testDefaultSettings(): void
    {
        $bar = new CliProgressBar();
        $this->assertEquals($bar->getBarLength(), 40, 'Bar length should be 40 at the beginning');
        $this->assertEquals($bar->getCurrentstep(), 0, 'Progress bar should start with 0');
        $this->assertEquals($bar->getSteps(), 100, 'Default number of steps should be 100');
    }

    public function testDefaultBarString(): void
    {
        $bar = new CliProgressBar(100, 10);
        $this->assertEquals((string)$bar, "\r▓▓▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 10.0% (10/100) ");
    }

    public function testFullBarStringFromSources(): void
    {
        $fullBar = "\r▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ 100.0% (100/100) ";
        $bar = new CliProgressBar(100, 100);
        $this->assertEquals((string)$bar, $fullBar);

        $bar = new CliProgressBar();
        $bar->setProgressTo(100);
        $this->assertEquals((string)$bar, $fullBar);

        $bar = new CliProgressBar();
        $bar->setProgressTo(99);
        $bar->progress();
        $this->assertEquals((string)$bar, $fullBar);
    }

    public function testBarTimeEt(): void
    {
        $bar = new CliProgressBar(5);
        $bar->displayTimeRemaining()->display();

        while ($bar->getCurrentstep() < 3) {
            $bar->progress();
            sleep(5);
        }

        $expected = "\r▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░ 60.0% (3/5) 00:00:10";

        $this->assertEquals($expected, (string) $bar);
    }

    public function testBarProgress(): void
    {
        $bar = new CliProgressBar(10, 1);
        $this->assertEquals((string)$bar, "\r▓▓▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 10.0% (1/10) ");
        $bar->progress();
        $this->assertEquals((string)$bar, "\r▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 20.0% (2/10) ");
        $bar->progress(2);
        $this->assertEquals((string)$bar, "\r▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░░░░░░ 40.0% (4/10) ");
        $bar->setProgressTo(3);
        $this->assertEquals((string)$bar, "\r▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 30.0% (3/10) ");
        $bar->setProgressTo(99);
        $this->assertEquals((string)$bar, "\r▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ 100.0% (10/10) ");
    }

    public function testBarOverflow(): void
    {
        $fullBar = "\r▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ 100.0% (100/100) ";
        $bar = new CliProgressBar();
        $bar->setProgressTo(99);
        $bar->progress();
        $bar->progress();
        $bar->progress();
        $bar->progress();
        $this->assertEquals((string)$bar, $fullBar);

        $bar = new CliProgressBar();
        $bar->setProgressTo(99);
        $bar->progress(5);
        $this->assertEquals((string)$bar, $fullBar);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConstructorStepsArgument(): void
    {
        $bar = new CliProgressBar(10, -5);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConstructorCurrentStepArgument(): void
    {
        $bar = new CliProgressBar(-1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidSetCurrentStep(): void
    {
        $bar = new CliProgressBar();
        $bar->setCurrentStep(-10);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidSetProgressTo(): void
    {
        $bar = new CliProgressBar();
        $bar->setProgressTo(-10);
    }
}