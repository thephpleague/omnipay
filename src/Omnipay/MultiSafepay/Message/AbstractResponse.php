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

use Exception;
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
     * @param mixed            $data
     *
     * @throws InvalidResponseException when $data is not valid XML
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        try {
            $this->data = new SimpleXMLElement($data);
        } catch (Exception $e) {
            throw new InvalidResponseException();
        }
    }
}
