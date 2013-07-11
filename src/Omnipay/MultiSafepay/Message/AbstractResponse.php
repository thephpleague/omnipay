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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use SimpleXMLElement;

abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * Constructor.
     *
     * @param RequestInterface $request
     * @param SimpleXMLElement $data
     *
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, SimpleXMLElement $data)
    {
        $this->request = $request;
        $this->data = $data;

        if (isset($this->data->error)) {
            throw new InvalidResponseException(
                (string) $this->data->error->description,
                (int) $this->data->error->code
            );
        }
    }
}
