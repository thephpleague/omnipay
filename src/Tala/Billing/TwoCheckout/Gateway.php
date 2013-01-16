<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\TwoCheckout;

use Tala\AbstractGateway;
use Tala\Exception\InvalidResponseException;
use Tala\RedirectResponse;
use Tala\Request;
use Tala\Response;

/**
 * 2Checkout Gateway
 *
 * @link http://www.2checkout.com/documentation/Advanced_User_Guide.pdf
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://www.2checkout.com/checkout/purchase';

    public function getDefaultSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildPurchase($request, $source);

        return new RedirectResponse($this->endpoint.'?'.http_build_query($data));
    }

    public function completePurchase(Request $request)
    {
        $orderNo = $this->getHttpRequest()->get('order_number');

        // strange exception specified by 2Checkout
        if ($this->testMode) {
            $orderNo = '1';
        }

        $key = strtoupper(md5($this->password.$this->username.$orderNo.$request->amountDollars));
        if ($key != $this->getHttpRequest()->get('key')) {
            throw new InvalidResponseException;
        }

        return new Response($orderNo);
    }

    protected function buildPurchase(Request $request, $source)
    {
        $request->validateRequired(array('amount', 'returnUrl'));

        $data = array();
        $data['sid'] = $this->username;
        $data['cart_order_id'] = $request->invoiceId;
        $data['total'] = $request->amountDollars;
        $data['tco_currency'] = $request->currency;
        $data['fixed'] = 'Y';
        $data['skip_landing'] = 1;
        $data['x_receipt_link_url'] = $request->returnUrl;

        $data['card_holder_name'] = $source->name;
        $data['street_address'] = $source->address1;
        $data['street_address2'] = $source->address2;
        $data['city'] = $source->city;
        $data['state'] = $source->region;
        $data['zip'] = $source->postcode;
        $data['country'] = $source->country;
        $data['phone'] = $source->phone;
        $data['email'] = $source->email;

        if ($this->testMode) {
            $data['demo'] = 'Y';
        }

        return $data;
    }
}
