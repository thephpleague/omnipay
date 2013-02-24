<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common;

use Guzzle\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Payment gateway interface
 */
interface GatewayInterface
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName();

    /**
     * Define gateway settings, in the following format:
     *
     * array(
     *     'username' => '', // string variable
     *     'testMode' => false, // boolean variable
     *     'landingPage' => array('billing', 'login'), // enum variable, first item is default
     * );
     */
    public function defineSettings();

    public function getHttpClient();

    public function setHttpClient(ClientInterface $value);

    public function getHttpRequest();

    public function setHttpRequest(HttpRequest $value);

    /**
     * Authorize a new payment.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function authorize(array $options = null);

    /**
     * Handle return from an off-site authorization request.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function completeAuthorize(array $options = null);

    /**
     * Capture an authorized payment.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function capture(array $options = null);

    /**
     * Create a new charge (combined authorize + capture).
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function purchase(array $options = null);

    /**
     * Handle return from an off-site purchase request.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function completePurchase(array $options = null);

    /**
     * Refund an existing transaction.
     *
     * This will refund a transaction which has been already submitted for processing,
     * and generally may be called up to 30 days after submitting the transaction.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function refund(array $options = null);

    /**
     * Void an existing transaction.
     *
     * This will prevent a transaction from being submitted for processing,
     * and can generally only be called up to 24 hours after submitting the transaction.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function void(array $options = null);
}
