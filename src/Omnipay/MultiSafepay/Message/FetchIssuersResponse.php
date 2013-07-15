<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay\Message;

class FetchIssuersResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->issuers);
    }

    /**
     * Return available issuers as an associative array.
     *
     * @return array
     */
    public function getIssuers()
    {
        $result = array();

        foreach ($this->data->issuers->issuer as $issuer) {
            $result[(string) $issuer->code] = (string) $issuer->description;
        }

        return $result;
    }
}
