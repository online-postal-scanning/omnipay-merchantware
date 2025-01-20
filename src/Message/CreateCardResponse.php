<?php

namespace Omnipay\Merchantware\Message;

class CreateCardResponse extends AbstractResponse
{
    public function getCardReference(): string
    {
        return $this->data['VaultBoardingResponse']['VaultToken'];
    }
}