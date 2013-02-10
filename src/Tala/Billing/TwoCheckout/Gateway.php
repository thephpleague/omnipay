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

/**
 * 2Checkout Gateway
 *
 * @link http://www.2checkout.com/documentation/Advanced_User_Guide.pdf
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://www.2checkout.com/checkout/purchase';
    protected $username;
    protected $password;
    protected $testMode;

    public function getName()
    {
        return '2Checkout';
    }

    public function defineSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function purchase($options)
    {
        $data = $this->buildPurchase($options);

        return new RedirectResponse($this->endpoint.'?'.http_build_query($data));
    }

    public function completePurchase($options)
    {
        $request = new Request($options);
        $orderNo = $this->httpRequest->get('order_number');

        // strange exception specified by 2Checkout
        if ($this->testMode) {
            $orderNo = '1';
        }

        $key = strtoupper(md5($this->password.$this->username.$orderNo.$request->getAmountDollars()));
        if ($key != $this->httpRequest->get('key')) {
            throw new InvalidResponseException;
        }

        return new Response($orderNo);
    }

    protected function buildPurchase($options)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'returnUrl'));
        $source = $request->getCard();

        $data = array();
        $data['sid'] = $this->username;
        $data['cart_order_id'] = $request->getTransactionId();
        $data['total'] = $request->getAmountDollars();
        $data['tco_currency'] = $request->getCurrency();
        $data['fixed'] = 'Y';
        $data['skip_landing'] = 1;
        $data['x_receipt_link_url'] = $request->getReturnUrl();

        if ($source) {
            $data['card_holder_name'] = $source->getName();
            $data['street_address'] = $source->getAddress1();
            $data['street_address2'] = $source->getAddress2();
            $data['city'] = $source->getCity();
            $data['state'] = $source->getState();
            $data['zip'] = $source->getPostcode();
            $data['country'] = $source->getCountry();
            $data['phone'] = $source->getPhone();
            $data['email'] = $source->getEmail();
        }

        if ($this->testMode) {
            $data['demo'] = 'Y';
        }

        return $data;
    }
}
