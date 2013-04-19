<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Complete Authorize Request
 */
class ExpressCompleteAuthorizeRequest extends AbstractRequest
{
    protected $action = 'Authorization';

    public function getData()
    {
        $data = $this->getBaseData('DoExpressCheckoutPayment');

        $this->validate('amount');

        $data['PAYMENTREQUEST_0_PAYMENTACTION'] = $this->action;
        $data['PAYMENTREQUEST_0_AMT'] = $this->getAmountDecimal();
        $data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->getCurrency();
        $data['PAYMENTREQUEST_0_INVNUM'] = $this->getTransactionId();
        $data['PAYMENTREQUEST_0_DESC'] = $this->getDescription();

        $data['TOKEN'] = $this->httpRequest->query->get('token');
        $data['PAYERID'] = $this->httpRequest->query->get('PayerID');

        return $data;
    }

    public function getDetailsData()
    {
        $data = $this->getBaseData('GetExpressCheckoutDetails');

        $data['TOKEN'] = $this->httpRequest->query->get('token');
        $data['PAYERID'] = $this->httpRequest->query->get('PayerID');

        return $data;
    }

    public function send()
    {
        $paymentDoResponse = parent::send();

        $url = $this->getEndpoint().'?'.http_build_query($this->getDetailsData());
        $httpResponse = $this->httpClient->get($url)->send();
        $paymentInfoResponse =  $this->createResponse($httpResponse->getBody());

        $mergedData = array_merge($paymentDoResponse->getData(), $paymentInfoResponse->getData());

        return $this->createResponse(http_build_query($mergedData));
    }
}
