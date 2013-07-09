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

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Buckaroo Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $this->validate('merchantId', 'secret', 'amount');

        if (strtolower($this->httpRequest->request->get('bpe_signature2')) !== $this->generateResponseSignature()) {
            throw new InvalidRequestException('Incorrect signature');
        }

        return $this->httpRequest->request->all();
    }

    public function generateResponseSignature()
    {
        return md5(
            $this->httpRequest->request->get('bpe_trx').
            $this->httpRequest->request->get('bpe_timestamp').
            $this->getMerchantId().
            $this->getTransactionId().
            $this->getCurrency().
            $this->getAmountInteger().
            $this->httpRequest->request->get('bpe_result').
            (int) $this->getTestMode().
            $this->getSecret()
        );
    }

    public function send(array $datas = array(), $doMerge = true)
    {
        if($datas)
            $datas = $doMerge ?array_merge($this->getData(), $datas) :$datas;
        else
            $datas = $this->getData();

        return $this->response = new CompletePurchaseResponse($this, $datas);
    }
}
