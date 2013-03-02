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
    			array(array(array('code' => '12345'), array('code' => '54321')), '{"_links":{"self":{"href":"\/vouchers"}},"_embedded":{"vouchers":[{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","code":"12345","description":""},{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","code":"54321","description":""}]},"count":2}'),
    			array(array(array('code' => 'asdfg'), array('code' => 'gfdsa')), '{"_links":{"self":{"href":"\/vouchers"}},"_embedded":{"vouchers":[{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","code":"asdfg","description":""},{"_links":{"self":{"href":"\/vouchers\/"}},"id":"","code":"gfdsa","description":""}]},"count":2}'),
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

    	$voucher = $this->getMock('\DVO\Entity\Voucher', array('getId', 'getCode'));
    	$voucher->expects($this->any())
    	        ->method('getId')
    	        ->will($this->returnValue(''));

    	$voucher->expects($this->once())
    	        ->method('getCode')
    	        ->will($this->returnValue($array[0]['code']));

    	$voucher2 = $this->getMock('\DVO\Entity\Voucher', array('getId', 'getCode'));
    	$voucher2->expects($this->any())
    	         ->method('getId')
    	         ->will($this->returnValue(''));

    	$voucher2->expects($this->once())
    	         ->method('getCode')
    	         ->will($this->returnValue($array[1]['code']));

    	$vf->expects($this->once())
    	   ->method('getVouchers')
    	   ->will($this->returnValue(array($voucher, $voucher2)));

        $vc = new \DVO\Controller\VoucherController($vf);
        $response = $vc->indexJsonAction($request, $app);

        $this->assertEquals($expected, $response->getContent());
    }
}