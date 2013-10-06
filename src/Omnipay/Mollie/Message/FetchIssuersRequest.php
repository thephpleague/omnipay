<?php

namespace Omnipay\Mollie\Message;

/**
 * Mollie Fetch Issuers Request
 */
class FetchIssuersRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData();
        $data['a'] = 'banklist';

        return $data;
    }
}
