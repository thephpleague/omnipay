<?php

namespace Omnipay\Myfatoorah\Message;

use Guzzle\Http\Exception\ClientErrorResponseException;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest {

    protected $sandboxEndpoint    = "https://apitest.myfatoorah.com";
    protected $productionEndpoint = "https://api.myfatoorah.com";

    public function getApiKey() {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value) {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * Get the gateway Test mode
     *
     * @return string
     */
    public function getTestMode() {
        return $this->getParameter('testMode');
    }

    /**
     * Set the gateway Test mode
     *
     * @param  string $value
     * @return Gateway provides a fluent interface.
     */
    public function setTestMode($value) {
        return $this->setParameter('testMode', $value);
    }

    public function getAmount() {
        return $this->getParameter('Amount');
    }

    public function setAmount($value) {
        return $this->setParameter('Amount', $value);
    }

    public function getCurrency() {
        return $this->getParameter('Currency');
    }

    public function setCurrency($value) {
        return $this->setParameter('Currency', $value);
    }

    public function getCard() {
        return $this->getParameter('Card');
    }

    public function setCard($value) {
        return $this->setParameter('Card', $value);
    }

    public function getFirstName() {
        $card = $this->getCard();
        return $card['firstName'];
    }

    public function setFirstName($value) {
        $card              = $this->getCard();
        return $card['firstName'] = $value;
    }

    public function getLastName() {
        $card = $this->getCard();
        return $card['lastName'];
    }

    public function setLastName($value) {
        $card             = $this->getCard();
        return $card['lastName'] = $value;
    }

    public function getEmail() {
        $card = $this->getCard();
        return $card['email'];
    }

    public function setEmail($value) {
        $card          = $this->getCard();
        return $card['email'] = $value;
    }

    public function getPhone() {
        $card = $this->getCard();
        return $card['phone'];
    }

    public function setPhone($value) {
        $card          = $this->getCard();
        return $card['phone'] = $value;
    }

    public function getCallBackUrl() {
        return $this->getParameter('returnUrl');
    }

    public function setCallBackUrl($value) {
        return $this->getParameter('returnUrl', $value);
    }

    public function getOrderRef() {
        return $this->getParameter('OrderRef');
    }

    public function setOrderRef($value) {
        return $this->setParameter('OrderRef', $value);
    }

    public function getHeaders() {
        $headers = [];

        return $headers;
    }

    public function send() {
        $data    = $this->getData();
        $headers = array_merge(
                $this->getHeaders(),
                ['Authorization' => 'Bearer ' . $this->getApiKey(), 'Content-Type' => ' application/json']
        );

        return $this->sendData($data, $headers);
    }

    public function sendData($data, array $headers = null) {
        if (sizeof($data) == 0) {
            $postFields = null;
        } else {
            $postFields = json_encode($data);
        }
        try {
            $httpResponse = $this->httpClient->request(
                    $this->getHttpMethod(),
                    $this->getEndpoint(),
                    $headers,
                    $postFields
            );
        } catch (ClientErrorResponseException $e) {
            $httpResponse = $e->getResponse();
        }
//                print_r($httpResponse->getBody()->getContents()); die;

        return (new Response($this, $httpResponse));
    }

    public function getHttpMethod() {
        return 'POST';
    }

    abstract public function getEndpoint();
}
