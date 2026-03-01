<?php

/**
 * PayTheFly Webhook Request.
 *
 * Handles incoming webhook notifications from PayTheFly.
 * This is an alternative to CompletePurchaseRequest that implements
 * the NotificationInterface for use with acceptNotification().
 *
 * Webhook body: { "data": "<json string>", "sign": "<hmac hex>", "timestamp": <unix> }
 * Signature: HMAC-SHA256(data + "." + timestamp, projectKey)
 *
 * Payload fields: value, confirmed, serial_no, tx_hash, wallet, tx_type
 * tx_type: 1=payment, 2=withdrawal
 *
 * Response MUST contain "success" string.
 */

namespace Omnipay\PayTheFly\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\NotificationInterface;

class WebhookRequest extends AbstractRequest implements NotificationInterface
{
    /**
     * Get the raw webhook body.
     *
     * @return array|null
     */
    public function getWebhookBody()
    {
        return $this->getParameter('webhookBody');
    }

    /**
     * Set the raw webhook body.
     *
     * @param array $value
     * @return $this
     */
    public function setWebhookBody($value)
    {
        return $this->setParameter('webhookBody', $value);
    }

    /**
     * Get data for this request.
     *
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('projectKey');

        $body = $this->getWebhookBody();

        if (empty($body) || !isset($body['data'], $body['sign'], $body['timestamp'])) {
            throw new InvalidRequestException(
                'Invalid webhook body. Expected: { "data": "<json>", "sign": "<hmac>", "timestamp": <unix> }'
            );
        }

        // Verify HMAC-SHA256 signature with timing-safe comparison
        $expected = hash_hmac('sha256', $body['data'] . '.' . $body['timestamp'], $this->getProjectKey());

        if (!hash_equals($expected, $body['sign'])) {
            throw new InvalidRequestException('Webhook signature verification failed');
        }

        return json_decode($body['data'], true) ?: [];
    }

    /**
     * Send the request.
     *
     * @param array $data
     * @return WebhookResponse
     */
    public function sendData($data)
    {
        return $this->response = new WebhookResponse($this, $data);
    }

    /**
     * Get the transaction reference from the webhook.
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        $data = $this->getData();
        return $data['serial_no'] ?? null;
    }

    /**
     * Get the transaction status.
     *
     * @return string NotificationInterface::STATUS_COMPLETED or STATUS_PENDING
     */
    public function getTransactionStatus()
    {
        $data = $this->getData();

        if (($data['confirmed'] ?? false) && ($data['tx_type'] ?? 0) === 1) {
            return NotificationInterface::STATUS_COMPLETED;
        }

        return NotificationInterface::STATUS_PENDING;
    }

    /**
     * Get the notification message.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'success'; // PayTheFly requires "success" in webhook response
    }
}
