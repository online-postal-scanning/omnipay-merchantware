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

    public function getMessage(): ?string
    {
        if (!$this->isSuccessful()) {
            if (!empty($this->data['ErrorMessage'])) {
                return $this->data['ErrorMessage'];
            }
            return $this->data['ApprovalStatus'] ?? 'Unknown error';
        }

        return $this->data['ApprovalStatus'] ?? null;
    }

    public function getCode(): ?string
    {
        if (!$this->isSuccessful() && !empty($this->data['ApprovalStatus'])) {
            $parts = explode(';', $this->data['ApprovalStatus']);
            if (count($parts) >= 2) {
                return $parts[1];
            }
        }
        return $this->data['ErrorCode'] ?? null;
    }
}
