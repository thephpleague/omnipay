<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Realex\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Realex Redirect Complete Purchase Response
 */
class RedirectCompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        $data = $this->getData();

        return isset($data['RESULT']) && $data['RESULT'] == 0;
    }

    public function getTransactionReference()
    {
        $data = $this->getData();

        return isset($data['PASREF']) ? $data['PASREF'] : null;
    }
}
