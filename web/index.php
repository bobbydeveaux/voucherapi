<?php

use Symfony\Component\HttpFoundation\Request;

$app = require_once __DIR__.'/../app/app.php';

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

if (PHP_VERSION_ID < 50400) {
    // we want to be able to use fancy arrays, traits & closure ($this)
    throw new Exception('PHP 5.4 or greater required.');
}

// Decide whether to enable XHProf
$xhprof = false;
if (true === extension_loaded('xhprof')) {
	$xhprof = true;
	xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}

if ('cli' === php_sapi_name() && count($argv) > 0) {
	$path = '/';
	$arr  = array();
	switch(count($argv)) {
		case 2:
			list($_, $method) = $argv;
			break;
		case 3:
			list($_, $method, $path) = $argv;
			break;
		case 4:
			list($_, $method, $path, $parameters) = $argv;
			parse_str($parameters, $arr);
			break;
		default:
		case 1:
			print 'Invalid args. Format index.php METHOD path params';
			exit;
	}

    $request = Request::create($path, $method, $arr);
    $app->run($request);
} else {
    $app->run();
    //$app['http_cache']->run();
}

if (true === $xhprof) {
	include_once '/usr/local/php5/xhprof/xhprof_lib/utils/xhprof_lib.php';
    include_once '/usr/local/php5/xhprof/xhprof_lib/utils/xhprof_runs.php';

	$profiler_namespace = 'voucherapi';
	$xhprof_data        = xhprof_disable();
	$xhprof_runs        = new XHProfRuns_Default();
	$run_id             = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);

    // url to the XHProf UI libraries (change the host name and path)
    $profiler_url = sprintf('/xhprof/xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
    echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';
}