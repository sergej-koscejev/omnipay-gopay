<?php

namespace Omnipay\Gopay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Gopay\Api\GopayConfig;
use Omnipay\Gopay\Api\GopaySoap;

class Gateway extends AbstractGateway {

    /**
     * @var \SoapClient
     */
    private $soapClient;

    public function __construct($soapClient = null)
    {
        $this->soapClient = $soapClient;
        $this->initialize();
    }

    public function getName()
    {
        return 'GoPay';
    }

    public function getDefaultParameters()
    {
        return parent::getDefaultParameters() + array('testMode' => false, 'goId' => '', 'secureKey' => '');
    }

    public function getGoId()
    {
        return $this->getParameter('goId');
    }

    public function setGoId($value)
    {
        return $this->setParameter('goId', $value);
    }

    public function getSecureKey()
    {
        return $this->getParameter('secureKey');
    }

    public function setSecureKey($value)
    {
        return $this->setParameter('secureKey', $value);
    }

    protected function createRequest($class, array $parameters)
    {
        $obj = new $class($this->getSoapClient());

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    protected function getSoapClient()
    {
        if (is_null($this->soapClient))
        {
            $url = $this->getTestMode() ? GopayConfig::TEST_WSDL_URL : GopayConfig::PROD_WSDL_URL;
            $this->soapClient = GopaySoap::createSoapClient($url);
        }

        return $this->soapClient;
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Gopay\Message\PurchaseRequest', $parameters);
    }
}
