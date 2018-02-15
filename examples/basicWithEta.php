<?php

use Siteworx\ProgressBar\CliProgressBar;

require_once('./../vendor/autoload.php');

$bar = new CliProgressBar(100, 0, "Testing Text");
$bar->displayTimeRemaining()->display();

$bar->setColorToRed();

while($bar->getCurrentstep() < $bar->getSteps()) {
    usleep(random_int(50000, 200000));
    $bar->progress();

    if ($bar->getCurrentstep() >= ($bar->getSteps() / 2)) {
        $bar->setColorToYellow();
    }
}

$bar->setColorToGreen();
$bar->display();

$bar->end();
