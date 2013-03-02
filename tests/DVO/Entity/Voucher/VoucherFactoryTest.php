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
		$obj     = new \DVO\Entity\Voucher\VoucherFactory($gateway, $cache);
        $this->assertInstanceOf('\DVO\Entity\Voucher\VoucherFactory', $obj);
    }

    public function providerGetVouchersGateway()
    {
    	return array(
    			//array(0, null, array()),
    			//array(2, null, array(array('code' => 'ABC123', 'description' => 'some code innit'),array('code' => 'XYZ987', 'description' => 'another code innit'))),
    			array(1, 'ABC123', array('ABC123' => array('code' => 'ABC123', 'description' => 'some code innit'), 'XYZ987' => array('code' => 'XYZ987', 'description' => 'another code innit'))),
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
    public function testGetVouchers($number, $voucherId, $gatewayArray)
    {
    	$gateway = $this->getMockGateway();
    	$gateway->expects($this->once())
    	        ->method('getVouchers')
    	        ->will($this->returnValue($gatewayArray));

		$cache   = $this->getMock('\DVO\Cache');
		$obj     = new \DVO\Entity\Voucher\VoucherFactory($gateway, $cache);

		$vouchers = $obj->getVouchers($voucherId);
		$this->assertCount($number, $vouchers);
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