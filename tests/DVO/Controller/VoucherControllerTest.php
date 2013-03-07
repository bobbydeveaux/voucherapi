<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class VoucherControllerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test Constructor
	 *
	 * @return void
	 * @author
	 **/
    public function testConstructor()
    {
    	$vf = $this->getMock('\DVO\Entity\Voucher\VoucherFactory', array(), array(), '', false);
        $vc = new \DVO\Controller\VoucherController($vf);
        $this->assertInstanceOf('\DVO\Controller\VoucherController', $vc);
    }

    public function providerGetAction()
    {
    	return array(
    			array(array(array('voucher_code' => '12345'), array('voucher_code' => '54321')), '{"_links":{"self":{"href":"\/vouchers"}},"_embedded":{"vouchers":[{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","voucher_code":"12345","description":""},{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","voucher_code":"54321","description":""}]},"count":2}'),
    			array(array(array('voucher_code' => 'asdfg'), array('voucher_code' => 'gfdsa')), '{"_links":{"self":{"href":"\/vouchers"}},"_embedded":{"vouchers":[{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","voucher_code":"asdfg","description":""},{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","voucher_code":"gfdsa","description":""}]},"count":2}'),
    		);
    }

    /**
     * test the index(get) action
     *
     * @dataProvider providerGetAction
     *
     * @return void
     * @author
     **/
    public function testGetAction($array, $expected)
    {
        $pb      = $this->getMock('\Symfony\Component\HttpFoundation\ParameterBag', array(), array(), '', false);

        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request', array('getPathInfo'), array(), '', false);
        $request->expects($this->any())
                ->method('getPathInfo')
                ->will($this->returnValue('/vouchers'));

        $request->attributes = $pb;

        $app     = $this->getMock('\Silex\Application', array(), array(), '', false);

    	$vf = $this->getMock('\DVO\Entity\Voucher\VoucherFactory', array('getVouchers'), array(), '', false);

    	$voucher = $this->getMock('\DVO\Entity\Voucher', array('getId', 'getVoucherCode'));
    	$voucher->expects($this->any())
    	        ->method('getId')
    	        ->will($this->returnValue(''));

    	$voucher->expects($this->once())
    	        ->method('getVoucherCode')
    	        ->will($this->returnValue($array[0]['voucher_code']));

    	$voucher2 = $this->getMock('\DVO\Entity\Voucher', array('getId', 'getVoucherCode'));
    	$voucher2->expects($this->any())
    	         ->method('getId')
    	         ->will($this->returnValue(''));

    	$voucher2->expects($this->once())
    	         ->method('getVoucherCode')
    	         ->will($this->returnValue($array[1]['voucher_code']));

    	$vf->expects($this->once())
    	   ->method('getVouchers')
    	   ->will($this->returnValue(array($voucher, $voucher2)));

        $vc = new \DVO\Controller\VoucherController($vf);
        $response = $vc->indexJsonAction($request, $app);

        $this->assertEquals($expected, $response->getContent());
    }
}