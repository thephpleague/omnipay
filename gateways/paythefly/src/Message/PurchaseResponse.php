<?php

/**
 * PayTheFly Purchase Response.
 *
 * This response handles the redirect to the PayTheFly payment page.
 * PayTheFly is always an off-site (redirect) gateway.
 *
 * Payment link format:
 * https://pro.paythefly.com/pay?chainId=56&projectId=xxx&amount=0.01&serialNo=xxx&deadline=xxx&signature=0x...&token=0x...
 *
 * Note: The signature must be generated server-side using EIP-712 typed data signing.
 * The amount is human-readable (e.g., "0.01"), NOT in raw token units.
 */

namespace Omnipay\PayTheFly\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * PayTheFly payment page base URL.
     *
     * @var string
     */
    protected $endpoint = 'https://pro.paythefly.com/pay';

    /**
     * Is the payment successful?
     *
     * PayTheFly always requires a redirect, so this returns false.
     * Use completePurchase() to check payment status via webhook.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Does the response require a redirect?
     *
     * @return bool Always true for PayTheFly
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Get the redirect URL.
     *
     * Constructs the PayTheFly payment URL with all required parameters.
     * Note: The EIP-712 signature should be appended by the merchant's
     * server-side signing service before redirecting the user.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $data = $this->getData();

        $params = array(
            'chainId'   => $data['chainId'],
            'projectId' => $data['projectId'],
            'amount'    => $data['amount'],
            'serialNo'  => $data['serialNo'],
            'deadline'  => $data['deadline'],
            'token'     => $data['token'],
            // 'signature' => '0x...' // Must be set by the merchant's EIP-712 signing service
        );

        return $this->endpoint . '?' . http_build_query($params);
    }

    /**
     * Get the redirect method (GET for PayTheFly).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Get redirect data (empty for GET redirect).
     *
     * @return null
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * Get the transaction reference (serial number).
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        return $this->data['serialNo'] ?? null;
    }
}
