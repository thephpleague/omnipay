<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
