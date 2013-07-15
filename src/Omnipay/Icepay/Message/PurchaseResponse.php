<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        return $this->request->getEndpoint();
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return $this->data;
    }
}
