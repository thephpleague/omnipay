<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\WorldPay;

use Tala\AbstractGateway;
use Tala\Exception;
use Tala\Exception\InvalidResponseException;
use Tala\Exception\UnsupportedOperationException;
use Tala\RedirectResponse;
use Tala\Request;
use Tala\Response;

/**
 * WorldPay Gateway
 *
 * @link http://www.worldpay.com/support/kb/bg/htmlredirect/rhtml.html
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://secure.worldpay.com/wcc/purchase';
    protected $testEndpoint = 'https://secure-test.worldpay.com/wcc/purchase';

    public function getDefaultSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'callbackPassword' => '',
            'testMode' => false,
        );
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildPurchase($request, $source);

        return new RedirectResponse($this->getCurrentEndpoint().'?'.http_build_query($data));
    }

    public function completePurchase(Request $request)
    {
        $callbackPW = (string) $this->getHttpRequest()->get('callbackPW');
        if ($callbackPW != $this->callbackPassword) {
            throw new InvalidResponseException;
        }

        $transStatus = $this->getHttpRequest()->get('transStatus');
        if (empty($transStatus)) {
            throw new InvalidResponseException;
        } elseif ($transStatus != 'Y') {
            throw new Exception($this->getHttpRequest()->get('rawAuthMessage'));
        } else {
            $gatewayReference = $this->getHttpRequest()->get('transId');

            return new Response($gatewayReference);
        }
    }

    protected function buildPurchase(Request $request, $source)
    {
        $request->validateRequired(array('amount', 'returnUrl'));

        $data = array();
        $data['instId'] = $this->username;
        $data['cartId'] = $request->orderId;
        $data['desc'] = $request->description;
        $data['amount'] = $request->amountDollars;
        $data['currency'] = $request->currency;
        $data['testMode'] = $this->testMode ? 100 : 0;
        $data['MC_callback'] = $request->returnUrl;
        $data['name'] = $source->name;
        $data['address1'] = $source->address1;
        $data['address2'] = $source->address2;
        $data['town'] = $source->city;
        $data['region'] = $source->state;
        $data['postcode'] = $source->postcode;
        $data['country'] = $source->country;
        $data['tel'] = $source->phone;
        $data['email'] = $source->email;

        if ($this->password) {
            $data['signatureFields'] = 'instId:amount:currency:cartId';
            $signature_data = array($this->password,
                $data['instId'], $data['amount'], $data['currency'], $data['cartId']);
            $data['signature'] = md5(implode(':', $signature_data));
        }

        return $data;
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(Request $request, $source)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function completeAuthorize(Request $request)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function capture(Request $request)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function refund(Request $request)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * {@inheritdoc}
     */
    public function void(Request $request)
    {
        throw new UnsupportedOperationException();
    }
}
