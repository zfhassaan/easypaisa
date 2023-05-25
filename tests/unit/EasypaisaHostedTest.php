<?php

namespace Tests\Feature;

use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Illuminate\Support\Str;
use Zfhassaan\Easypaisa\Easypaisa;

class EasypaisaHostedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testSendHostedRequest()
{
    // Arrange
    $requestData = [
        'amount' => 100,
        'orderRefNum' => 'ABC123',
    ];
    $easypaisa = new Easypaisa();

    // Act
    $result = $easypaisa->sendHostedRequest($requestData);

    // Assert
    $expectedUrl = config('easypaisa.hosted'); //Fetch Hosted Checkout URL from .env
    $this->assertTrue(Str::contains($result, $expectedUrl));
}
    /**
     * Test sending a hosted request with valid data.
     */
    public function testSendHostedRequestWithValidData()
    {
        // Arrange
        $requestData = [
            'amount' => 100,
            'orderRefNum' => 'ABC123',
        ];
        $easypaisa = new Easypaisa();

        // Act
        $result = $easypaisa->sendHostedRequest($requestData);

        // Assert
        $expectedUrl = 'https://easypay.easypaisa.com.pk/tpg/'; // Replace with your expected checkout URL
        $this->assertTrue(Str::contains($result, $expectedUrl));
    }

    /**
     * Test sending a hosted request with missing amount.
     */
    public function testSendHostedRequestWithMissingAmount()
    {
        // Arrange
        $requestData = [
            'orderRefNum' => 'ABC123',
        ];
        $easypaisa = new Easypaisa();

        // Act & Assert
        $result = $easypaisa->sendHostedRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class,$result);
    }

    /**
     * Test sending a hosted request with missing orderRefNum.
     */
    public function testSendHostedRequestWithMissingOrderRefNum()
    {
        $requestData = [
            'amount' => 100,
        ];
        if(empty($requestData['orderRefNum']))
        {
            $easypaisa = new Easypaisa();
            $result = $easypaisa->sendHostedRequest($requestData);
            $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class,$result);
        }


    }

    /**
     * Test sending a hosted request with invalid amount.
     */
    public function testSendHostedRequestWithInvalidAmount()
    {
        // Arrange
        $requestData = [
            'amount' => -1,
            'orderRefNum' => 'ABC123',
        ];
        $easypaisa = new Easypaisa();

        // Act & Assert
        $result = $easypaisa->sendHostedRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class,$result);
    }


    public function testSendHostedRequestWithNegativeAmount()
    {
        // Arrange
        $requestData = [
            'amount' => -1,
            'orderRefNum' => 'ABC123',
        ];
        $easypaisa = new Easypaisa();

        // Act & Assert
        $result = $easypaisa->sendHostedRequest($requestData);
        $this->assertInstanceOf(JsonResponse::class,$result);
    }
}
