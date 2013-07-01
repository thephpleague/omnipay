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
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Mollie Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        if ($this->isRedirect()) {
            return false;
        }

        return 'error' !== (string) $this->data->item['type'];
    }

    public function isRedirect()
    {
        return isset($this->data->order->URL);
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return (string) $this->data->order->URL;
        }
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getTransactionReference()
    {
        if (isset($this->data->order)) {
            return (string) $this->data->order->transaction_id;
        }
    }

    public function getMessage()
    {
        if (isset($this->data->item)) {
            return (string) $this->data->item->message;
        } elseif (isset($this->data->order)) {
            return (string) $this->data->order->message;
        } else {
            return (string) $this->data->message;
        }
    }

    public function getCode()
    {
        if (isset($this->data->item)) {
            return (string) $this->data->item->errorcode;
        }
    }

    /**
     * Get an associateive array of banks returned from a fetchIssuers request
     */
    public function getIssuers()
    {
        if (isset($this->data->bank)) {
            $issuers = array();

            foreach ($this->data->bank as $bank) {
                $bank_id = (string) $bank->bank_id;
                $issuers[$bank_id] = (string) $bank->bank_name;
            }

            return $issuers;
        }
    }
}
