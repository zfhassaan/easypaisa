<?php

namespace Zfhassaan\Easypaisa;

use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class Easypaisa extends Payment
{
    /**
     * Send Direct Checkout Request
     *
     * @return Response|Application|ResponseFactory
     */
    public function sendRequest($request)
    {

        $credentials = $this->getCredentials();

        $data = [
            'orderId'=> strip_tags($request['orderId']),
            'storeId' => $this->getStoreId(),
            'transactionAmount'=> strip_tags($request['transactionAmount']),
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

    /**
     * Send Hosted Checkout URL Request
     */
    public function sendHostedRequest($request)
    {
        $data['amount'] = strip_tags($request['amount']);  //Last two digits will be considered as Decimal
        $data['orderRefNum'] = strip_tags($request['orderRefNum']); // You can customize it (only Max 20 Alpha-Numeric characters)
        $data['paymentMethod'] = 'InitialRequest';
        $data['postBackURL'] = $this->getCallbackUrl();
        $data['storeId'] = $this->getStoreId();
        $data['timeStamp'] = $this->getTimestamp();
        $hashk = $this->gethashRequest($data);
        $data['encryptedHashRequest'] = $hashk;
        $data['mobileAccountNo'] = '';
        return $this->getCheckoutUrl($data);
    }
}
