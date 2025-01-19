<?php

namespace Omnipay\Merchantware\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Psr\Http\Message\RequestFactoryInterface;

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

    public function sendData($data)
    {
        // $data is a soap envelope
        $headers = [
            'Content-Type' => 'application/soap+xml;charset=UTF-8',
        ];

                // Debug request
        error_log('Request URL: ' . $this->getEndpoint());
        error_log('Request Headers:');
        error_log(print_r($headers, true));
        error_log('Request Body:');
        error_log($data);

        // $request = $this->messageFactory->createRequest('POST', $this->getEndpoint());
        // foreach ($headers as $name => $value) {
        //     $request = $request->withHeader($name, $value);
        // }
        // $request = $request->withBody(\GuzzleHttp\Psr7\Utils::streamFor($data));

        $response = $this->httpClient->request(
            'POST', 
            $this->getEndpoint(),
            $headers,
            $data,
        );

        // Debug raw response
        error_log('Raw Response Body:');
        error_log((string)$response->getBody());
        error_log('Response Status: ' . $response->getStatusCode());

        // $parsed = $this->parseResponse($response);
        // if (false === ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300)) {
        //     throw HttpException::factory($request, $response);
        // }

        $factory = $this->getResponseFactory();

        return $this->response = (new $factory)->handle($this, $response);
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
