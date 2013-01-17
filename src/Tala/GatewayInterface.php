<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

use Tala\Exception\UnsupportedOperationException;
use Tala\Request;

/**
 * Payment gateway interface
 *
 * @author Adrian Macneil <adrian@adrianmacneil.com>
 * @author Alexander Deruwe <alexander.deruwe@gmail.com>
 */
interface GatewayInterface
{
    /**
     * Initialize the gateway with an associative array of settings.
     *
     * @param array $settings Associative array of settings
     */
    public function initialize($settings);

    /**
     * Get settings which can be displayed for user configuration.
     *
     * @return array
     */
    public function getDefaultSettings();

    /**
     * Authorizes a new payment.
     *
     * @param Request $request Payment request
     * @param mixed   $source  Source
     *
     * @throws UnsupportedOperationException If the operation is not supported
     *
     * @return Response
     */
    public function authorize(Request $request, $source);

    /**
     * Handles return from an authorization.
     *
     * @param Request $request Payment request
     *
     * @throws UnsupportedOperationException If the operation is not supported
     *
     * @return Response
     */
    public function completeAuthorize(Request $request);

    /**
     * Capture an authorized payment.
     *
     * @param Request $request Payment request
     *
     * @throws UnsupportedOperationException If the operation is not supported
     *
     * @return Response
     */
    public function capture(Request $request);

    /**
     * Creates a new charge (combined authorize + capture).
     *
     * @param Request $request Payment request
     * @param mixed   $source  Source
     *
     * @throws UnsupportedOperationException If the operation is not supported
     *
     * @return Response
     */
    public function purchase(Request $request, $source);

    /**
     * Handle return from a purchase.
     *
     * @param Request $request Payment request
     *
     * @throws UnsupportedOperationException If the operation is not supported
     *
     * @return Response
     */
    public function completePurchase(Request $request);

    /**
     * Refund an existing transaction.
     * Generally this will refund a transaction which has been already submitted for processing,
     * and may be called up to 30 days after submitting the transaction.
     *
     * @param Request $request Payment request
     *
     * @throws UnsupportedOperationException If the operation is not supported
     *
     * @return Response
     */
    public function refund(Request $request);

    /**
     * Void an existing transaction.
     * Generally this will prevent the transaction from being submitted for processing,
     * and can only be called up to 24 hours after submitting the transaction.
     *
     * @param Request $request Payment request
     *
     * @throws UnsupportedOperationException If the operation is not supported
     *
     * @return Response
     */
    public function void(Request $request);
}
