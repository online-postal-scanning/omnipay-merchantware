<?php

namespace Tests\Factory;

use Omnipay\Merchantware\Factory\CreateCardResponseFactory;
use Omnipay\Merchantware\Message\CreateCardRequest;
use Omnipay\Merchantware\Message\CreateCardResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[CoversClass(CreateCardResponseFactory::class)]
class CreateCardResponseFactoryTest extends TestCase
{
    private CreateCardResponseFactory $factory;
    private CreateCardRequest $request;

    protected function setUp(): void
    {
        $this->factory = new CreateCardResponseFactory();
        $this->request = $this->createMock(CreateCardRequest::class);
    }

    public function testHandleSuccessfulResponse(): void
    {
        $xmlContent = file_get_contents(__DIR__ . '/../Fixtures/CreateCard/success.xml');
        $response = $this->createMockResponse($xmlContent);

        $result = $this->factory->handle($this->request, $response);

        self::assertInstanceOf(CreateCardResponse::class, $result);
        self::assertTrue($result->isSuccessful());
        self::assertSame('10000010CWOJZ2IQGCOA', $result->getCardReference());
        // Add more assertions based on your XML content
    }

    public function testHandleFailedResponse(): void
    {
        $xmlContent = file_get_contents(__DIR__ . '/../Fixtures/CreateCard/failure.xml');
        $response = $this->createMockResponse($xmlContent);

        $result = $this->factory->handle($this->request, $response);

        self::assertInstanceOf(CreateCardResponse::class, $result);
        self::assertFalse($result->isSuccessful());
        self::assertSame('CVV2 Value supplied is invalid', $result->getMessage());
        self::assertSame('N7', $result->getCode());
        // Add more assertions based on your XML content
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
