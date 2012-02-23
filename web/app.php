<?php

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);
$kernel->handle(Request::createFromGlobals())->send();

if (function_exists('xdebug_is_enabled') && xdebug_is_enabled()) {
    echo sprintf("<br>the time of script execute: %0.2fs", xdebug_time_index());
}
echo sprintf("<br>peak memory usage: %0.2fKb", memory_get_peak_usage()/1024);
