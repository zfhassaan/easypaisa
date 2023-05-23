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
     * @return Response|Application|ResponseFactory
     */
    public function sendRequest($request)
    {
        $credentials = $this->getCredentials();
        $response = Http::timeout(60)->withHeaders(['credentials'=>$credentials])->post($this->getApiUrl(),$request);
        $result = $response->json();

        return $result;
    }
}