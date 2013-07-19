<?php

namespace Omnipay\Sips\Message;

/**
 * Class ResponseCall
 *
 * Defines a call to the Sips Response binary
 *
 * @package Omnipay\Sips\Message
 */
class ResponseCall extends SipsBinaryCall
{
    /**
     * Raw data coming back from Sips
     *
     * @var
     */
    protected $sipsData;

    /**
     * Sets the data to add to the request
     *
     * @param mixed $sipsData
     */
    public function setSipsData($sipsData)
    {
        $this->sipsData = $sipsData;
    }

    /**
     * Gets the data to add to the request
     *
     * @return mixed
     */
    public function getSipsData()
    {
        return $this->sipsData;
    }

    public function send()
    {
        $params = $this->buildRequest();
        $path_bin = $this->getSipsResponseExecPath();

        $result = exec("$path_bin $params");

        return $this->response = new ResponseResult($this, $result);
    }

    /**
     * Gets a string representing all the parameters to pass to Sips
     *
     * @return string
     */
    protected function buildRequest()
    {
        $params = array(
            'pathfile' => $this->getSipsPathFilePath(),
            'message' => $this->getSipsData()
        );

        $response = array();
        foreach ($params as $key => $value) {
            $response[] = $key . '=' . $value;
        }

        return implode(' ', $response);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return array('DATA' => $this->getSipsData());
    }
}
