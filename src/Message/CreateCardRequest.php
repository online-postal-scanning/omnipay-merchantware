<?php

namespace Omnipay\Merchantware\Message;

use Omnipay\Merchantware\Factory\CreateCardResponseFactory;

class CreateCardRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('card');
        $card = $this->getCard();$baseData = $this->getBaseData();

        return sprintf(
            '<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
    <soap12:Body>
        <Authorize xmlns="http://schemas.merchantwarehouse.com/merchantware/v46/">
            <Credentials>
                <MerchantName>%s</MerchantName>
                <MerchantSiteId>%s</MerchantSiteId>
                <MerchantKey>%s</MerchantKey>
            </Credentials>
            <PaymentData>
                <Source>KEYED</Source>
                <CardNumber>%s</CardNumber>
                <ExpirationDate>%s</ExpirationDate>
                <CardHolder>%s</CardHolder>
                <AvsZipCode>%s</AvsZipCode>
                <CardVerificationValue>%s</CardVerificationValue>
                <CardPresence>NOTPRESENT</CardPresence>
            </PaymentData>
            <Request>
                <Amount>%s</Amount>
                <TaxAmount>0</TaxAmount>
                <ForceDuplicate>False</ForceDuplicate>
            </Request>
        </Authorize>
    </soap12:Body>
</soap12:Envelope>',
$baseData['merchantName'],
$baseData['merchantSiteId'],
$baseData['merchantKey'],
$card->getNumber(),
$card->getExpiryDate('my'),  // Format: MMYY
$card->getName(),
$card->getPostcode(),
$card->getCvv(),
$this->getAmount() ?? '0.00'
        );
    }

    protected function getResponseFactory()
    {
        return CreateCardResponseFactory::class;
    }
}