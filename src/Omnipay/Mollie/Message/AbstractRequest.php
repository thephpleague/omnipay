<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Mollie\Message;

/**
 * Mollie iDeal Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://secure.mollie.nl/xml/ideal';

    /**
     * More permissive testMode check.
     * @return bool
     */
    public function getTestMode() {
        return (in_array(strtolower(parent::getTestMode()), array('yes', '1', 'true')));
    }

    /**
     * Does the actual response generation / sending.
     * @return Response
     */
    public function send()
    {
        $url = $this->getEndpoint().'?'.http_build_query($this->getData());
        $request = $this->httpClient->get($url);
        $httpResponse = $request->send();
        return $this->createResponse($httpResponse);
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param $data
     *
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    /**
     * Gets the partner_id parameter
     * @return mixed
     */
    public function getPartnerId()
    {
        return $this->getParameter('partner_id');
    }

    /**
     * Sets the partner_id parameter
     *
     * @param $value
     *
     * @return $this
     */
    public function setPartnerId($value)
    {
        return $this->setParameter('partner_id', $value);
    }

    /**
     * Get the bank_id parameter
     *
     * @return mixed
     */
    public function getBankId()
    {
        return $this->getParameter('bank_id');
    }

    /**
     * Sets the bank_id parameter
     *
     * @param $value
     *
     * @return $this
     */
    public function setBankId($value)
    {
        return $this->setParameter('bank_id', $value);
    }

    /**
     * Gets the profile_key parameter
     *
     * @return mixed
     */
    public function getProfileKey()
    {
        return $this->getParameter('profile_key');
    }

    /**
     * Sets the profile_key parameter
     *
     * @param $value
     *
     * @return $this
     */
    public function setProfileKey($value)
    {
        return $this->setParameter('profile_key', $value);
    }

    /**
     * Gets the reportUrl parameter
     *
     * @return mixed
     */
    public function getReportUrl()
    {
        return $this->getParameter('reportUrl');
    }

    /**
     * Sets the reportUrl parameter.
     *
     * @param $value
     *
     * @return $this
     */
    public function setReportUrl($value)
    {
        return $this->setParameter('reportUrl', $value);
    }
}
