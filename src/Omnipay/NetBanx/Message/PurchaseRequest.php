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
 * NetBanx Purchase Request
 */
class PurchaseRequest extends AuthorizeRequest
{
    const MODE_PURCHASE = 'ccPurchase';
    const MODE_STORED_DATA_PURCHASE = 'ccStoredDataPurchase';

    /**
     * @inheritdoc
     */
    protected function getStoredDataMode()
    {
        return self::MODE_STORED_DATA_PURCHASE;
    }

    /**
     * @inheritdoc
     */
    protected function getBasicMode()
    {
        return self::MODE_PURCHASE;
    }
}
