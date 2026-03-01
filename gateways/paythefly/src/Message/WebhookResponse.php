<?php

/**
 * PayTheFly Webhook Response.
 *
 * Response for webhook notification processing.
 * Uses PayTheFly-specific field names:
 *   - value (not amount)
 *   - confirmed (not status)
 *   - serial_no, tx_hash, wallet, tx_type
 */

namespace Omnipay\PayTheFly\Message;

use Omnipay\Common\Message\AbstractResponse;

class WebhookResponse extends AbstractResponse
{
    /**
     * Is the payment successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return ($this->data['confirmed'] ?? false) && ($this->data['tx_type'] ?? 0) === 1;
    }

    /**
     * Get the transaction reference (serial_no).
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        return $this->data['serial_no'] ?? null;
    }

    /**
     * Get the blockchain transaction hash.
     *
     * @return string|null
     */
    public function getTransactionId()
    {
        return $this->data['tx_hash'] ?? null;
    }

    /**
     * Get the payment value.
     *
     * PayTheFly uses "value" instead of "amount" in webhooks.
     *
     * @return string|null
     */
    public function getAmount()
    {
        return $this->data['value'] ?? null;
    }

    /**
     * Get the payer's wallet address.
     *
     * @return string|null
     */
    public function getWallet()
    {
        return $this->data['wallet'] ?? null;
    }

    /**
     * Get the response message.
     *
     * PayTheFly requires the response to contain "success".
     *
     * @return string
     */
    public function getMessage()
    {
        return 'success';
    }
}
