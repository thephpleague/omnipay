<?php
/**
 * Tpay Abstract Request
 */

namespace Omnipay\Tpay\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

abstract class AbstractRequest extends OmnipayAbstractRequest
{
    protected $apiEndpoint = 'https://secure.tpay.com/api/cards/';

    protected $supportedLanguages = array('pl', 'en', 'fr', 'es', 'it', 'ru');

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getApiPassword()
    {
        return $this->getParameter('apiPassword');
    }

    public function setApiPassword($value)
    {
        return $this->setParameter('apiPassword', $value);
    }

    public function getVerificationCode()
    {
        return $this->getParameter('verificationCode');
    }

    public function setVerificationCode($value)
    {
        return $this->setParameter('verificationCode', $value);
    }

    public function getRsaKey()
    {
        return $this->getParameter('rsaKey');
    }

    public function setRsaKey($value)
    {
        return $this->setParameter('rsaKey', $value);
    }

    public function getHashType()
    {
        return $this->getParameter('hashType');
    }

    public function setHashType($value)
    {
        return $this->setParameter('hashType', $value);
    }

    public function getCurrentDomain()
    {
        return $this->getParameter('currentDomain');
    }

    public function setCurrentDomain($value)
    {
        return $this->setParameter('currentDomain', $value);
    }

    /**
     * @param $value - can be 'pl', 'en', 'fr', 'es', 'it', 'ru'
     * @return OmnipayAbstractRequest
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setCardSave()
    {
        return $this->setParameter('cardSave', true);
    }

    public function getCardSave()
    {
        return $this->getParameter('cardSave');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    /**
     * @param array $data
     * @return Response
     */
    public function sendData($data)
    {
        $data['json'] = 1;
        $httpRequest = $this->httpClient->post($this->getEndpoint(), null, http_build_query($data));
        $httpRequest->getCurlOptions();
        foreach ($data as $key => $value) {
            $httpRequest->setPostField($key, $value);
        }
        $httpResponse = $httpRequest->send();

        return $this->createResponse($httpResponse->getBody());
    }

    public function getToken()
    {
        return $this->getParameter('cli_auth');
    }

    public function setToken($value)
    {
        return $this->setParameter('cli_auth', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('sale_auth');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('sale_auth', $value);
    }

    protected function getEndpoint()
    {
        return $this->apiEndpoint . $this->getApiKey();
    }

    /**
     * @param array $data
     * @return string
     */
    public function getSign($data)
    {
        $string = '';
        foreach ($data as $key => $value) {
            $string .= $value;
        }
        $string .= $this->getVerificationCode();

        return hash($this->getHashType(), $string);

    }


    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

}
