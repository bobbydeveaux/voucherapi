<?php

/**
 * Cache Test
 *
 * @package DVO
 * @author 
 **/
class CacheTest extends \PHPUnit_Framework_TestCase
{
    public function testCache() {
        $cache = new \DVO\Cache;
        $this->assertInstanceOf('\DVO\Cache', $cache);
    }
}