<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DVO\Entity;

/**
 * Voucher
 *
 * @package default
 * @author
 **/
class Voucher extends EntityAbstract
{
    /**
     * Handles the HTTP GET.
     */
    public function __construct()
    {
        $this->data = array(
            'id'   => '',
            'code' => '',
            'description' => ''
            );
    }

    /**
     * Get the ID.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * Get the Code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->data['code'];
    }

    /**
     * Get the Description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->data['description'];
    }
}
