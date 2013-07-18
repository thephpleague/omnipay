<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use SoapFault;
use stdClass;

class FetchPaymentMethodsRequest extends AbstractBasicRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('merchantId', 'secretCode');

        $data = new stdClass();
        $data->MerchantID = $this->getMerchantId();
        $data->Timestamp = (null !== $this->getTimestamp()) ? $this->getTimestamp() : gmdate("Y-m-d\TH:i:s\Z");
        $data->Checksum = $this->generateSignature($data);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        try {
            $rawResponse = $this->getSoapClient()->GetMyPaymentMethods(array(
                'request' => $this->getData(),
            ));
        } catch (SoapFault $e) {
            throw new InvalidResponseException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->response = new FetchPaymentMethodsResponse($this, $rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateSignature(stdClass $data)
    {
        $raw = implode(
            '|',
            array(
                $data->MerchantID,
                $this->getSecretCode(),
                $data->Timestamp,
            )
        );

        return sha1($raw);
    }
}
