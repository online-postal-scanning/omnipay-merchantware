<?php

namespace Omnipay\Merchantware\Factory;

class AbstractResponseFactory
{
    protected function parseResponse($response): array
    {
        $body = (string) $response->getBody();
        
        // Debug output
        error_log('Response Body: ' . $body);
        
        $xml = new \SimpleXMLElement($body);
        $xml->registerXPathNamespace('soap', 'http://www.w3.org/2003/05/soap-envelope');
        $xml->registerXPathNamespace('ns', 'http://schemas.merchantwarehouse.com/merchantware/v46/');

        if ($response->getStatusCode() >= 400) {
            $fault = $xml->xpath('//soap:Fault');
            if (!empty($fault)) {
                throw new \RuntimeException((string) $fault[0]->Reason->Text);
            }
        }

        // Find the response element (AuthorizeResponse, FindBoardedCardResponse, etc.)
        $responseElements = $xml->xpath('//ns:AuthorizeResult');
        if (empty($responseElements)) {
            throw new \RuntimeException('No response data found in SOAP response');
        }

        $responseData = $responseElements[0];
        
        // Debug output
        error_log('Response Body Contents: ' . print_r($responseData, true));
        error_log('Response Body as XML: ' . $responseData->asXML());

        // Convert SimpleXMLElement to array
        $result = [];
        foreach ($responseData->children() as $key => $value) {
            if ($key === 'ExtensionPoint') {
                continue; // Skip ExtensionPoint data
            }
            $result[$key] = (string)$value;
        }

        // If there's an error message, make sure it's included in the result
        if (isset($result['ErrorMessage']) && !empty($result['ErrorMessage'])) {
            $result['error'] = $result['ErrorMessage'];
            $result['status'] = 'failed';
        } else {
            $result['status'] = 'authorized';
        }

        return $result;
    }

    protected function convertXmlToArray(\SimpleXMLElement $xml): array
    {
        $result = [];
        foreach ($xml->children() as $key => $value) {
            $result[$key] = (string)$value;
        }
        return $result;
    }
}