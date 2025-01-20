<?php

namespace Omnipay\Merchantware\Message;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return isset($this->data['SaleResponse']['ApprovalStatus']) 
            && $this->data['SaleResponse']['ApprovalStatus'] === 'APPROVED';
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['SaleResponse']['Token'] ?? null;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->data['SaleResponse']['AuthorizationCode'] ?? null;
    }

    public function getAvsResponse(): ?string
    {
        return $this->data['SaleResponse']['AvsResponse'] ?? null;
    }

    public function getCvvResponse(): ?string
    {
        return $this->data['SaleResponse']['CvvResponse'] ?? null;
    }

    public function getMessage(): ?string
    {
        if (!$this->isSuccessful()) {
            return $this->data['SaleResponse']['ErrorMessage'] ?? 'Unknown error';
        }

        return $this->data['SaleResponse']['ApprovalStatus'] ?? null;
    }

    public function getCode(): ?string
    {
        return $this->data['SaleResponse']['ErrorCode'] ?? null;
    }
}
