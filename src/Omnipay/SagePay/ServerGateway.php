<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Sage Pay Server Gateway
 */
class ServerGateway extends DirectGateway
{
    public function getName()
    {
        return 'Sage Pay Server';
    }

    public function authorize($options = null)
    {
        $request = new Request($options);
        $data = $this->buildServerAuthorizeOrPurchase($request, 'DEFERRED');

        return $this->send('DEFERRED', $data, $request);
    }

    public function completeAuthorize($options = null)
    {
        return $this->completePurchase($options);
    }

    public function purchase($options = null)
    {
        $request = new Request($options);
        $data = $this->buildServerAuthorizeOrPurchase($request, 'PAYMENT');

        return $this->send('PAYMENT', $data, $request);
    }

    public function completePurchase($options = null)
    {
        $request = new Request($options);
        $request->validate(array('transactionId', 'gatewayReference'));

        $reference = json_decode($request->getGatewayReference(), true);

        // validate VPSSignature
        $signature = md5(
            $reference['VPSTxId'].
            $reference['VendorTxCode'].
            $this->httpRequest->request->get('Status').
            $this->httpRequest->request->get('TxAuthNo').
            $this->vendor.
            $this->httpRequest->request->get('AVSCV2').
            $reference['SecurityKey'].
            $this->httpRequest->request->get('AddressResult').
            $this->httpRequest->request->get('PostCodeResult').
            $this->httpRequest->request->get('CV2Result').
            $this->httpRequest->request->get('GiftAid').
            $this->httpRequest->request->get('3DSecureStatus').
            $this->httpRequest->request->get('CAVV').
            $this->httpRequest->request->get('AddressStatus').
            $this->httpRequest->request->get('PayerStatus').
            $this->httpRequest->request->get('CardType').
            $this->httpRequest->request->get('Last4Digits')
        );

        if (strtolower($this->httpRequest->request->get('VPSSignature')) !== $signature) {
            throw new InvalidResponseException;
        }

        // add reference to response data so we can still generate gateway reference
        $data = array_merge($reference, $this->httpRequest->request->all());

        return new Response($data, $request);
    }

    protected function buildServerAuthorizeOrPurchase(Request $request, $method)
    {
        $request->validate(array('returnUrl'));

        $data = $this->buildAuthorizeOrPurchase($request, $method);
        $data['NotificationURL'] = $request->getReturnUrl();

        return $data;
    }

    protected function getCurrentEndpoint($service)
    {
        $service = strtolower($service);
        if ($service == 'payment' || $service == 'deferred') {
            $service = 'vspserver-register';
        }

        return parent::getCurrentEndpoint($service);
    }
}
