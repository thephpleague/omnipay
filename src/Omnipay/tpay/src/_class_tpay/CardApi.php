<?php

/*
 * Created by tpay.com
 */

namespace Omnipay\Tpay\_class_tpay;

use Omnipay\Tpay\_class_tpay\PaymentOptions\CardOptions;
use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\_class_tpay\Utilities\Util;
use Omnipay\Tpay\_class_tpay\Validators\PaymentTypes\PaymentTypeCard;
use Omnipay\Tpay\Dictionaries\CardDictionary;

class CardApi extends CardOptions
{
    /**
     * Prepare for register sale @see $this->registerSale
     *
     * @param string $clientName client name
     * @param string $clientEmail client email
     * @param string $saleDescription sale description
     * @return bool|mixed
     */
    public function registerSaleMethod(
        $clientName,
        $clientEmail,
        $saleDescription
    ) {
        $params[CardDictionary::METHOD] = $this->method;
        if (!is_null($this->cardData)) {
            $params['card'] = $this->cardData;
        }
        $params = array_merge($params, array(
            CardDictionary::NAME   => $clientName,
            CardDictionary::EMAIL  => $clientEmail,
            CardDictionary::DESC   => $saleDescription,
            CardDictionary::AMOUNT => $this->amount,
        ));
        $params[CardDictionary::CURRENCY] = $this->currency;
        $params['order_id'] = $this->orderID;
        if ($this->oneTimer) {
            $params['onetimer'] = $this->oneTimer;
        }
        $params[CardDictionary::LANGUAGE] = $this->lang;
        if ($this->enablePowUrl) {
            $params['enable_pow_url'] = 1;
        }
        $params[CardDictionary::SIGN] = hash($this->cardHashAlg, implode('', $params) . $this->cardVerificationCode);
        $params[CardDictionary::APIPASS] = $this->cardApiPass;
        $params = array_merge($params, $this->checkReturnUrls());
        $this->validateConfig(new PaymentTypeCard(), $params);
        Util::log('Card request', print_r($params, true));

        return $this->requests($this->cardsApiURL . $this->cardApiKey, $params);
    }

    private function checkReturnUrls()
    {
        $params = array();
        if (filter_var($this->powUrl, FILTER_VALIDATE_URL)) {
            $params['pow_url'] = $this->powUrl;
        }
        if (filter_var($this->powUrlBlad, FILTER_VALIDATE_URL)) {
            $params['pow_url_blad'] = $this->powUrlBlad;
        }
        return $params;
    }

    /**
     * Method used to create new sale for payment on demand.
     * It can be called after receiving notification with cli_auth (see communication schema in register_sale method).
     * It cannot be used if oneTimer option was sent in register_sale or client has unregistered
     * (by link in email or by API).
     *
     * @param string $saleDescription sale description
     *
     * @return bool|mixed
     *
     * @throws TException
     */
    public function presaleMethod(
        $saleDescription
    ) {
        $params = array(
            CardDictionary::AMOUNT   => $this->amount,
            CardDictionary::METHOD   => CardDictionary::PRESALE,
            CardDictionary::CLIAUTH  => $this->clientAuthCode,
            CardDictionary::DESC     => $saleDescription,
            CardDictionary::CURRENCY => $this->currency,
            CardDictionary::ORDERID  => $this->orderID,
            CardDictionary::LANGUAGE => $this->lang,
        );
        $params[CardDictionary::SIGN] = hash($this->cardHashAlg, CardDictionary::PRESALE . $this->clientAuthCode .
            $saleDescription . $this->amount . $this->currency . $this->orderID . $this->lang .
            $this->cardVerificationCode);
        $params[CardDictionary::APIPASS] = $this->cardApiPass;
        Util::log('Pre sale params with hash ',
            print_r($params, true) . 'req url ' . $this->cardsApiURL . $this->cardApiKey);

        return $this->requests($this->cardsApiURL . $this->cardApiKey, $params);
    }

    /**
     * Method used to execute created sale with presale method. Sale defined with sale_auth can be executed only once.
     * If the method is called second time with the same parameters, system returns sale actual status - in parameter
     * status - done for correct payment and declined for rejected payment.
     * In that case, client card is not charged the second time.
     *
     * @param string $saleAuthCode sale auth code
     * @return bool|mixed
     * @throws TException
     */
    public function saleMethod(
        $saleAuthCode
    ) {
        if (strlen($saleAuthCode) !== 40) {
            throw new TException('invalid sale_auth code');
        }
        $params = array(
            CardDictionary::METHOD   => CardDictionary::SALE,
            CardDictionary::CLIAUTH  => $this->clientAuthCode,
            CardDictionary::SALEAUTH => $saleAuthCode,
        );
        $params[CardDictionary::SIGN] = hash($this->cardHashAlg, CardDictionary::SALE .
            $this->clientAuthCode . $saleAuthCode . $this->cardVerificationCode);
        $params[CardDictionary::APIPASS] = $this->cardApiPass;
        Util::log('Sale request params', print_r($params, true));

        return $this->requests($this->cardsApiURL . $this->cardApiKey, $params);
    }

    /**
     * Method used to deregister client card data from system.
     * Client can also do it himself from link in email after payment - if oneTimer was not set - in that case system
     * will sent notification. After successful deregistration Merchant can no more charge client's card
     *
     * @return array|bool
     */
    public function deregisterClient()
    {
        $params[CardDictionary::METHOD] = CardDictionary::DEREGISTER;
        $params[CardDictionary::CLIAUTH] = $this->clientAuthCode;
        $params[CardDictionary::LANGUAGE] = $this->lang;
        $params[CardDictionary::SIGN] = hash($this->cardHashAlg, implode('', $params) . $this->cardVerificationCode);
        $params[CardDictionary::APIPASS] = $this->cardApiPass;

        return $this->requests($this->cardsApiURL . $this->cardApiKey, $params);
    }
}
