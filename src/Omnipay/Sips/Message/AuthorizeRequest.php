<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Sips\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\AbstractRequest;

class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');

        $this->getCard()->validate();

        return array('amount' => $this->getAmount());
    }

    public function send()
    {
        $params = self::getSipsParamString($this);
        $path_bin = '/var/www/app/config/sips/bin/request';
        $result = exec("$path_bin $params");

        return $this->response = new AuthorizeResponse($this, $result);
    }

    protected static function getSipsParamString(\Omnipay\Sips\Message\AuthorizeRequest $request)
    {
        $params = 'merchant_id=014141675911111';
        $params .= " pathfile=/var/www/app/config/sips/param/pathfile";
        $params .= " amount=" . $request->getAmountInteger();
        $params .= " currency_code=" . $request->getCurrencyNumeric();
        $params .= " transaction_id=" . $request->getTransactionId();

        /** @var CreditCard  $cart */
        $cart = $request->getCard();

        $params .= " customer_email=" . $cart->getEmail();
        $params .= " customer_ip_address=" . $request->getClientIp();

        $cartParams = self::getSipsCartString($request);
        $params .= " caddie=" . $cartParams;

        //$SUPERID = $parameters['sessionId'];
        //$parm .= " cancel_return_url=http://www.monsite.com/response.php?SUPERID=" . $SUPERID;
        //
        //// url réponse automatique
        //$parm .= " automatic_response_url=http://www.monsite.com/call_autoresponse.php";
        //
        ////url de retour du client après le paiement
        //$parm .= " normal_return_url=http://www.monsite.com/response.php?SUPERID=" . $SUPERID;

        return trim($params);
    }

    protected static function getSipsCartString(\Omnipay\Sips\Message\AuthorizeRequest $request)
    {
        $cartParams = array();

        // User info
        $cartParams[] = $request->getClientIp();

        /** @var CreditCard  $cart */
        $cart = $request->getCard();

        $cartParams[] = $cart->getBillingFirstName();
        $cartParams[] = $cart->getBillingLastName();

        $cartParams[] = $cart->getBillingCompany();
        $cartParams[] = $cart->getBillingAddress1().' '.$cart->getBillingAddress2();

        $cartParams[] = $cart->getBillingCity();
        $cartParams[] = $cart->getBillingPostcode();
        $cartParams[] = $cart->getBillingCountry();


        $cartParams[] = $cart->getBillingPhone();
        $cartParams[] = $cart->getEmail();

        $cartParams[] = $request->getTransactionId();

        $cartParams[] = 'TbcEboutique';
        $cartParams[] = 'vDev';

        $cartParams[] = $request->getAmountInteger();

        return trim(base64_encode(serialize($cartParams)));
    }
}
