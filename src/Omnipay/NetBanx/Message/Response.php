<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\NetBanx\Message;

use Omnipay\NetBanx\Gateway;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * NetBanx Response
 */
class Response extends AbstractResponse
{
    /**
     * Constructor
     *
     * @param  RequestInterface         $request
     * @param  string                   $data
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        try {
            $this->data = new \SimpleXMLElement($data);
        } catch (\Exception $e) {
            throw new InvalidResponseException();
        }
    }

    /**
     * Whether or not response is successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        $decisionOk = Gateway::DECISION_ACCEPTED === (string) $this->data->decision;
        $codeOk = Gateway::CODE_OK === (string) $this->data->code;

        return $decisionOk && $codeOk;
    }

    /**
     * Get transaction reference
     *
     * @return string
     */
    public function getTransactionReference()
    {
        return (string) $this->data->confirmationNumber;
    }

    /**
     * Get card reference
     *
     * @return string
     */
    public function getCardReference()
    {
        return (string) $this->data->confirmationNumber;
    }

    /**
     * Get message from responce
     *
     * @return string
     */
    public function getMessage()
    {
        return (string) $this->data->description;
    }
}
