<?php
include __DIR__ . '/../unit/bootstrap.php';

use SagePHP\System\Exec;
use SagePHP\VCS\Git;

$exec = new Exec();
$git = new Git($exec);

$git->cloneRepository('git@github.com:francodacosta/phmagick.git');