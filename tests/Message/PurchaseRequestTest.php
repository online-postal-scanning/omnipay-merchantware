<?php

namespace Omnipay\Merchantware\Tests\Message;

use Omnipay\Merchantware\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PurchaseRequest::class)]
class PurchaseRequestTest extends TestCase
{
    private PurchaseRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'merchantName' => 'TestMerchant',
            'merchantSiteId' => 'TestSiteId',
            'merchantKey' => 'TestKey',
            'amount' => '10.00',
            'cardReference' => 'abc123',
            'invoiceNumber' => 'INV123',
            'registerNumber' => 'REG1',
            'terminalId' => 'TERM1',
            'merchantTransactionId' => 'TXN123',
        ]);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();
        
        self::assertIsString($data);
        self::assertStringContainsString('<MerchantName>TestMerchant</MerchantName>', $data);
        self::assertStringContainsString('<MerchantSiteId>TestSiteId</MerchantSiteId>', $data);
        self::assertStringContainsString('<MerchantKey>TestKey</MerchantKey>', $data);
        self::assertStringContainsString('<Source>VAULT</Source>', $data);
        self::assertStringContainsString('<VaultToken>abc123</VaultToken>', $data);
        self::assertStringContainsString('<Amount>10.00</Amount>', $data);
        self::assertStringContainsString('<InvoiceNumber>INV123</InvoiceNumber>', $data);
        self::assertStringContainsString('<StoredCardReason>UNSCHEDULEDCIT</StoredCardReason>', $data);
    }

    public function testMissingAmount(): void
    {
        $this->request->initialize([
            'merchantName' => 'TestMerchant',
            'merchantSiteId' => 'TestSiteId',
            'merchantKey' => 'TestKey',
            'cardReference' => 'abc123',
            'invoiceNumber' => 'INV123',
            'merchantTransactionId' => 'TXN123',
        ]);

        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('The amount parameter is required');

        $this->request->getData();
    }

    public function testMissingCardReference(): void
    {
        $this->request->initialize([
            'merchantName' => 'TestMerchant',
            'merchantSiteId' => 'TestSiteId',
            'merchantKey' => 'TestKey',
            'amount' => '10.00',
            'invoiceNumber' => 'INV123',
            'merchantTransactionId' => 'TXN123',
        ]);

        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('The cardReference parameter is required');

        $this->request->getData();
    }
}
