<?php
/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\NetBanx\Message;

/**
 * NetBanx 'StoredData' Purchase Request
 */
class StoredDataPurchaseRequest extends StoredDataAuthorizeRequest
{
    /**
     * Method
     *
     * @var string
     */
    protected $txnMode = 'ccStoredDataPurchase';
}
