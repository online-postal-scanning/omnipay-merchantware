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
        return empty($this->data['Token']) ? null : $this->data['Token'];
    }

    public function getAuthorizationCode(): ?string
    {
        return empty($this->data['AuthorizationCode']) ? null : $this->data['AuthorizationCode'];
    }

    public function getAvsResponse(): ?string
    {
        return empty($this->data['AvsResponse']) ? null : $this->data['AvsResponse'];
    }

    public function getCvvResponse(): ?string
    {
        return empty($this->data['CvResponse']) ? null : $this->data['CvResponse'];
    }
}
