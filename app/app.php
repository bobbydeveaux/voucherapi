<?php

require_once __DIR__.'/bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Davegardnerisme\CruftFlake;

$app = new Application();
$app['debug'] = true;

// example route - kinda obvious what this does :)
$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

// setup the app cache
$app['cache'] = $app->share(function(){
    return new DVO\Cache;
});

$app['cruftflake'] = $app->share(function() {
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
    return new DVO\Entity\Voucher\VoucherFactory($app['vouchers.gateway'], $app['cache']);
});

// setup the voucher controller
$app['vouchers.controller'] = $app->share(function() use ($app) {
    return new DVO\Controller\VoucherController($app['vouchers.factory']);
});

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->get('/vouchers', "vouchers.controller:indexJsonAction");
$app->post('/vouchers', "vouchers.controller:createJsonAction");
$app->put('/vouchers', "vouchers.controller:updateJsonAction");
$app->delete('/vouchers', "vouchers.controller:deleteJsonAction");

return $app;