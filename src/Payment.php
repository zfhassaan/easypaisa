<?php

namespace zfhassaan\Easypaisa;

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
    protected string $storeId; // Store ID depending on Sandbox or Production
    protected string $type; // Hosted or Direct Checkout
    protected string $username; // Username
    protected string $password; // Password
    protected string $hashKey; // HashKey from Sandbox or Production
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
    public function initConfig(): void
    {
        $this->api_mode = config('easypaisa.mode');
        if($this->api_mode = 'sandbox' ) {
            $this->setApiUrl(config('sandbox_url'));
            $this->setUserName(config('sandbox_username'));
            $this->setPassword(config('sandbox_password'));
            $this->setStoreId(config('sandbox_storeid'));
            $this->setHashKey(config('sandbox_hashkey'));
        } else {
            $this->setApiUrl(config('prod_url'));
            $this->setUserName(config('prod_username'));
            $this->setPassword(config('prod_username'));
            $this->setStoreId(config('prod_storeid'));
            $this->setHashKey(config('prod_hashkey'));
        }
        $this->setCallbackUrl(config('callback'));
        $this->setType(config('type'));
        $this->currentDate = Carbon::now('Asia/Karachi');
        $this->expiryDate = $this->currentDate->format('Ymd His');
        $this->timestamp = $this->currentDate->format('Y-m-d\TH:i:s');
    }

    protected function crypt($data)
    {
        $cipher = 'aes-128-ecb';
        $crypttext = openssl_encrypt($data,$cipher,$this->getHashKey(),OPENSSL_RAW_DATA);
        return $this->setHashKey(base64_encode($crypttext));
    }

    protected function gethashRequest()
    {
        $params = [
            'amount'=> $this->getAmount(),
            'orderRefNum' => $this->getOrderId(),
            'paymentMethod'=>'InitialRequest',
            'postBackURL'=>$this->getCallbackUrl(),
            'storeId'=>$this->getStoreId(),
            'timestamp'=>$this->getTimestamp(),
        ];

        $query = http_build_query($params);
        return $this->crypt($query);

    }
    protected function getCheckoutUrl()
    {
        $params = [
            'storeId'=> $this->getStoreId(),
            'orderId'=> $this->getOrderId(),
            'transactionAmount'=> $this->getAmount(),
            'mobileAccountNo'=> $this->getMobileAccount(),
            'emailAddress'=>$this->getEmailAddress(),
            'transactionType'=>$this->gettype(),
            'tokenExpiry'=> '',
            'bankIdentificationNumber'=> '',
            'encryptedHashRequest'=> urlencode($this->getHashKey()),
            'merchantPaymentMethod'=>'',
            'postBackURL'=>$this->getCallbackUrl(),
            'signature' => ''
        ];

        $checkoutUrl = $this->setCheckoutURL(http_build_query($params));
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
    protected function setApiMode(string $api_mode): void
    {
        $this->api_mode = $api_mode;
    }

    protected function getApiMode(): string
    {
        return $this->api_mode;
    }

    // Setter and Getter for $apiUrl
    protected function setApiUrl(string $apiUrl): void
    {
        $this->apiUrl = $apiUrl;
    }

    protected function getApiUrl(): string
    {
        return $this->apiUrl;
    }


    // Setter and Getter for $storeId
    protected function setStoreId(string $storeId): void
    {
        $this->storeId = $storeId;
    }

    protected function getStoreId(): string
    {
        return $this->storeId;
    }

    // Setter and Getter for $type
    protected function setType(string $type): void
    {
        $this->type = $type;
    }

    protected function getType(): string
    {
        return $this->type;
    }

    // Setter and Getter for $username
    protected function setUserName(string $username): void
    {
        $this->username = $username;
    }

    protected function getUsername(): string
    {
        return $this->username;
    }

    // Setter and Getter for $password
    protected function setPassword(string $password): void
    {
        $this->password = $password;
    }

    protected function getPassword(): string
    {
        return $this->password;
    }

    // Setter and Getter for $hashKey
    protected function setHashKey(string $hashKey):void
    {
        $this->hashKey = $hashKey;
    }

    protected function getHashKey(): string
    {
        return $this->hashKey;
    }

    // Setter and Getter for $callbackUrl
    protected function setCallbackUrl(string $callbackUrl): void
    {
        $this->callbackUrl = $callbackUrl;
    }

    protected function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    // Setter and Getter for $orderId
    protected function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    protected function getOrderId(): string
    {
        return $this->orderId;
    }

    // Setter and Getter for $amount
    protected function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    protected function getAmount(): string
    {
        return $this->amount;
    }

    // Setter and Getter for $mobileAccount
    protected function setMobileAccount(string $mobileAccount): void
    {
        $this->mobileAccount = $mobileAccount;
    }

    protected function getMobileAccount(): string
    {
        return $this->mobileAccount;
    }

    // Setter and Getter for $emailAddress
    protected function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    protected function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}