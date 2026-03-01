<?php

/**
 * PayTheFly Complete Purchase Response.
 *
 * Processes the webhook payload and determines payment status.
 *
 * Webhook payload fields:
 *   - value: Payment amount (NOT "amount")
 *   - confirmed: Whether payment is confirmed (NOT "status")
 *   - serial_no: Order serial number
 *   - tx_hash: Blockchain transaction hash
 *   - wallet: Payer's wallet address
 *   - tx_type: 1 = payment, 2 = withdrawal
 *
 * IMPORTANT: Your webhook handler MUST return a response containing "success"
 * for PayTheFly to mark the notification as delivered.
 */

namespace Omnipay\PayTheFly\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Transaction type constants.
     */
    const TX_TYPE_PAYMENT    = 1;
    const TX_TYPE_WITHDRAWAL = 2;

    /**
     * Is the payment successful?
     *
     * A payment is considered successful when:
     * 1. The webhook data has a valid payload
     * 2. The "confirmed" field is truthy
     * 3. The tx_type is 1 (payment)
     *
     * @return bool
     */
    public function isSuccessful()
    {
        $payload = $this->getPayload();

        if (empty($payload)) {
            return false;
        }

        // PayTheFly uses "confirmed" (not "status") to indicate payment confirmation
        $confirmed = $payload['confirmed'] ?? false;
        $txType = $payload['tx_type'] ?? null;

        return $confirmed && $txType === self::TX_TYPE_PAYMENT;
    }

    /**
     * Get the decoded webhook payload.
     *
     * @return array|null
     */
    public function getPayload()
    {
        return $this->data['payload'] ?? null;
    }

    /**
     * Get the transaction reference (serial number).
     *
     * PayTheFly uses "serial_no" in webhook payloads.
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        $payload = $this->getPayload();
        return $payload['serial_no'] ?? null;
    }

    /**
     * Get the transaction ID (blockchain tx hash).
     *
     * @return string|null
     */
    public function getTransactionId()
    {
        $payload = $this->getPayload();
        return $payload['tx_hash'] ?? null;
    }

    /**
     * Get the payment value.
     *
     * PayTheFly uses "value" (NOT "amount") in webhook payloads.
     *
     * @return string|null
     */
    public function getAmount()
    {
        $payload = $this->getPayload();
        return $payload['value'] ?? null;
    }

    /**
     * Get the payer's wallet address.
     *
     * @return string|null
     */
    public function getWallet()
    {
        $payload = $this->getPayload();
        return $payload['wallet'] ?? null;
    }

    /**
     * Get the transaction type.
     *
     * @return int|null 1 = payment, 2 = withdrawal
     */
    public function getTxType()
    {
        $payload = $this->getPayload();
        return $payload['tx_type'] ?? null;
    }

    /**
     * Is this a payment transaction?
     *
     * @return bool
     */
    public function isPayment()
    {
        return $this->getTxType() === self::TX_TYPE_PAYMENT;
    }

    /**
     * Is this a withdrawal transaction?
     *
     * @return bool
     */
    public function isWithdrawal()
    {
        return $this->getTxType() === self::TX_TYPE_WITHDRAWAL;
    }

    /**
     * Get the response message.
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->isSuccessful()) {
            return 'Payment confirmed';
        }

        $payload = $this->getPayload();
        if (empty($payload)) {
            return 'No payload data';
        }

        if (!($payload['confirmed'] ?? false)) {
            return 'Payment not yet confirmed';
        }

        return 'Unknown status';
    }
}
