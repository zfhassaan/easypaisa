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
        //
    }
}
