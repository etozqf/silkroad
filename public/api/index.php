<?php
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);
define('IN_API', 1);
define('IS_AJAX', 1);
require '../../cmstop.php';
require 'abstract.php';
$cmstop = new cmstop('api');
$cmstop->execute();