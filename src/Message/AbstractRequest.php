<?php

namespace Omnipay\Merchantware\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $liveEndpoint = 'https://ps1.merchantware.net/Merchantware/ws/RetailTransaction/v46/Credit.asmx';
    protected $testEndpoint = 'https://ps1.merchantware.net/Merchantware/ws/RetailTransaction/v46/Credit.asmx';

    public function getMerchantName()
    {
        return $this->getParameter('merchantName');
    }

    public function setMerchantName($value)
    {
        return $this->setParameter('merchantName', $value);
    }

    public function getMerchantSiteId()
    {
        return $this->getParameter('merchantSiteId');
    }

    public function setMerchantSiteId($value)
    {
        return $this->setParameter('merchantSiteId', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function createResponse($response)
    {
        $factory = $this->getResponseFactory();

        return $this->response = (new $factory)->handle($this, $response);
    }

    abstract protected function getResponseFactory();

    protected function getBaseData()
    {
        return [
            'merchantName' => $this->getMerchantName(),
            'merchantSiteId' => $this->getMerchantSiteId(),
            'merchantKey' => $this->getMerchantKey(),
        ];
    }
}
