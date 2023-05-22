<?php

namespace zfhassaan\Easypaisa;

use zfhassaan\easypaisa\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Easypaisa extends Payment
{
    /**
     * Send Request
     * @return Response|Application|ResponseFactory
     */
    public function sendRequest()
    {

    }

    /**
     * Send Hosted Checkout Request
     * @return Response|Application|ResponseFactory
     */
    public function HostedCheckout($request)
    {
        if(
            isset($request->amount) &&
            isset($request->orderId)

        ) {
            $this->setAmount($request->amount);
            $this->setOrderId($request->orderId);
            $hashRequest = $this->gethashRequest();
            $checkoutURL = $this->getCheckoutUrl();
            dd($checkoutURL);
        }

    }
}
