<?php

namespace Omnipay\Merchantware\Message;

class CreateCardResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return !empty($this->data['VaultBoardingResponse']['VaultToken']);
    }

    public function getCardReference(): ?string
    {
        return $this->data['VaultBoardingResponse']['VaultToken'] ?? null;
    }
}