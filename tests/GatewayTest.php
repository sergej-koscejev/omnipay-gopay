<?php
namespace Omnipay\Gopay;

use Omnipay\Gopay\Message\PurchaseResponseTest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    private $secureKey = '9876543210abcdef';
    protected $gateway;
    protected $soapClient;
    protected $options;

    public function setUp()
    {
        parent::setUp();
        $this->soapClient = $this->getMockFromWsdl(__DIR__ . '/EPaymentServiceV2.wsdl');

        $this->gateway = new Gateway($this->soapClient);
        $this->gateway->setGoId('12345');
        $this->gateway->setSecureKey($this->secureKey);

        $this->options = array(
            'card' => $this->getValidCard()
        );
    }

    public function testPurchase()
    {
        $soapResponse = PurchaseResponseTest::successfulResponseData($this->secureKey);

        $this->soapClient->expects($this->once())->method('createPayment')
            ->with($this->anything())
            ->will($this->returnValue($soapResponse));

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isRedirect());
    }
}
