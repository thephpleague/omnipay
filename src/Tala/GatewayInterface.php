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

use Tala\Request;

/**
 * Payment gateway interface
 */
interface GatewayInterface
{
    /**
     * Authorize a new payment.
     *
     * @param array An array of options
     * @return Tala\ResponseInterface
     */
    public function authorize($options);

    /**
     * Handle return from an off-site authorization request.
     *
     * @param array An array of options
     * @return Tala\ResponseInterface
     */
    public function completeAuthorize($options);

    /**
     * Capture an authorized payment.
     *
     * @param array An array of options
     * @return Tala\ResponseInterface
     */
    public function capture($options);

    /**
     * Create a new charge (combined authorize + capture).
     *
     * @param array An array of options
     * @return Tala\ResponseInterface
     */
    public function purchase($options);

    /**
     * Handle return from an off-site purchase request.
     *
     * @param array An array of options
     * @return Tala\ResponseInterface
     */
    public function completePurchase($options);

    /**
     * Refund an existing transaction.
     *
     * This will refund a transaction which has been already submitted for processing,
     * and generally may be called up to 30 days after submitting the transaction.
     *
     * @param array An array of options
     * @return Tala\ResponseInterface
     */
    public function refund($options);

    /**
     * Void an existing transaction.
     *
     * This will prevent a transaction from being submitted for processing,
     * and can generally only be called up to 24 hours after submitting the transaction.
     *
     * @param array An array of options
     * @return Tala\ResponseInterface
     */
    public function void($options);
}
