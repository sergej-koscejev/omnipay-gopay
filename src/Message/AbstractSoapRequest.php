<?php

namespace Omnipay\Gopay\Message;

use Omnipay\Common\Message\AbstractRequest;
use SoapClient;

/**
 * Abstract Request
 */
abstract class AbstractSoapRequest extends AbstractRequest
{
    /**
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * Create a new Request
     *
     * @param SoapClient $soapClient A SoapClient to make calls with
     */
    public function __construct(SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
        $this->initialize();
    }
}
