<?php

namespace Omnipay\Merchantware\Message;

class CreateCardResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return !empty($this->data['VaultToken']);
    }

    public function getCardReference(): ?string
    {
        return $this->data['VaultToken'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['ErrorMessage'] ?? null;
    }

    public function getCode(): ?string
    {
        return $this->data['ErrorCode'] ?? null;
    }
}