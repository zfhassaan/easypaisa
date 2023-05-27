<?php

namespace Zfhassaan\Easypaisa;

use DateTime;
use DateTimeZone;
use Exception;
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
        try{
            $email = $request['emailAddress'];
            if (intval($request['transactionAmount']) < 0 || empty($request['orderId']) || empty($request['mobileAccountNo'])) {
                return response()->json(['status' => false, 'message' => 'Missing Arguments.'], Response::HTTP_NOT_ACCEPTABLE);
            }
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['status' => false, 'message' => 'Email format is incorrect'], Response::HTTP_CONFLICT);
            }
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
        catch(\Exception $e)
        {
            return response()->json(['status'=> false, 'message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Send Hosted Checkout URL Request
     */
    public function sendHostedRequest($request)
    {
        try{
            if(intval($request['amount']) < 0 || empty($request['orderRefNum'])) {
                return response()->json(['status' => false,'message' => 'Invalid Arguments Passed.'],Response::HTTP_CONFLICT);
            }

            $data['amount'] = strip_tags($request['amount']);
            $data['orderRefNum'] = strip_tags($request['orderRefNum']);
            $data['paymentMethod'] = 'InitialRequest';
            $data['postBackURL'] = $this->getCallbackUrl();
            $data['storeId'] = $this->getStoreId();
            $data['timeStamp'] = $this->getTimestamp();
            $hashk = $this->gethashRequest($data);
            $data['encryptedHashRequest'] = $hashk;
            $data['mobileAccountNo'] = '';
            return $this->getCheckoutUrl($data);
        } catch(\Exception $e)
        {
            return response()->json(['status' => false,'mesage' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}