<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__.'/bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Davegardnerisme\CruftFlake;

$app = new Application(array(
    'debug'   => true,
    'caching' => true,
));

$app['cache'] = $app->share(function(){
    return new DVO\Cache;
});
$app->register(new DVO\Provider\Memcache());

$app['cruftflake'] = $app->share(function() {
	if (false === class_exists('\ZMQContext')) {
		throw new Exception('ZeroMQ not installed');
	}

	$context = new \ZMQContext();
    $socket = new \ZMQSocket($context, \ZMQ::SOCKET_REQ);
    return new CruftFlake\CruftFlake($context, $socket);
});

// setup the voucher gateway
$app['vouchers.gateway'] = $app->share(function() use ($app){
    return new DVO\Entity\Voucher\VoucherGateway($app['cruftflake']);
});

// setup the voucher factory
$app['vouchers.factory'] = $app->share(function() use($app) {
    return new DVO\Entity\Voucher\VoucherFactory($app['vouchers.gateway'], $app['memcache']);
});

// setup the voucher controller
$app['vouchers.controller'] = $app->share(function() use ($app) {
    return new DVO\Controller\VoucherController($app['vouchers.factory']);
});

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => __DIR__.'/../cache/',
));

// only get, put & delete
$app->get('/vouchers/{id}', "vouchers.controller:indexJsonAction");
$app->put('/vouchers/{id}', "vouchers.controller:updateJsonAction");
$app->delete('/vouchers/{id}', "vouchers.controller:deleteJsonAction");

$app->get('/vouchers', "vouchers.controller:indexJsonAction");
$app->put('/vouchers', "vouchers.controller:updateJsonAction");
$app->delete('/vouchers', "vouchers.controller:deleteJsonAction");
$app->post('/vouchers', "vouchers.controller:createJsonAction");


return $app;