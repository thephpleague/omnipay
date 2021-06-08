<?php

/**
 * MyFatoorah Gateway
 */

namespace Omnipay\Myfatoorah;

use Omnipay\Common\AbstractGateway;

/**
 * MyFatoorah Gateway.
 *
 * Example:
 *
 * <code>

  // Create a gateway for the MyFatoorah Gateway
  $gateway      = Omnipay::create('Myfatoorah');
  $gateway->setApiKey('rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL');
  $gateway->setTestMode('true');// true in case of test token and empty '' in case of live token!
  // Create Invoice URL
  /*$data                      = array();
  $data['Amount']            = '50';
  $data['OrderRef']          = '5342652460';
  $data['Currency']          = 'KWD';
  $data['returnUrl']         = 'http://localomnipay.com/myfatoorah.php';
  $data['Card']['firstName'] = 'fname';
  $data['Card']['lastName']  = 'lname';
  $data['Card']['email']     = 'test@test.com';
  //
  // Do a purchase transaction on the gateway
  $transaction               = $gateway->purchase($data)->send();
  if ($transaction->isSuccessful()) {
  $invoiceId   = $transaction->getTransactionReference();
  echo "Invoice Id = " . $invoiceId . "<br>";
  $redirectUrl = $transaction->getRedirectUrl();
  echo "Redirect Url = <a href='$redirectUrl' target='_blank'>" . $redirectUrl . "</a><br>";
  } else {
  echo $transaction->getMessage();
  } */
// In the callback, Get Payment status for specific Payment ID
/* $callBackData = ['paymentId' => '100202113817903101'];
  $callback     = $gateway->completePurchase($callBackData)->send();
  if ($callback->isSuccessful()) {
  echo "<pre>";
  print_r($callback->getPaymentStatus('5342652460', '100202113817903101'));
  } else {
  echo $callback->getMessage();
  } */

// Refund specific Payment ID
/* $refundData = ['paymentId' => '100202113817903101', 'Amount'=>1];
  $refund     = $gateway->refund($refundData)->send();
  if ($refund->isSuccessful()) {
  echo "<pre>";
  print_r($refund->getRefundInfo());
  } else {
  echo $refund->getMessage();
  } */
/*
 * 
 * </code>
 *
 * @link https://developer.myfatoorah.com
 */
class Gateway extends AbstractGateway {

    public function getName() {
        return 'Myfatoorah';
    }

    /**
     * Get default parameters for this gateway
     *
     * @return void
     */
    public function getDefaultParameters() {
        return [
            'apiKey'   => '',
            'testMode' => false
        ];
    }

    /**
     * Get the gateway apiKey key
     *
     * @return string
     */
    public function getApiKey() {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway apiKey key
     *
     * @param  string $value
     * @return Gateway provides a fluent interface.
     */
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

    /**
     * completeAuthorize Request
     *
     *
     * @param  array|array $parameters
     * @return \Omnipay\Myfatoorah\Message\Response
     */
    public function purchase(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Myfatoorah\Message\CompleteAuthorizeRequest', $parameters);
    }

    /**
     * completePurchase Request
     *
     *
     * @param  array|array $parameters
     * @return \Omnipay\Myfatoorah\Message\Response
     */
    public function completePurchase(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Myfatoorah\Message\CompletePurchase', $parameters);
    }

    /**
     * refund Request
     *
     *
     * @param  array|array $parameters
     * @return \Omnipay\Myfatoorah\Message\Response
     */
    public function refund(array $parameters = array()) {
        return $this->createRequest('\Omnipay\Myfatoorah\Message\RefundRequest', $parameters);
    }

}
