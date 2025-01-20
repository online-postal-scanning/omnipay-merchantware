<?php

namespace Omnipay\Merchantware\Message;

use Omnipay\Merchantware\Factory\PurchaseResponseFactory;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'cardReference');
        $baseData = $this->getBaseData();

        return sprintf(
            '<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
    <soap12:Body>
        <Sale xmlns="http://schemas.merchantwarehouse.com/merchantware/v46/">
            <Credentials>
                <MerchantName>%s</MerchantName>
                <MerchantSiteId>%s</MerchantSiteId>
                <MerchantKey>%s</MerchantKey>
            </Credentials>
            <PaymentData>
                <Source>VAULT</Source>
                <VaultToken>%s</VaultToken>
            </PaymentData>
            <Request>
                <Amount>%s</Amount>
                <CashbackAmount>0.00</CashbackAmount>
                <SurchargeAmount>0.00</SurchargeAmount>
                <TaxAmount>0.00</TaxAmount>
                <InvoiceNumber>%s</InvoiceNumber>
                <RegisterNumber>%s</RegisterNumber>
                <MerchantTransactionId>%s</MerchantTransactionId>
                <CardAcceptorTerminalId>%s</CardAcceptorTerminalId>
            </Request>
        </Sale>
    </soap12:Body>
</soap12:Envelope>',
            $baseData['merchantName'],
            $baseData['merchantSiteId'],
            $baseData['merchantKey'],
            $this->getCardReference(),
            $this->getAmount(),
            $this->getInvoiceNumber() ?? '',
            $this->getRegisterNumber() ?? '',
            $this->getTransactionId() ?? '',
            $this->getTerminalId() ?? ''
        );
    }

    protected function getResponseFactory()
    {
        return PurchaseResponseFactory::class;
    }

    public function getInvoiceNumber()
    {
        return $this->getParameter('invoiceNumber');
    }

    public function setInvoiceNumber($value)
    {
        return $this->setParameter('invoiceNumber', $value);
    }

    public function getRegisterNumber()
    {
        return $this->getParameter('registerNumber');
    }

    public function setRegisterNumber($value)
    {
        return $this->setParameter('registerNumber', $value);
    }

    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }

    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }
}
