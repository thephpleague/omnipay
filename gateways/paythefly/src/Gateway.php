<?php

/**
 * PayTheFly Gateway.
 *
 * PayTheFly is a crypto payment gateway supporting BSC and TRON chains.
 * It uses EIP-712 typed structured data signing for payment request authentication.
 *
 * @link https://pro.paythefly.com
 */

namespace Omnipay\PayTheFly;

use Omnipay\Common\AbstractGateway;

/**
 * PayTheFly Gateway.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for PayTheFly
 *   $gateway = Omnipay::create('PayTheFly');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'projectId'  => 'your-project-id',
 *       'projectKey' => getenv('PAYTHEFLY_PROJECT_KEY'),
 *       'privateKey' => getenv('PAYTHEFLY_PRIVATE_KEY'),
 *       'chainId'    => 56, // BSC mainnet
 *   ));
 *
 *   // Create a purchase request
 *   $response = $gateway->purchase(array(
 *       'amount'   => '10.00',     // Human-readable amount (NOT raw units)
 *       'token'    => '0x55d398326f99059fF775485246999027B3197955', // USDT on BSC
 *       'serialNo' => 'ORDER-12345',
 *   ))->send();
 *
 *   if ($response->isRedirect()) {
 *       $response->redirect(); // Redirect to PayTheFly payment page
 *   }
 * </code>
 *
 * Supported Chains:
 * - BSC (chainId=56, 18 decimals)
 * - TRON (chainId=728126428, 6 decimals)
 *
 * @link https://pro.paythefly.com
 */
class Gateway extends AbstractGateway
{
    /**
     * Get gateway display name.
     *
     * @return string
     */
    public function getName()
    {
        return 'PayTheFly';
    }

    /**
     * Get gateway default parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'projectId'  => '',
            'projectKey' => '',
            'privateKey' => '',
            'chainId'    => 56,
        );
    }

    /**
     * Get project ID.
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->getParameter('projectId');
    }

    /**
     * Set project ID.
     *
     * @param string $value
     * @return $this
     */
    public function setProjectId($value)
    {
        return $this->setParameter('projectId', $value);
    }

    /**
     * Get project key (used for webhook HMAC verification).
     * Should be loaded from environment variables.
     *
     * @return string
     */
    public function getProjectKey()
    {
        return $this->getParameter('projectKey');
    }

    /**
     * Set project key.
     *
     * @param string $value
     * @return $this
     */
    public function setProjectKey($value)
    {
        return $this->setParameter('projectKey', $value);
    }

    /**
     * Get private key for EIP-712 signing.
     * Should be loaded from environment variables.
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    /**
     * Set private key.
     *
     * @param string $value
     * @return $this
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    /**
     * Get chain ID.
     * BSC = 56, TRON = 728126428
     *
     * @return int
     */
    public function getChainId()
    {
        return $this->getParameter('chainId');
    }

    /**
     * Set chain ID.
     *
     * @param int $value
     * @return $this
     */
    public function setChainId($value)
    {
        return $this->setParameter('chainId', $value);
    }

    /**
     * Create a purchase request.
     *
     * Generates a PayTheFly payment URL with EIP-712 signature.
     *
     * @param array $parameters
     * @return \Omnipay\PayTheFly\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTheFly\Message\PurchaseRequest', $parameters);
    }

    /**
     * Handle webhook notification (completePurchase).
     *
     * Verifies the HMAC-SHA256 signature of incoming webhook data
     * and returns the payment status.
     *
     * @param array $parameters
     * @return \Omnipay\PayTheFly\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTheFly\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * Handle incoming webhook notification.
     *
     * @param array $parameters
     * @return \Omnipay\PayTheFly\Message\WebhookRequest
     */
    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayTheFly\Message\WebhookRequest', $parameters);
    }
}
