<?php

namespace Omnipay\Merchantware;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Merchantware';
    }

    public function getDefaultParameters()
    {
        return [
            'merchantName' => '',
            'merchantSiteId' => '',
            'merchantKey' => '',
        ];
    }

    public function createCard(array $options = [])
    {
        return $this->createRequest('\Omnipay\Merchantware\Message\CreateCardRequest', $options);
    }

    public function purchase(array $options = [])
    {
        return $this->createRequest('\Omnipay\Merchantware\Message\PurchaseRequest', $options);
    }

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
}
