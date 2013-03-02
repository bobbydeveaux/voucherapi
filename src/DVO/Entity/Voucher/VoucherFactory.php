<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DVO\Entity\Voucher;

use DVO\Cache;

class VoucherFactory
{
    protected $gateway;
    protected $cache;

    /**
     * VoucherFactory constructor.
     *
     * @param VoucherGateway $gateway The voucher gateway.
     * @param Cache          $cache   The cache.
     */
    public function __construct(VoucherGateway $gateway, Cache $cache)
    {
        $this->gateway = $gateway;
        $this->cache   = $cache;
    }

    /**
     * Get the gateway!.
     *
     * @return \DVO\Entity\Voucher\VoucherGateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Creates the Voucher.
     *
     * @return \DVO\Entity\Voucher
     */
    public function create()
    {
        return new \DVO\Entity\Voucher;
    }

    /**
     * Gets the vouchers.
     *
     * @param integer $voucherId The voucher ID from the controller.
     *
     * @return array
     */
    public function getVouchers($voucherId)
    {
        $vouchers = $this->gateway->getVouchers($voucherId);
        /* @codingStandardsIgnoreStart */
        $vouchers = array_filter(array_map(function($vid, $voucher) use ($voucherId) {
            // make sure that we only return the voucher we want.
            if (false === empty($voucherId) && $voucherId !== $vid) {
                return null;
            }

            $vc = $this->create();
            foreach ($voucher as $key => $value) {
                $vc->$key = $value;
            }

            $vc->id = $vid;
            return $vc;
        }, array_keys($vouchers), $vouchers));
        /* @codingStandardsIgnoreEnd */

        return $vouchers;
    }
}
