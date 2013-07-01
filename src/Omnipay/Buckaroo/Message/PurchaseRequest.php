<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Buckaroo\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Buckaroo Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://payment.buckaroo.nl/sslplus/request_for_authorization.asp';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getData()
    {
        $this->validate('merchantId', 'secret', 'amount', 'returnUrl');

        $data = array();
        $data['BPE_Merchant'] = $this->getMerchantId();
        $data['BPE_Amount'] = $this->getAmountInteger();
        $data['BPE_Currency'] = $this->getCurrency();
        $data['BPE_Language'] = 'EN';
        $data['BPE_Mode'] = (int) $this->getTestMode();
        $data['BPE_Invoice'] = $this->getTransactionId();
        $data['BPE_Return_Success'] = $this->getReturnUrl();
        $data['BPE_Return_Reject'] = $this->getReturnUrl();
        $data['BPE_Return_Error'] = $this->getReturnUrl();
        $data['BPE_Return_Method'] = 'POST';
        $data['BPE_Signature2'] = $this->generateSignature($data);

        return $data;
    }

    public function generateSignature($data)
    {
        return md5(
            $data['BPE_Merchant'].
            $data['BPE_Invoice'].
            $data['BPE_Amount'].
            $data['BPE_Currency'].
            $data['BPE_Mode'].
            $this->getSecret()
        );
    }

    public function send()
    {
        return $this->response = new PurchaseResponse($this, $this->getData());
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
