<?php

namespace Omnipay\Gopay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Gopay\Api\GopayConfig;
use Omnipay\Gopay\Api\GopayHelper;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return $this->getData()->result == GopayHelper::CALL_COMPLETED
        && $this->getData()->sessionState == GopayHelper::CREATED
        && $this->getData()->paymentSessionId > 0;
    }

    public function getMessage()
    {
        return $this->getData()->resultDescription;
    }


    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $url = $this->getRequest()->getTestMode() ? GopayConfig::TEST_FULL_URL : GopayConfig::TEST_WSDL_URL;

        /** @noinspection PhpUndefinedMethodInspection */
        $goId = $this->getRequest()->getGoId();
        $paymentSessionId = $this->getData()->paymentSessionId;

        /** @noinspection PhpUndefinedMethodInspection */
        $secureKey = $this->getRequest()->getSecureKey();

        return $url . '?' . http_build_query(array(
            'sessionInfo.targetGoId' => $goId,
            'sessionInfo.paymentSessionId' => $paymentSessionId,
            'sessionInfo.encryptedSignature' => GopayHelper::getPaymentSessionSignature(
                $goId, $paymentSessionId, $secureKey)));
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        return null;
    }
}
