<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DVO\Controller;

use DVO\Entity\Voucher;
use DVO\Entity\Voucher\VoucherFactory;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VoucherController
{
    protected $factory;

    /**
     * VoucherController constructor.
     *
     * @param VoucherFactory $factory The voucher factory.
     */
    public function __construct(VoucherFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Handles the HTTP GET.
     *
     * @param Request     $request The request.
     * @param Application $app     The app.
     *
     * @return JsonResponse
     */
    public function indexJsonAction(Request $request, Application $app)
    {
        $voucherId = (int) $request->attributes->get('id');
        $vouchers  = $this->factory->getVouchers($voucherId);
        /* @codingStandardsIgnoreStart */
        $vouchers = array_map(function($voucher) use ($request) {
            $vc                           = array();
            $vc['_links']['self']['href'] = $request->getPathInfo() . '/' . $voucher->getId();
            $vc['id']                     = $voucher->getId();
            $vc['voucher_code']           = $voucher->getVoucherCode();
            $vc['description']            = $voucher->getDescription();
            return $vc;
        }, $vouchers);
        /* @codingStandardsIgnoreEnd */

        $response['_links']['self']['href'] = $request->getPathInfo();
        $response['_embedded']['vouchers']  = $vouchers;
        $response['count']                  = count($vouchers);

        return new JsonResponse($response, 200, array('Cache-Control' => 's-maxage=10, public'));
    }

    /**
     * Handles the HTTP POST.
     *
     * @param Request     $request The request.
     * @param Application $app     The app.
     *
     * @return JsonResponse
     */
    public function createJsonAction(Request $request, Application $app)
    {
        $data    = json_decode($request->getContent(), true);
        $voucher = $this->factory->create();

        foreach ($data as $key => $value) {
            $voucher->$key = $value;
        }

        if (false === $this->factory->getGateway()->insertVoucher($voucher)) {
            return $this->errorAction($request, $app);
        }

        return $this->indexJsonAction($request, $app);
    }

    /**
     * Handles any errors.
     *
     * @param Request     $request The request.
     * @param Application $app     The app.
     *
     * @return JsonResponse
     */
    public function errorAction(Request $request, Application $app)
    {
        $response = array('status' => array('code' => 400, 'message' => Response::$statusTexts[400]));
        return new JsonResponse($response);
    }
}
