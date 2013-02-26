<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * Sage Pay Server Complete Authorize Response
 */
class ServerCompleteAuthorizeResponse extends Response
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function getTransactionReference()
    {
        if (isset($this->data['TxAuthNo'])) {
            $reference = json_decode($this->getRequest()->getTransactionReference(), true);
            $reference['VendorTxCode'] = $this->getRequest()->getTransactionId();
            $reference['TxAuthNo'] = $this->data['TxAuthNo'];

            return json_encode($reference);
        }
    }

    /**
     * Confirm (Sage Pay Server only)
     *
     * Sage Pay Server does things backwards compared to every other gateway (including Sage Pay
     * Direct). The return URL is called by their server, and they expect you to confirm receipt
     * and then pass a URL for them to forward the customer to.
     *
     * Because of this, an extra step is required. In your return controller, after calling
     * $gateway->completePurchase(), you should update your database with details of the
     * successful payment. You must then call $response->confirm() to notify Sage Pay you
     * received the payment details, and provide a URL to forward the customer to.
     *
     * Keep in mind your original confirmPurchase() script is being called by Sage Pay, not
     * the customer.
     *
     * @param string URL to foward the customer to. Note this is different to your standard
     *               return controller action URL.
     */
    public function confirm($nextUrl)
    {
        exit("Status=OK\r\nRedirectUrl=".$nextUrl);
    }
}
