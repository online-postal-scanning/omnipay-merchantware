<?php

namespace Omnipay\Merchantware\Factory;

use SimpleXMLElement;

abstract class AbstractResponseFactory
{
    protected function parseResponse($response): array
    {
        $content = (string) $response->getBody();
        $xml = new SimpleXMLElement($content);
        
        // Register the SOAP namespace
        $xml->registerXPathNamespace('soap', 'http://www.w3.org/2003/05/soap-envelope');
        $xml->registerXPathNamespace('ns', 'http://schemas.merchantwarehouse.com/merchantware/v46/');
        
        // Get the response element (either SaleResponse or CreateCardResponse)
        $responseElements = $xml->xpath('//ns:*[contains(local-name(), "Response")]/*[contains(local-name(), "Result")]');
        
        if (empty($responseElements)) {
            throw new \RuntimeException('No response data found in SOAP response');
        }
        
        return $this->xmlToArray($responseElements[0]);
    }

    protected function xmlToArray(SimpleXMLElement $xml): array
    {
        $data = [];
        
        foreach ($xml->children() as $child) {
            $value = trim((string) $child);
            
            if ($child->count() > 0) {
                $value = $this->xmlToArray($child);
            }
            
            $data[$child->getName()] = $value;
        }
        
        return $data;
    }
}