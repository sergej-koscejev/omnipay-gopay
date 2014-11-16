<?php

namespace Omnipay\Gopay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Gopay\Api\GopayHelper;
use Omnipay\Tests\TestCase;
use stdClass;

class PurchaseResponseTest extends TestCase {

    const SECURE_KEY = '0123456789abcdef';

    public static function successfulResponseData($secureKey)
    {
        $data = new stdClass();
        $data->targetGoId = 12345;
        $data->productName = 'Product Description';
        $data->totalPrice = 1000;
        $data->currency = 'CZK';
        $data->orderNumber = '1234';
        $data->recurrentPayment = '';
        $data->parentPaymentSessionId = '';
        $data->preAuthorization = '';
        $data->result = GopayHelper::CALL_COMPLETED;
        $data->sessionState = GopayHelper::CREATED;
        $data->sessionSubState = '';
        $data->paymentChannel = '';
        $data->paymentSessionId = 11112222;

        $data->encryptedSignature = GopayHelper::encrypt(
            GopayHelper::hash(GopayHelper::concatPaymentStatus($data, $secureKey)),
            $secureKey);
        return $data;
    }

    public function testConstruct()
    {
        $data = self::successfulResponseData(self::SECURE_KEY);

        $response = $this->createResponseFromData($data);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
    }

    public function testInvalidSignature()
    {
        $this->setExpectedException('Omnipay\Common\Exception\InvalidResponseException');

        $data = self::successfulResponseData(self::SECURE_KEY);
        $data->encryptedSignature = '0123456789012345678901';

        $this->createResponseFromData($data);
    }

    /**
     * @param $data
     * @return PurchaseResponse
     */
    public function createResponseFromData($data)
    {
        $request = $this->getMockRequest();
        $request->shouldReceive('getParameters')->andReturn(array('secureKey' => self::SECURE_KEY));

        $response = new PurchaseResponse($request, $data);
        return $response;
    }
}
