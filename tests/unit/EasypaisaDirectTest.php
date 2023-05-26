<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Zfhassaan\Easypaisa\Easypaisa;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;

class EasypaisaDirectTest extends TestCase
{
    /**
     * Test sending a Direct Request with valid data.
     */
    public function testSendDirectRequestWithValidData()
    {
        // Data
        $requestData = [
            'orderId'=> '548828745',
            'transactionAmount' => 10,
            'mobileAccountNo' => '03450975945',
            'emailAddress' => 'abc@gmail.com'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $expectedUrl = 'https://easypaystg.easypaisa.com.pk';
        $this->assertTrue(Str::contains($result, $expectedUrl));
    }
    /**
     * Test Sending a Direct Request with missing orderId
     */
    public function testSendDirectRequestWithMissingOrderid()
    {
        // Data
        $requestData = [
            'transactionAmount' => 10,
            'mobileAccountNo' => '03450975945',
            'emailAddress' => 'abc@gmail.com'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
    /**
     * Test Sending a Direct Request with missing transectionAmount
     */
    public function testSendDirectRequestWithMissingTransactionAmount()
    {
        // Data
        $requestData = [
            'orderId'=> '548828745',
            'mobileAccountNo' => '03450975945',
            'emailAddress' => 'abc@gmail.com'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
    /**
     * Test Sending a Direct Request with missing mobileAccountNo
     */
    public function testSendDirectRequestWithMissingMobileAccountNo()
    {
        // Data
        $requestData = [
            'orderId'=> '548828745',
            'transactionAmount' => 10,
            'emailAddress' => 'abc@gmail.com'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
    /**
     * Test Sending a Direct Request with missing emailAddress
     */
    public function testSendDirectRequestWithMissingEmailAddress()
    {
        // Data
        $requestData = [
            'orderId'=> '548828745',
            'transactionAmount' => 10,
            'mobileAccountNo' => '03450975945'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
    /**
     * Test Sending a Direct Request with invalid transactionAmount
     */
    public function testSendDirectRequestWithInvalidTransactionAmount()
    {
        // Data
        $requestData = [
            'orderId'=> '548828745',
            'transactionAmount' => -1,
            'mobileAccountNo' => '03450975945',
            'emailAddress' => 'abc@gmail.com'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
    public function testSendDirectRequestWithInvalidEmailAddress()
    {
        // Data
        $requestData = [
            'orderId'=> '548828745',
            'transactionAmount' => 10,
            'mobileAccountNo' => '03450975945',
            'emailAddress' => 'abcgmail.com'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
    public function testSendDirectRequestWithInvalidData()
    {
        // Data
        $requestData = [
            'orderId'=> '',
            'transactionAmount' => -1,
            'mobileAccountNo' => '',
            'emailAddress' => 'abcgmail.com'
        ];
        $easypaisa = new Easypaisa();
        // Action
        $result = $easypaisa->sendRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}
