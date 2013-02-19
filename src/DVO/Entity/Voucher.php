<?php

namespace DVO\Entity;

/**
 * Voucher
 *
 * @package default
 * @author 
 **/
class Voucher extends EntityAbstract
{
    public function __construct()
    {
        $this->_data = array(
            'id'   => '',
            'code' => ''
            );
    }
}