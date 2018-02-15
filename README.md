# cli-progress-bar [![Build Status](https://travis-ci.org/siteworxpro/cli-progress-bar.svg?branch=master)](https://travis-ci.org/siteworxpro/cli-progress-bar)
Progress bar for cli apps

[Forked From dariuszp/cli-progress-bar](https://github.com/dariuszp/cli-progress-bar)

![example animation](examples/img/terminal.gif)

## Installation

```bash
composer require siteworx/cli-progress-bar
```

## Usage

```php
use Siteworx\ProgressBar\CliProgressBar;
$bar = new CliProgressBar(10, 5);
$bar->display();
$bar->end();
```

Code above will show half full progress bar:

```
▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░░ 50.0% (5/10)
```

Windows can't handle some UTF characters so there is an alternate method to display progress bar:

```php
use  Siteworx\ProgressBar\CliProgressBar;
$bar = new CliProgressBar();
$bar->displayAlternateProgressBar(); // this only switch style

$bar->display();
$bar->end();
```

Output will be:

```
XXXX____________________________________ 10.0% (10/100)
```

Add text to the progress bar using the following methods
```php
use Siteworx\ProgressBar\CliProgressBar;
$bar = new CliProgressBar(50, 0, "My Custom Text");
$bar->display();
$bar->end();
```
or
```php
use Siteworx\ProgressBar\CliProgressBar;
$bar = new CliProgressBar();
$bar->setDetails("My Custom Text");
$bar->display();
$bar->end();
```

Estimated time to completion is available. At least 2 iterations are required to calculate an ET.  
The more iterations the better the estimate calculation will be. 

```php
use Siteworx\ProgressBar\CliProgressBar;
$bar = new CliProgressBar();
$bar->displayTimeRemaining()->display();
$bar->end();
```

will output
```
▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░░ 50.0% (5/10) 02:14
```


Also update asynchronously with setDetails()

More features like:
- changing progress bar length (basicWithShortBar.php)
- changing bar color (colors.php)
- animation example (basic.php)
- etc...

in [example](examples/) directory.

----
Author: Półtorak Dariusz
Contributors: [@mathmatrix828 - Mason Phillips](https://github.com/mathmatrix828/)

License: [MIT](https://opensource.org/licenses/MIT)
