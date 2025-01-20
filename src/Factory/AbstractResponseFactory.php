<?php

namespace Omnipay\Merchantware\Factory;

class AbstractResponseFactory
{
    protected function parseResponse($response): array
    {
        $body = (string) $response->getBody();
        
        $xml = new \SimpleXMLElement($body);
        $xml->registerXPathNamespace('soap', 'http://www.w3.org/2003/05/soap-envelope');
        $xml->registerXPathNamespace('ns', 'http://schemas.merchantwarehouse.com/merchantware/v46/');
    
        if ($response->getStatusCode() >= 400) {
            $fault = $xml->xpath('//soap:Fault');
            if (!empty($fault)) {
                throw new \RuntimeException((string) $fault[0]->Reason->Text);
            }
        }
    
        $responseElements = $xml->xpath('//ns:AuthorizeResult');
        if (empty($responseElements)) {
            throw new \RuntimeException('No response data found in SOAP response');
        }
    
        $responseData = $responseElements[0];
        
        // Convert SimpleXMLElement to array recursively
        $result = $this->xmlToArray($responseData);
        
        // If we have a VaultBoardingResponse, use its data
        if (isset($result['VaultBoardingResponse'])) {
            $vaultData = $result['VaultBoardingResponse'];
            $result['Token'] = $vaultData['VaultToken'] ?? null;
            $result['ErrorMessage'] = $vaultData['ErrorMessage'] ?? null;
        }
    
        if (!empty($result['Token']) || !empty($result['VaultToken'])) {
            $result['status'] = 'success';
        } else if (!empty($result['ErrorMessage'])) {
            $result['error'] = $result['ErrorMessage'];
            $result['status'] = 'failed';
        }
    
        return $result;
    }
    
    protected function xmlToArray(\SimpleXMLElement $xml): array
    {
        $result = [];
        foreach ($xml->children() as $key => $value) {
            if ($key === 'ExtensionPoint') {
                continue;
            }
            if ($value->count() > 0) {
                // Node has children, recurse
                $result[$key] = $this->xmlToArray($value);
            } else {
                $result[$key] = (string)$value;
            }
        }
        return $result;
    }
}