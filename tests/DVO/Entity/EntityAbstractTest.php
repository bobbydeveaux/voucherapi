<?php

class EntityAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testEntityVoucher()
    {
        $obj = new \DVO\Entity\Voucher;
        $this->assertInstanceOf('\DVO\Entity\EntityAbstract', $obj);
    }

    public function testEntityMagicFuncs()
    {
        $obj = new \DVO\Entity\Voucher;
        $obj->setCode('ASD123');

        $this->assertEquals($obj->getCode(), 'ASD123');
    }

    public function testGetData()
    {
        $obj = new \DVO\Entity\Voucher;
        $obj->setCode('ASD123');

        $data = $obj->getData();

        $this->assertEquals(array('id' =>'', 'code' => 'ASD123'), $data);
    }

    /**
     * @expectedException \DVO\Entity\Exception
     */
    public function testDefaultMagic()
    {
        $obj = new \DVO\Entity\Voucher;
        $var = $obj->placeholder();
    }

    /**
     * @expectedException \DVO\Entity\Exception
     */
    public function testMagicSetFuncsFail()
    {
        $obj = new \DVO\Entity\Voucher;
        $obj->something = 'test';
    }

    /**
     * @group entityTests
     * @expectedException \DVO\Entity\Exception
     */
    public function testMagicGetFuncsFail()
    {
        $obj = new \DVO\Entity\Voucher;
        $testing = $obj->something;
    }

}