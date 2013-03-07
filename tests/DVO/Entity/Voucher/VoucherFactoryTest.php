<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class VoucherFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testVoucherCreate()
    {
		$gateway = $this->getMockGateway();
		$cache   = $this->getMock('\DVO\Cache');
        $cache->enabled = false;
		$obj     = new \DVO\Entity\Voucher\VoucherFactory($gateway, $cache);
        $this->assertInstanceOf('\DVO\Entity\Voucher\VoucherFactory', $obj);
    }

    public function providerGetVouchersGateway()
    {
    	return array(
    			//array(0, null, array()),
    			//array(2, null, array(array('code' => 'ABC123', 'description' => 'some code innit'),array('code' => 'XYZ987', 'description' => 'another code innit'))),
    			array(1, 'ABC123', array('ABC123' => array('voucher_code' => 'ABC123', 'description' => 'some code innit'), 'XYZ987' => array('voucher_code' => 'XYZ987', 'description' => 'another code innit')), false),
                array(1, 'ABC123', array('ABC123' => array('voucher_code' => 'ABC123', 'description' => 'some code innit'), 'XYZ987' => array('voucher_code' => 'XYZ987', 'description' => 'another code innit')), true),
    		);
    }

    /**
     * test the index(get) action
     *
     * @dataProvider providerGetVouchersGateway
     *
     * @return void
     * @author
     **/
    public function testGetVouchers($number, $voucherId, $gatewayArray, $cacheEnabled)
    {
    	$gateway = $this->getMockGateway();

        $cache          = $this->getMock('\DVO\Cache', array('get', 'set'), array(), '', false);
        $cache->enabled = $cacheEnabled;

        if (true === $cache->enabled) {
            $voucher               = new \DVO\Entity\Voucher;
            $voucher->id           = $voucherId;
            $voucher->description  = $gatewayArray[$voucherId]['description'];
            $voucher->voucher_code = $gatewayArray[$voucherId]['voucher_code'];

            $cache->expects($this->once())
                    ->method('get')
                    ->will($this->returnValue(array($voucher)));
        } else {
            $gateway->expects($this->once())
                ->method('getVouchers')
                ->will($this->returnValue($gatewayArray));

             $cache->expects($this->once())
                    ->method('set')
                    ->with();
        }

        $obj            = new \DVO\Entity\Voucher\VoucherFactory($gateway, $cache);

		$vouchers = $obj->getVouchers($voucherId);

        $this->assertInstanceOf("\DVO\Entity\Voucher", $vouchers[0]);
		$this->assertCount($number, $vouchers);
        $this->assertSame($vouchers[0]->getId(), $gatewayArray[$voucherId]['voucher_code']);
        $this->assertSame($vouchers[0]->getVoucherCode(), $gatewayArray[$voucherId]['voucher_code']);
    }

    public function testGetGateway()
    {
    	$gateway = $this->getMockGateway();

    	$cache   = $this->getMock('\DVO\Cache');
    	$obj     = new \DVO\Entity\Voucher\VoucherFactory($gateway, $cache);

    	$this->assertEquals($gateway, $obj->getGateway());
    }

    public function getMockGateway()
    {
		$gateway    = $this->getMock('\DVO\Entity\Voucher\VoucherGateway', array('getVouchers'), array(), '', false);

		return $gateway;
    }
}