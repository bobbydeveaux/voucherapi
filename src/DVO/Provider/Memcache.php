<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DVO\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Memcache Provider
 *
 * @package Cache Class
 * @author
 **/
class Memcache implements ServiceProviderInterface
{
    /**
     * Boot.
     *
     * @param Application $app The application.
     *
     * @return void
     */
    public function boot(Application $app)
    {

    }

    /**
     * Register the provider.
     *
     * @param Application $app The application.
     *
     * @return \DVO\Cache
     */
    public function register(Application $app)
    {
        /* @codingStandardsIgnoreStart */
        $app['memcache'] = $app->share(function() use ($app) {
            $memcache = $app['cache'];
            $servers = array(
                array('127.0.0.1', 11211),
            );

            array_walk($servers, function($server) use ($memcache) {
                $memcache->addServer($server[0], $server[1]);
            });

            return $memcache;
        });
        /* @codingStandardsIgnoreEnd */
    }
}
