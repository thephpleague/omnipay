<?php

namespace Omnipay\Sips\Message;

use Doctrine\ORM\Persisters\AbstractEntityInheritancePersister;
use Omnipay\Sips\Message\RequestCall;

/**
 * Sips Authorize Response
 */
class RequestResult extends SipsBinaryResult
{
    /**
     * @inheritdoc
     */
    protected function getResultComponents()
    {
        return array(
            'code' => 1,
            'debug' => 2,
            'message' => 3
        );
    }

    /**
     * @inheritdoc
     */
    public function setData()
    {
        $this->validate('amount', 'card');

        $this->getCard()->validate();

        return array('amount' => $this->getAmount());
    }
}
