<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;

/**
 * Sage Pay Abstract Request
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    protected $liveEndpoint = 'https://live.sagepay.com/gateway/service';
    protected $testEndpoint = 'https://test.sagepay.com/gateway/service';
    protected $simulatorEndpoint = 'https://test.sagepay.com/Simulator';

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }
    
    public function getFirstName(){
        return $this->getParameter('firstName');
    }
    
    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }
    
    public function getLastName(){
        return $this->getParameter('lastName');
    }
    
    public function setLastName($value)
    {
        return $this->setParameter('lastName', $value);
    }
    
    
    public function getBillingAddress1(){
        return $this->getParameter('billingAddress1');
    }
    
    public function setBillingAddress1($value)
    {
        return $this->setParameter('billingAddress1', $value);
    }
    
    public function getBillingAddress2(){
        return $this->getParameter('billingAddress2');
    }
    
    public function setBillingAddress2($value)
    {
        return $this->setParameter('billingAddress2', $value);
    }
    
    public function getBillingCity(){
        return $this->getParameter('billingCity');
    }
    
    public function setBillingCity($value)
    {
        return $this->setParameter('billingCity', $value);
    }
    
    public function getBillingPostcode(){
        return $this->getParameter('billingPostcode');
    }
    
    public function setBillingPostcode($value)
    {
        return $this->setParameter('billingPostcode', $value);
    }
    
    public function getBillingState(){
        return $this->getParameter('billingState');
    }
    
    public function setBillingState($value)
    {
        return $this->setParameter('billingState', $value);
    }
    
    public function getBillingPhone(){
        return $this->getParameter('billingPhone');
    }
    
    public function setBillingPhone($value)
    {
        return $this->setParameter('billingPhone', $value);
    }
    
    public function getBillingCountry(){
        return $this->getParameter('billingCountry');
    }
    
    public function setBillingCountry($value)
    {
        return $this->setParameter('billingCountry', $value);
    }
    
    public function getShippingAddress1(){
        return $this->getParameter('shippingAddress1');
    }
    
    public function setShippingAddress1($value)
    {
        return $this->setParameter('shippingAddress1', $value);
    }
    
    public function getShippingAddress2(){
        return $this->getParameter('shippingAddress2');
    }
    
    public function setShippingAddress2($value)
    {
        return $this->setParameter('shippingAddress2', $value);
    }
    
    public function getShippingCity(){
        return $this->getParameter('shippingCity');
    }
    
    public function setShippingCity($value)
    {
        return $this->setParameter('shippingCity', $value);
    }
    
    public function getShippingPostcode(){
        return $this->getParameter('shippingPostcode');
    }
    
    public function setShippingPostcode($value)
    {
        return $this->setParameter('shippingPostcode', $value);
    }
    
    public function getShippingState(){
        return $this->getParameter('shippingState');
    }
    
    public function setShippingState($value)
    {
        return $this->setParameter('shippingState', $value);
    }
    
    public function getShippingPhone(){
        return $this->getParameter('shippingPhone');
    }
    
    public function setShippingPhone($value)
    {
        return $this->setParameter('shippingPhone', $value);
    }
    
    public function getShippingCountry(){
        return $this->getParameter('shippingCountry');
    }
    
    public function setShippingCountry($value)
    {
        return $this->setParameter('shippingCountry', $value);
    }
     
    public function getCompany(){
        return $this->getParameter('company');
    }
    
    public function setCompany($value)
    {
        return $this->setParameter('company', $value);
    }
    
     public function getEmail(){
        return $this->getParameter('email');
    }
    
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }
    
    public function getSimulatorMode()
    {
        return $this->getParameter('simulatorMode');
    }

    public function setSimulatorMode($value)
    {
        return $this->setParameter('simulatorMode', $value);
    }

    public function getService()
    {
        return $this->action;
    }

    protected function getBaseData()
    {
        $data = array();
        $data['VPSProtocol'] = '2.23';
        $data['TxType'] = $this->action;
        $data['Vendor'] = $this->getVendor();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        return $this->createResponse($httpResponse->getBody());
    }

    public function getEndpoint()
    {
        $service = strtolower($this->getService());

        if ($this->getSimulatorMode()) {
            // hooray for consistency
            if ($service == 'vspdirect-register') {
                return $this->simulatorEndpoint.'/VSPDirectGateway.asp';
            } elseif ($service == 'vspserver-register') {
                return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=VendorRegisterTx';
            } elseif ($service == 'direct3dcallback') {
                return $this->simulatorEndpoint.'/VSPDirectCallback.asp';
            }elseif ($service == 'token' ){

            }elseif ($service == 'removetoken'){

            }

            return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=Vendor'.ucfirst($service).'Tx';
        }
        
        if ($this->getTestMode()) {
            return $this->testEndpoint."/$service.vsp";
        }

        return $this->liveEndpoint."/$service.vsp";
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
