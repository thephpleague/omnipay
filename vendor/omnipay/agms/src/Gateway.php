<?php

namespace Omnipay\Agms;

use Omnipay\Common\AbstractGateway;
use Omnipay\Agms\Message\AuthorizeRequest;

/**
 * Agms Gateway
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the Agms Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('Agms');
 *
 *   // Initialise the gateway
 *   $gateway->setUsername('username');
 *   $gateway->setPassword('password');
 *   $gateway->setApiKey('api_key');
 *   $gateway->setAccountNumber('account_number');
 *
 *   // Create a credit card object
 *   // This card can be used for testing.
 *   $card = new CreditCard(array(
 *               'firstName'    => 'Example',
 *               'lastName'     => 'Customer',
 *               'number'       => '4242424242424242',
 *               'expiryMonth'  => '01',
 *               'expiryYear'   => '2020',
 *               'cvv'          => '123',
 *               'email'                 => 'customer@example.com',
 *               'billingAddress1'       => 'Address 1',
 *               'billingCountry'        => 'US',
 *               'billingCity'           => 'Dallas',
 *               'billingPostcode'       => '12345',
 *               'billingState'          => 'TX',
 *   ));
 *
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->purchase(array(
 *       'amount'                   => '10.00',
 *       'currency'                 => 'USD',
 *       'card'                     => $card,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
 *
 * Unlike some gateways, there is no test mode endpoint separate
 * to the live mode endpoint, the Agms API endpoint is the same
 * for test and for live.
 *
 * Setting the testMode flag on this gateway has no effect.  To
 * use test mode just use your test card data.
 * 
 * Authentication:
 *
 * Authentication is by means of a username and password
 * when creating the gateway object.
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\Agms\Message\AbstractRequest
 * @link http://onlinepaymentprocessing.com/docs/
 */

class Gateway extends AbstractGateway
{
    /**
     * Get the gateway name
     *
     * @return string
     */
    public function getName()
    {
        return 'Agms';
    }

    /**
     * Get the gateway short name
     *
     * @return string
     */
    public function getShortName()
    {
        return 'Agms';
    }

    /**
     * Get the gateway default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'apiKey' => '',
            'accountNumber' => ''
        );
    }

    /**
     * Get the gateway username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the gateway username
     *
     * @return AbstractGateway
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the gateway password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the gateway password
     *
     * @return AbstractGateway
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get the gateway api key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway api key
     *
     * @return AbstractGateway
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * Get the gateway account number
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    /**
     * Set the gateway account number
     *
     * @return AbstractGateway
     */
    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\AuthorizeRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\CaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\CaptureRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\RefundRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\VoidRequest
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\VoidRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\CreateCardRequest
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\CreateCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\UpdateCardRequest
     */
    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\UpdateCardRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Agms\Message\DeleteCardRequest
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Agms\Message\DeleteCardRequest', $parameters);
    }
    
}
