<?php

namespace Omnipay\Merchantware\Factory;

use Omnipay\Merchantware\Message\PurchaseRequest;
use Omnipay\Merchantware\Message\PurchaseResponse;

class PurchaseResponseFactory extends AbstractResponseFactory
{
    public function handle(PurchaseRequest $request, $gatewayResponse): PurchaseResponse
    {
        $response = $this->parseResponse($gatewayResponse);

        return new PurchaseResponse($request, $response);
    }
}
