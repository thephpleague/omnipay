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

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

class FetchIssuersRequest extends BaseAbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://www.targetpay.com/ideal/getissuers.php?format=xml';

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $httpResponse = $this->httpClient->get($this->endpoint)->send();

        return $this->response = new FetchIssuersResponse($this, $httpResponse->xml());
    }
}
