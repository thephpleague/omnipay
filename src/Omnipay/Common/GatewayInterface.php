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
     * Get gateway short name
     *
     * This name can be used with GatewayFactory as an alias of the gateway class,
     * to create new instances of this gateway.
     */
    public function getShortName();

    /**
     * Define gateway parameters, in the following format:
     *
     * array(
     *     'username' => '', // string variable
     *     'testMode' => false, // boolean variable
     *     'landingPage' => array('billing', 'login'), // enum variable, first item is default
     * );
     */
    public function getDefaultParameters();

    /**
     * Initialize gateway with parameters
     */
    public function initialize(array $paramters = array());

    /**
     * Get all gateway parameters
     *
     * @return array
     */
    public function getParameters();

    /**
     * Authorize a new payment.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function authorize(array $parameters = array());

    /**
     * Handle return from an off-site authorization request.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function completeAuthorize(array $parameters = array());

    /**
     * Capture an authorized payment.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function capture(array $parameters = array());

    /**
     * Create a new charge (combined authorize + capture).
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function purchase(array $parameters = array());

    /**
     * Handle return from an off-site purchase request.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function completePurchase(array $parameters = array());

    /**
     * Refund an existing transaction.
     *
     * This will refund a transaction which has been already submitted for processing,
     * and generally may be called up to 30 days after submitting the transaction.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function refund(array $parameters = array());

    /**
     * Void an existing transaction.
     *
     * This will prevent a transaction from being submitted for processing,
     * and can generally only be called up to 24 hours after submitting the transaction.
     *
     * @param array An array of options
     * @return Omnipay\ResponseInterface
     */
    public function void(array $parameters = array());
}
