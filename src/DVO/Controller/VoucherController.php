<?php

namespace DVO\Controller;

use DVO\Entity\Voucher;
use DVO\Entity\Voucher\VoucherFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class VoucherController
{
    protected $_factory;

    /**
     * VoucherController constructor
     *
     * @param VoucherFactory $factory The voucher factory
     * 
     * @return void
     * @author
     **/
    public function __construct(VoucherFactory $factory)
    {
        $this->_factory = $factory;
    }

    public function indexJsonAction()
    {
        $vouchers = $this->_factory->getVouchers();
        /* @codingStandardsIgnoreStart */
        $vouchers = array_map(function($voucher) {
            $vc = array();
            $vc['code'] = $voucher->getCode();
            $vc['description'] = $voucher->getDescription();
            return $vc;
        }, $vouchers);
        /* @codingStandardsIgnoreEnd */
        return new JsonResponse($vouchers);
    }

    public function createJsonAction()
    {
        $voucher = VoucherFactory::create();
        $voucher->code = 'KAJSD10';
        $voucher->description = 'some code innit';

        $this->_factory->getGateway()->insertVoucher($voucher);

        return $this->indexJsonAction();
    }

    public function updateJsonAction()
    {
    }

    public function deleteJsonAction()
    {
    }
}
