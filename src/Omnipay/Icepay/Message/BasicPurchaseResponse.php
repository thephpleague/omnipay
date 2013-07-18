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
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use stdClass;

class BasicPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws InvalidResponseException when the checksum is invalid
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (!$this->verifySignature($data->CheckoutResult)) {
            throw new InvalidResponseException('Checksum invalid.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        return isset($this->data->CheckoutResult->ProviderTransactionID) ? $this->data->CheckoutResult->ProviderTransactionID : null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return isset($this->data->CheckoutResult->PaymentScreenURL);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        return $this->isRedirect() ? $this->data->CheckoutResult->PaymentScreenURL : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * @param stdClass $data
     *
     * @return boolean
     */
    protected function verifySignature(stdClass $data)
    {
        $raw = implode(
            '|',
            array(
                $this->request->getSecretCode(),
                $data->MerchantID,
                $data->Timestamp,
                $data->Amount,
                $data->Country,
                $data->Currency,
                $data->Description,
                $data->EndUserIP,
                $data->Issuer,
                $data->Language,
                $data->OrderID,
                $data->PaymentID,
                $data->PaymentMethod,
                $data->PaymentScreenURL,
                $data->ProviderTransactionID,
                $data->Reference,
                $data->TestMode ? 'true' : 'false',
                $data->URLCompleted,
                $data->URLError,
            )
        );

         return sha1($raw) === $data->Checksum;
    }
}
