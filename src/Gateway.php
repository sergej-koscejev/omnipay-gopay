<?php

namespace Omnipay\Gopay;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway {

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

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Gopay\Message\PurchaseRequest', $parameters);
    }
}
