<?php

namespace Omnipay\Gopay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Gopay\Api\GopayConfig;
use Omnipay\Gopay\Api\GopaySoap;

class PurchaseRequest extends AbstractSoapRequest
{
    public function setGoId($value)
    {
        return $this->setParameter('goId', $value);
    }

    public function setSecureKey($value)
    {
        return $this->setParameter('secureKey', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return GopaySoap::createPaymentCommand(
            $this->getGoId(),
            $this->getDescription(),
            $this->getAmountInteger(),
            $this->getCurrency(),
            $this->getTransactionId(),
            $this->getReturnUrl(),
            $this->getCancelUrl(),
            false,
            false,
            null,
            null,
            null,
            null,
            '',
            $this->getSecureKey(),
            $this->getCard()->getFirstName(),
            $this->getCard()->getLastName(),
            $this->getCard()->getBillingCity(),
            $this->getCard()->getBillingAddress1(),
            $this->getCard()->getBillingPostcode(),
            null, // TODO $this->getCard()->getBillingCountry() returns user input, we need three-letter country code
            $this->getCard()->getEmail(),
            $this->getCard()->getPhone(),
            null, null, null, null, null
        );
    }

    public function getGoId()
    {
        return $this->getParameter('goId');
    }

    public function getSecureKey()
    {
        return $this->getParameter('secureKey');
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $paymentStatus = $this->soapClient->createPayment(array('paymentCommand' => $data));
        return $this->response = new PurchaseResponse($this, $paymentStatus);
    }
}
