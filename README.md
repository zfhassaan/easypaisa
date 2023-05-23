<!--suppress ALL -->
<p align="center">
  <img src="logo.png" alt="EASYPAISA Payment Gateway" width="150"/><br/>
  <!-- <h3 align="center">Payfast</h3> -->
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zfhassaan/easypaisa.svg?style=flat-square)](https://packagist.org/packages/zfhassaan/easypaisa)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/zfhassaan/easypaisa.svg?style=flat-square)](https://packagist.org/packages/zfhassaan/easypaisa)


## Disclaimer 
This is unofficial Easypaisa API Payment Gateway. This repository  is only created to help developers in streamlining the integration process. This Package only processes direct checkout process. There's no direct checkout process enabled yet.


# Installation
To install the EasyPaisa package, follow these steps:

Install the package via Composer by running the following command:

```bash
composer require vendor-name/easypaisa-package
```
Publish the package configuration file by running the following command:

```php 
php artisan vendor:publish --tag=easypaisa-config
```

Update the `.env` file with the required configuration values:

```bash
EASYPAISA_MODE=sandbox
EASYPAISA_SANDBOX_URL=
EASYPAISA_PROD_URL=
EASYPAISA_TYPE=direct
EASYPAISA_SANDBOX_USERNAME=
EASYPAISA_SANDBOX_PASSWORD=
EASYPAISA_SANDBOX_STOREID=
EASYPAISA_PRODUCTION_USERNAME=
EASYPAISA_PRODUCTION_PASSWORD=
EASYPAISA_PRODUCTION_STOREID=
EASYPAISA_PAYMENT_TYPE=MA
EASYPAISA_SANDBOX_HASHKEY=
EASYPAISA_PRODUCTION_HASHKEY=
EASYPAISA_CALLBACK_URL=
EASYPAISA_HOSTED_CHECKOUT=
```

# Usage

## Direct Checkout
To perform a direct checkout using the EasyPaisa payment gateway, use the following code:

```php 
try {
    $easypaisa = new Easypaisa;
    $response = $easypaisa->sendRequest($request->all());
    $responseCode = $response['responseCode'];
    $responseDesc = $response['responseDesc'];

    if ($responseCode != '0000') {
        return response()->json(['status' => false, 'message' => $responseDesc], Response::HTTP_NOT_ACCEPTABLE);
    }

    $result = [
        'status_code' => '00',
        'status_msg' => $response['responseDesc'],
        'transaction_id' => $response['transactionId'],
        'code' => $response['responseCode'],
        'message' => $response['responseDesc'],
        'basket_id' => strip_tags($request['orderId'])
    ];
    return response()->json($result);
} catch (\Exception $e) {
    return response()->json(['status' => false, 'message' => 'Error Processing Your Request.'], 500);
}
```

The `sendRequest()` method of the Easypaisa class processes the payment request. It takes an array of request data as input and returns the response from the EasyPaisa payment gateway.

`Package File: Easypaisa.php`
The sendRequest() method is defined in the Easypaisa package file. It processes the payment request by sending the data to the EasyPaisa API.

```php
$credentials = $this->getCredentials();

$data = [
    'orderId' => strip_tags($request['orderId']),
    'storeId' => $this->getStoreId(),
    'transactionAmount' => strip_tags($request['amount']),
    'transactionType' => 'MA',
    'mobileAccountNo' => strip_tags($request['mobileAccountNo']),
    'emailAddress' => strip_tags($request['emailAddress'])
];
$response = Http::timeout(60)->withHeaders([
    'credentials' => $credentials,
    'Content-Type' => 'application/json'
])->post($this->getApiUrl(), $data);

$result = $response->json();

return $result;
```

The getCredentials() method retrieves the credentials based on the configuration settings
