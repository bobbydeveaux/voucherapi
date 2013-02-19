<?php

namespace DVO\Controller;

use DVO\Entity\Voucher;
use DVO\Entity\Voucher\VoucherFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class VoucherController
{
    protected $factory;

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
        $this->factory = $factory;
    }

    public function indexJsonAction()
    {
        $vouchers = $this->factory->getVouchers();
        $vouchers = array_map(function($voucher) {
            $vc = array();
            $vc['code'] = $voucher->getCode();

            return $vc;
        }, $vouchers);
        return new JsonResponse($vouchers);
    }

    public function createJsonAction()
    {
    }

    public function updateJsonAction()
    {
    }

    public function deleteJsonAction()
    {
    }
}
