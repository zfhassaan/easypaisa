<?php

namespace Zfhassaan\Easypaisa;

use Zfhassaan\Easypaisa\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;

class Easypaisa extends Payment
{
    /**
     * Send Request
     *
     * @return Response|Application|ResponseFactory
     */
    public function sendRequest($request)
    {
        $credentials = $this->getCredentials();
        $data = [
            'orderId'=> strip_tags($request['orderId']),
            'storeId' => $this->getStoreId(),
            'transactionAmount'=> strip_tags($request['amount']),
            'transactionType'=> 'MA',
            'mobileAccountNo'=> strip_tags($request['mobileAccountNo']),
            'emailAddress'=> strip_tags($request['emailAddress'])
        ];
        $response = Http::timeout(60)->withHeaders([
            'credentials'=>$credentials,
            'Content-Type'=> 'application/json'
            ])->post($this->getApiUrl(),$data);

        $result = $response->json();

        return $result;
    }
}
