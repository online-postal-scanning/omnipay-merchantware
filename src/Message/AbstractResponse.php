<?php

namespace Omnipay\Merchantware\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;

abstract class AbstractResponse extends OmnipayAbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['ApprovalStatus']) && $this->data['ApprovalStatus'] === 'APPROVED';
    }

    public function getTransactionReference()
    {
        return isset($this->data['Token']) ? $this->data['Token'] : null;
    }

    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            $message = $this->extractErrorMessage();

            if ($message) {
                if (str_contains($message, ':')) {
                    return trim(substr(strstr($message, ':'), 1));
                }
                return $message;
            }
        }
        
        return $this->data['ApprovalStatus'] ?? null;
    }

    public function getCode()
    {
        $message = $this->extractErrorMessage();
        
        if ($message && str_contains($message, ':')) {
            return trim(strstr($message, ':', true));
        }
        
        return $this->data['VaultBoardingResponse']['ErrorCode'] ?? null;
    }

    protected function extractErrorMessage()
    {
        return $this->data['ErrorMessage'] 
            ?? $this->data['VaultBoardingResponse']['ErrorMessage'] 
            ?? null;    
    }
}
