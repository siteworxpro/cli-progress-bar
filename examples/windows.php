<?php

use Siteworx\ProgressBar\CliProgressBar;

require_once('./../vendor/autoload.php');

$bar = new CliProgressBar(10, 3);
$bar->displayAlternateProgressBar();
$bar->display();
$bar->end();