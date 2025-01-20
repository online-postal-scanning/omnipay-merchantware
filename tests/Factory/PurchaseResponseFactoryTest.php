<?php

namespace Tests\Factory;

use Omnipay\Merchantware\Factory\PurchaseResponseFactory;
use Omnipay\Merchantware\Message\PurchaseRequest;
use Omnipay\Merchantware\Message\PurchaseResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[CoversClass(PurchaseResponseFactory::class)]
class PurchaseResponseFactoryTest extends TestCase
{
    private PurchaseResponseFactory $factory;
    private PurchaseRequest $request;

    protected function setUp(): void
    {
        $this->factory = new PurchaseResponseFactory();
        $this->request = $this->createMock(PurchaseRequest::class);
    }

    public function testHandleSuccessfulResponse(): void
    {
        $xmlContent = file_get_contents(__DIR__ . '/../Fixtures/Purchase/success.xml');
        $response = $this->createMockResponse($xmlContent);

        $result = $this->factory->handle($this->request, $response);

        self::assertInstanceOf(PurchaseResponse::class, $result);
        self::assertTrue($result->isSuccessful());
        self::assertSame('123546046446', $result->getTransactionReference());
        self::assertSame('123311', $result->getAuthorizationCode());
        self::assertSame('Z', $result->getAvsResponse());
        self::assertNull($result->getCvvResponse());
        self::assertSame('APPROVED', $result->getMessage());
        self::assertNull($result->getCode());
    }

    public function testHandleFailedResponse(): void
    {
        $xmlContent = file_get_contents(__DIR__ . '/../Fixtures/Purchase/failure.xml');
        $response = $this->createMockResponse($xmlContent);

        $result = $this->factory->handle($this->request, $response);

        self::assertInstanceOf(PurchaseResponse::class, $result);
        self::assertFalse($result->isSuccessful());
        self::assertSame('6043536930', $result->getTransactionReference());
        self::assertNull($result->getAuthorizationCode());
        self::assertNull($result->getAvsResponse());
        self::assertNull($result->getCvvResponse());
        self::assertSame('DECLINED,DUPLICATE;1110;duplicate transaction', $result->getMessage());
        self::assertSame('1110', $result->getCode());
    }

    private function createMockResponse(string $content): ResponseInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($content);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }
}
