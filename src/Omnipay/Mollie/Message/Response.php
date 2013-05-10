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

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Mollie Response
 */
class Response extends AbstractResponse
{
    /**
     * Creates a new Response instance, parsing the request data from XML.
     *
     * @param RequestInterface $request
     * @param $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data->xml();
    }

    /**
     * We consider the general response successful if the data is a SimpleXMLElement.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return ($this->data instanceof \SimpleXMLElement);
    }

    /**
     * Default redirect method for Mollie iDeal.
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Default redirect data for Mollie iDeal (none).
     *
     * @return null
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * Gets the transaction_id from the order, if available.
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        if ($this->data instanceof \SimpleXMLElement
            && $this->data->order instanceof \SimpleXMLElement
            && $this->data->order->transaction_id
        ) {
            return (string)$this->data->order->transaction_id;
        }
        return null;
    }


    /**
     * Gets the message from the request. Mollie iDeal has a message in most APIs,
     * so don't check for this to see if it was successful or not.
     *
     * @return null|string
     */
    public function getMessage()
    {
        if ($this->data instanceof \SimpleXMLElement) {
            if ($this->data->item && $this->data->item->message) return (string)$this->data->item->message;
            if ($this->data->order && $this->data->order->message) return (string)$this->data->order->message;
            if ($this->data->message) return (string)$this->data->message;
        }
        return null;
    }
}
