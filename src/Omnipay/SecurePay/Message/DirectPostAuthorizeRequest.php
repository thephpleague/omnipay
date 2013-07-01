<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecurePay\Message;

/**
 * SecurePay Direct Post Authorize Request
 */
class DirectPostAuthorizeRequest extends DirectPostAbstractRequest
{
    public $txnType = '1';

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = array();
        $data['EPS_MERCHANT'] = $this->getMerchantId();
        $data['EPS_TXNTYPE'] = $this->txnType;
        $data['EPS_IP'] = $this->getClientIp();
        $data['EPS_AMOUNT'] = $this->getAmount();
        $data['EPS_REFERENCEID'] = $this->getTransactionId();
        $data['EPS_TIMESTAMP'] = gmdate('YmdHis');
        $data['EPS_FINGERPRINT'] = $this->generateFingerprint($data);
        $data['EPS_RESULTURL'] = $this->getReturnUrl();
        $data['EPS_CALLBACKURL'] = $this->getReturnUrl();
        $data['EPS_REDIRECT'] = 'TRUE';
        $data['EPS_CURRENCY'] = $this->getCurrency();

        return $data;
    }

    public function generateFingerprint(array $data)
    {
        $hash = implode(
            '|',
            array(
                $data['EPS_MERCHANT'],
                $this->getTransactionPassword(),
                $data['EPS_TXNTYPE'],
                $data['EPS_REFERENCEID'],
                $data['EPS_AMOUNT'],
                $data['EPS_TIMESTAMP'],
            )
        );

        return sha1($hash);
    }

    public function send()
    {
        return $this->response = new DirectPostAuthorizeResponse($this, $this->getData(), $this->getEndpoint());
    }
}
