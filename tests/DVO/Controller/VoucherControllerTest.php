<?php

/**
 * VoucherController Test
 *
 * @package DVO
 * @author 
 **/
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
    			array(array(array('code' => '12345'), array('code' => '54321')), '[{"code":"12345"},{"code":"54321"}]'),
    			array(array(array('code' => 'asdfg'), array('code' => 'gfdsa')), '[{"code":"asdfg"},{"code":"gfdsa"}]'),
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
        $response = $vc->indexJsonAction();

        $this->assertEquals($expected, $response->getContent());
    }
}