<?php

namespace Omnipay\Merchantware\Message;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return isset($this->data['SaleResponse']['SaleResult']['ApprovalStatus']) 
            && $this->data['SaleResponse']['SaleResult']['ApprovalStatus'] === 'APPROVED';
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['SaleResponse']['SaleResult']['Token'] ?? null;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->data['SaleResponse']['SaleResult']['AuthorizationCode'] ?? null;
    }

    public function getAvsResponse(): ?string
    {
        return $this->data['SaleResponse']['SaleResult']['AvsResponse'] ?? null;
    }

    public function getCvvResponse(): ?string
    {
        return $this->data['SaleResponse']['SaleResult']['CvvResponse'] ?? null;
    }

    public function getMessage(): ?string
    {
        if (!$this->isSuccessful()) {
            return $this->data['SaleResponse']['SaleResult']['ErrorMessage'] ?? 'Unknown error';
        }

        return $this->data['SaleResponse']['SaleResult']['ApprovalStatus'] ?? null;
    }

    public function getCode(): ?string
    {
        return $this->data['SaleResponse']['SaleResult']['ErrorCode'] ?? null;
    }
}
