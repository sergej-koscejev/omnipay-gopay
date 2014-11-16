<?php
namespace Omnipay\Gopay;

use Omnipay\Gopay\Api\GopayHelper;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $gateway;
    protected $soapClient;
    protected $options;

    public function setUp()
    {
        parent::setUp();
        $this->soapClient = $this->getMockFromWsdl(__DIR__ . '/EPaymentServiceV2.wsdl');

        $this->gateway = new Gateway($this->soapClient);
        $this->gateway->setGoId('12345');
        $this->gateway->setSecureKey('98765');

        $this->options = array(
            'card' => $this->getValidCard()
        );
    }

    public function testPurchase()
    {
        $soapResponse = new \stdClass();
        $soapResponse->result = GopayHelper::CALL_COMPLETED;
        $soapResponse->sessionState = GopayHelper::CREATED;
        $soapResponse->paymentSessionId = 1234;

        $this->soapClient->expects($this->once())->method('createPayment')
            ->with($this->anything())
            ->will($this->returnValue($soapResponse));

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isRedirect());
    }
}
