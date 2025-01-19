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
        return isset($this->data['ErrorMessage']) ? $this->data['ErrorMessage'] : null;
    }

    public function getCode()
    {
        return isset($this->data['ErrorCode']) ? $this->data['ErrorCode'] : null;
    }
}
