<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class CacheTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Cache is just an empty class atm!
	 *
	 * @return void
	 * @author
	 **/
    public function testCache() {
        $cache = new \DVO\Cache;
        $this->assertInstanceOf('\DVO\Cache', $cache);
    }
}