<?php

namespace Zfhassaan\Easypaisa;

use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;

class Payment
{
    protected string $api_mode; // Sandbox / Production
    protected string $apiUrl; // API Url set for the value
    private ?string $storeId = null; // Store ID depending on Sandbox or Production
    protected string $type; // Hosted or Direct Checkout
    protected string $username; // Username
    protected string $password; // Password
    private ?string $hashKey = null;// HashKey from Sandbox or Production
    protected string $callbackUrl; // success/failure message
    protected string $checkoutUrl; // Checkout url for hosted checkout.
    protected string $orderId; // OrderId;
    protected string $amount; // Order Amount
    protected string $mobileAccount; // Account or Mobile Account
    protected string $emailAddress; // Email address for the customer.

    protected $currentDate; // Current Date
    protected $expiryDate; // Expiry Date
    protected $timestamp; // timestamp


    /**
     * Constructor for Easypaisa Payment Gateway
     * @return void
     */
    public function __construct()
    {
        $this->initConfig();
    }

    /**
     * Initialize Config Values
     * @return void
     */
    public function initConfig()
    {

        // dd(config('easypaisa.easypaisa.sandbox_url'));
        $this->api_mode = config('easypaisa.mode');
        $this->setCallbackUrl(config('easypaisa.callback'));
        $this->setType(config('easypaisa.type'));

        if ($this->getType() === 'hosted') {
            $this->setApiUrl(config('easypaisa.hosted'));
        } else {
            if ($this->api_mode === 'sandbox') {
                $this->setApiUrl(config('easypaisa.sandbox_url'));
                $this->setUserName(config('easypaisa.sandbox_username'));
                $this->setPassword(config('easypaisa.sandbox_password'));
                $this->setStoreId(config('easypaisa.sandbox_storeid'));
                $this->setHashKey(config('easypaisa.sandbox_hashkey'));
            } else {
                $this->setApiUrl(config('easypaisa.prod_url'));
                $this->setUserName(config('easypaisa.prod_username'));
                $this->setPassword(config('easypaisa.prod_username'));
                $this->setStoreId(config('easypaisa.prod_storeid'));
                $this->setHashKey(config('easypaisa.prod_hashkey'));
            }
        }

        $this->currentDate = now()->setTimezone('Asia/Karachi');
        $this->expiryDate = $this->currentDate->format('Ymd His');
        $this->timestamp = $this->currentDate->format('Y-m-d\TH:i:s');

    }

    protected function gethashRequest()
    {
        $params = [
            'amount'=> $this->getAmount(),
            'orderRefNum' => $this->getOrderId(),
            'paymentMethod'=>'InitialRequest',
            'postBackURL'=>urldecode($this->getCallbackUrl()),
            'storeId'=>$this->getStoreId(),
            'timestamp'=>$this->getTimestamp(),
        ];

        $query = http_build_query($params);
        $query = str_replace(['%3A', '%2F'], [':', '/'], $query );

        $cipher = 'aes-128-ecb';
        $crypttext = openssl_encrypt($query,$cipher,$this->getHashKey(),OPENSSL_RAW_DATA);
        $crypttext = $this->setHashKey(base64_encode($crypttext));

        return $this->getHashKey();

    }
    protected function getCheckoutUrl()
    {
        $params = [
            'storeId'=> $this->getStoreId(),
            'orderId'=> $this->getOrderId(),
            'transactionAmount'=> $this->getAmount(),

            'transactionType'=>$this->gettype(),
            'tokenExpiry'=> '',
            'bankIdentificationNumber'=> '',
            'encryptedHashRequest'=> urlencode($this->getHashKey()),
            'merchantPaymentMethod'=>'',
            'postBackURL'=>$this->getCallbackUrl(),
            'signature' => ''
        ];

        // 'mobileAccountNo'=> $this->getMobileAccount(),

        if(isset($this->mobileAccount)) {
            $params['mobileAccountNo'] = $this->getMobileAccount();
        }

        if(isset($this->emailAddress)) {
            $params['emailAddress'] = $this->getEmailAddress();
        }
        $query = http_build_query($params);
        $query = str_replace(['%3A', '%2F'], [':', '/'], $query );
        dd($this->getApiUrl().$query);
        $checkoutUrl = $this->setCheckoutURL($query);
        return $checkoutUrl;
    }
    // Get Encrypted Credentials
    protected function getCredentials() {
        return base64_encode($this->getUsername().':'.$this->getPassword());
    }

    // checkout URL
    protected function setCheckoutURL(string $checkoutUrl):void
    {
        $this->checkoutUrl = $checkoutUrl;
    }

    // Get Current Date
    protected function getCurrentDate():Date
    {
        return $this->currentDate;
    }

    // Get Timestamp
    protected function getTimestamp()
    {
        return $this->timestamp;
    }

    // Get Expiry Date
    protected function getExpiryDate() {
        return $this->expiryDate;
    }
    // Setter and Getter for $api_mode
    protected function setApiMode(string $api_mode)
    {
        $this->api_mode = $api_mode;
    }

    protected function getApiMode()
    {
        return $this->api_mode;
    }

    // Setter and Getter for $apiUrl
    protected function setApiUrl(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    protected function getApiUrl()
    {
        return $this->apiUrl;
    }


    // Setter and Getter for $storeId
    protected function setStoreId(string $storeId)
    {
        $this->storeId = $storeId;
    }

    protected function getStoreId()
    {
        return $this->storeId;
    }

    // Setter and Getter for $type
    protected function setType(string $type)
    {
        $this->type = $type;
    }

    protected function getType()
    {
        return $this->type;
    }

    // Setter and Getter for $username
    protected function setUserName(string $username)
    {
        $this->username = $username;
    }

    protected function getUsername()
    {
        return $this->username;
    }

    // Setter and Getter for $password
    protected function setPassword(string $password)
    {
        $this->password = $password;
    }

    protected function getPassword()
    {
        return $this->password;
    }

    // Setter and Getter for $hashKey
    protected function setHashKey(string $hashKey):void
    {
        $this->hashKey = $hashKey;
    }

    protected function getHashKey()
    {
        return $this->hashKey;
    }

    // Setter and Getter for $callbackUrl
    protected function setCallbackUrl(string $callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    protected function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    // Setter and Getter for $orderId
    protected function setOrderId(string $orderId)
    {
        $this->orderId = $orderId;
    }

    protected function getOrderId()
    {
        return $this->orderId;
    }

    // Setter and Getter for $amount
    protected function setAmount(string $amount)
    {
        $this->amount = $amount;
    }

    protected function getAmount()
    {
        return $this->amount;
    }

    // Setter and Getter for $mobileAccount
    protected function setMobileAccount(string $mobileAccount)
    {
        $this->mobileAccount = $mobileAccount;
    }

    protected function getMobileAccount()
    {
        return $this->mobileAccount;
    }

    // Setter and Getter for $emailAddress
    protected function setEmailAddress(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    protected function getEmailAddress()
    {
        return $this->emailAddress;
    }
}
