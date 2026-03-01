<?php

/**
 * PayTheFly Complete Purchase Request.
 *
 * Handles incoming webhook notifications from PayTheFly.
 * Verifies HMAC-SHA256 signature and extracts payment data.
 *
 * Webhook body format:
 * {
 *   "data": "<json string>",  // Stringified JSON payload
 *   "sign": "<hmac hex>",     // HMAC-SHA256 hex signature
 *   "timestamp": <unix>       // Unix timestamp
 * }
 *
 * Webhook signature: HMAC-SHA256(data + "." + timestamp, projectKey)
 *
 * Webhook payload fields (inside "data" JSON string):
 *   - value (NOT "amount")
 *   - confirmed (NOT "status")
 *   - serial_no
 *   - tx_hash
 *   - wallet
 *   - tx_type: 1 = payment, 2 = withdrawal
 *
 * Webhook response MUST contain the string "success".
 */

namespace Omnipay\PayTheFly\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * Get the raw webhook payload.
     *
     * @return array|null
     */
    public function getWebhookData()
    {
        return $this->getParameter('webhookData');
    }

    /**
     * Set the raw webhook payload.
     *
     * @param array $value The decoded webhook JSON body
     * @return $this
     */
    public function setWebhookData($value)
    {
        return $this->setParameter('webhookData', $value);
    }

    /**
     * Get and validate the request data.
     *
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('projectKey');

        $webhookData = $this->getWebhookData();

        if (empty($webhookData)) {
            throw new InvalidRequestException('Webhook data is required');
        }

        if (!isset($webhookData['data'], $webhookData['sign'], $webhookData['timestamp'])) {
            throw new InvalidRequestException(
                'Invalid webhook format. Expected: data (json string), sign (hmac hex), timestamp (unix)'
            );
        }

        // Verify HMAC-SHA256 signature using timing-safe comparison
        $expectedSign = hash_hmac(
            'sha256',
            $webhookData['data'] . '.' . $webhookData['timestamp'],
            $this->getProjectKey()
        );

        if (!hash_equals($expectedSign, $webhookData['sign'])) {
            throw new InvalidRequestException('Invalid webhook signature');
        }

        // Decode the data payload
        $payload = json_decode($webhookData['data'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidRequestException('Invalid webhook data JSON: ' . json_last_error_msg());
        }

        return array(
            'payload'   => $payload,
            'timestamp' => $webhookData['timestamp'],
        );
    }

    /**
     * Send the request (process webhook data).
     *
     * @param array $data
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
