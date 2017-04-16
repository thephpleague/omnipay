<?php

namespace Omnipay\Agms\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Agms Response
 */
class Response extends AbstractResponse
{
    public function __construct($request, $data, $op='ProcessTransaction')
    {
        $this->request = $request;
        $this->data = $this->parseResponse($data, $op);
    }

    /**
     * Get the request status
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return isset($this->data['STATUS_CODE']) && $this->data['STATUS_CODE'] == 1;
    }

    /**
     * Get the transaction id
     *
     * @return string
     */
    public function getTransactionId()
    {
        return isset($this->data['TRANS_ID']) ? $this->data['TRANS_ID'] : null;
    }

    /**
     * Get the transaction reference
     *
     * @return string
     */
    public function getTransactionReference()
    {
        return isset($this->data['TRANS_ID']) ? $this->data['TRANS_ID'] : null;
    }

    /**
     * Get the status message
     *
     * @return string
     */
    public function getMessage()
    {
        return isset($this->data['STATUS_MSG']) ? $this->data['STATUS_MSG'] : null;
    }

    /**
     * Get the card reference or safe id
     *
     * @return string
     */
    public function getCardReference()
    {
        return isset($this->data['SAFE_ID']) ? $this->data['SAFE_ID'] : null;
    }

    /**
     * Parse the transaction response
     *
     * @return array
     */
    private function parseResponse($data, $op)
    {
        $arr = array();
        $xml = new \SimpleXMLElement($data);
        $xml = $xml->xpath('/soap:Envelope/soap:Body');
        $xml = $xml[0];
        $data = json_decode(json_encode($xml));
        $opResponse = $op . 'Response';
        $opResult = $op . 'Result';
        $arr = $this->object2array($data->$opResponse->$opResult);
        return $arr;
    }
    
    /**
     * Convert object to array
     *
     * @return array
     */
    private function object2array($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = $this->object2array($value);
            }
            return $result;
        }
        return $data;
    }
    
}
