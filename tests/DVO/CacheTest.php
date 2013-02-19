<?php

/**
 * Cache Test
 *
 * @package DVO
 * @author 
 **/
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