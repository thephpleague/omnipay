<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TargetPay\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

class FetchIssuersResponse extends BaseAbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return true;
    }

    /**
     * Return available issuers as an associative array.
     *
     * @return array
     */
    public function getIssuers()
    {
        $result = array();

        foreach ($this->data as $issuer) {
            $result[(string) $issuer['id']] = (string) $issuer;
        }

        return $result;
    }
}
