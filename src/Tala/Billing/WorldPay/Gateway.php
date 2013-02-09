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
    protected $installationId;
    protected $secretWord;
    protected $callbackPassword;
    protected $testMode;

    public function getName()
    {
        return 'WorldPay';
    }

    public function defineSettings()
    {
        return array(
            'installationId' => '',
            'secretWord' => '',
            'callbackPassword' => '',
            'testMode' => false,
        );
    }

    public function getInstallationId()
    {
        return $this->installationId;
    }

    public function setInstallationId($value)
    {
        $this->installationId = $value;
    }

    public function getSecretWord()
    {
        return $this->secretWord;
    }

    public function setSecretWord($value)
    {
        $this->secretWord = $value;
    }

    public function getCallbackPassword()
    {
        return $this->callbackPassword;
    }

    public function setCallbackPassword($value)
    {
        $this->callbackPassword = $value;
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

        return new RedirectResponse($this->getCurrentEndpoint().'?'.http_build_query($data));
    }

    public function completePurchase($options)
    {
        $callbackPW = (string) $this->httpRequest->get('callbackPW');
        if ($callbackPW != $this->callbackPassword) {
            throw new InvalidResponseException;
        }

        $transStatus = $this->httpRequest->get('transStatus');
        if (empty($transStatus)) {
            throw new InvalidResponseException;
        } elseif ($transStatus != 'Y') {
            throw new Exception($this->httpRequest->get('rawAuthMessage'));
        } else {
            $gatewayReference = $this->httpRequest->get('transId');

            return new Response($gatewayReference);
        }
    }

    protected function buildPurchase($options)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'returnUrl'));

        $data = array();
        $data['instId'] = $this->installationId;
        $data['cartId'] = $request->getTransactionId();
        $data['desc'] = $request->getDescription();
        $data['amount'] = $request->getAmountDollars();
        $data['currency'] = $request->getCurrency();
        $data['testMode'] = $this->testMode ? 100 : 0;
        $data['MC_callback'] = $request->getReturnUrl();

        $source = $request->getCard();
        if ($source) {
            $data['name'] = $source->getName();
            $data['address1'] = $source->getAddress1();
            $data['address2'] = $source->getAddress2();
            $data['town'] = $source->getCity();
            $data['region'] = $source->getState();
            $data['postcode'] = $source->getPostcode();
            $data['country'] = $source->getCountry();
            $data['tel'] = $source->getPhone();
            $data['email'] = $source->getEmail();
        }

        if ($this->secretWord) {
            $data['signatureFields'] = 'instId:amount:currency:cartId';
            $signature_data = array($this->secretWord,
                $data['instId'], $data['amount'], $data['currency'], $data['cartId']);
            $data['signature'] = md5(implode(':', $signature_data));
        }

        return $data;
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }
}
