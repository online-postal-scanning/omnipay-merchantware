<?php
declare(strict_types=1);

namespace Omnipay\Merchantware\Factory;

use Omnipay\Merchantware\Message\CreateCardRequest;
use Omnipay\Merchantware\Message\CreateCardResponse;

class CreateCardResponseFactory extends AbstractResponseFactory
{
    public function handle(CreateCardRequest $request, $gatewayResponse): CreateCardResponse
    {
        $response = $this->parseResponse($gatewayResponse);

        return new CreateCardResponse($request, $response);
    }
}