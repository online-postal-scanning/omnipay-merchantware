<?php

namespace Tests;

use Omnipay\Merchantware\Gateway;
use Omnipay\Merchantware\Message\CreateCardRequest;
use Omnipay\Merchantware\Message\PurchaseRequest;
use Omnipay\Tests\GatewayTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Gateway::class)]
class GatewayTest extends GatewayTestCase
{
    protected $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantName('TestMerchant');
        $this->gateway->setMerchantSiteId('TestSiteId');
        $this->gateway->setMerchantKey('TestKey');
    }

    public function testCreateCard(): void
    {
        $request = $this->gateway->createCard([
            'card' => $this->getValidCard(),
        ]);

        self::assertInstanceOf(CreateCardRequest::class, $request);
        self::assertSame('TestMerchant', $request->getMerchantName());
        self::assertSame('TestSiteId', $request->getMerchantSiteId());
        self::assertSame('TestKey', $request->getMerchantKey());
    }

    public function testPurchase(): void
    {
        $request = $this->gateway->purchase([
            'amount' => '10.00',
            'cardReference' => 'abc123',
            'invoiceNumber' => 'INV123',
            'registerNumber' => 'REG1',
            'merchantTransactionId' => 'TXN123',
            'terminalId' => 'TERM1',
        ]);

        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('TestMerchant', $request->getMerchantName());
        self::assertSame('TestSiteId', $request->getMerchantSiteId());
        self::assertSame('TestKey', $request->getMerchantKey());
        self::assertSame('10.00', $request->getAmount());
        self::assertSame('abc123', $request->getCardReference());
    }
}
