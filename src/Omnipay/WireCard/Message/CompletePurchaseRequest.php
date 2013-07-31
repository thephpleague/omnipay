<?php

namespace Omnipay\WireCard\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * WireCard Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $callbackPw = (string) $this->httpRequest->request->get('callbackPW');
        if ($callbackPw !== $this->getCallbackPassword()) {
            throw new InvalidResponseException("Invalid callback password for wirecard");
        }

        return $this->httpRequest->request->all();
    }

    public function send()
    {
        //return $this->response = new CompletePurchaseResponse($this, $this->getData());
        return $this->protoTypeSend();
    }

    protected function protoTypeSend()
    {
        $curl = getCurl();
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    protected function getCurl()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->getDataStorageInit());
        curl_setopt($curl, CURLOPT_PORT, 443);
        curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getPostFieldsAsString());
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        return $curl;

    }

    protected function getDataStorageInit()
    {
        return 'some/url';
    }

    protected function getPostFieldsAsString()
    {
        $postFields = "";
        $postFields .= "customerId=" . $this->getCustomerId();
        $postFields .= "&shopId=" . $this->getShopId();
        $postFields .= "&orderIdent=" . $orderIdent;
        $postFields .= "&returnUrl=" . $this->getReturnUrl();
        $postFields .= "&language=" . $this->getLanguage();
        $postFields .= "&requestFingerprint=" . $this->getRequestFingerPrint();
        return $postFields;

    }

    protected function getCustomerId()
    {
        return 12345;
    }

    protected function getShopId()
    {
        return 123;
    }

    protected function getReturnUrl()
    {
        return 'some/url';
    }

    protected function getLanguage()
    {
        return 'en';
    }

    protected function getRequestFingerPrint()
    {
        $requestFingerprintSeed  = "";
        $requestFingerprintSeed  .= $customerId;
        $requestFingerprintSeed  .= $shopId;
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-";
        $randomString = "";
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        $_SESSION["orderIdent"] = $randomString;
        $orderIdent = $_SESSION["orderIdent"];
        $requestFingerprintSeed  .= $orderIdent;

        $returnURL = "some/frontend/fallback_return.php";
        $requestFingerprintSeed  .= $returnURL;

        $language = "en";
        $requestFingerprintSeed  .= $language;

        $javascriptScriptVersion = ""; // version can be an empty string
        $requestFingerprintSeed  .= $javascriptScriptVersion;

        $requestFingerprintSeed  .= $secret;

        $requestFingerprint = hash("sha512", $requestFingerprintSeed);
        return $requestFingerPrint;

    }

}
