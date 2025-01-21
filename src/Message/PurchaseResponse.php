<?php

namespace Omnipay\Merchantware\Message;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return isset($this->data['ApprovalStatus']) 
            && $this->data['ApprovalStatus'] === 'APPROVED';
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['Token'] ?? null;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->data['AuthorizationCode'] ?? null;
    }

    public function getAvsResponse(): ?string
    {
        return $this->data['AvsResponse'] ?? null;
    }

    public function getCvvResponse(): ?string
    {
        return $this->data['CvResponse'] ?? null;
    }
}
