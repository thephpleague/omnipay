<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\FormRedirectResponse;
use Omnipay\Common\Message\RedirectResponse;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Sage Pay Response
 */
class Response extends AbstractResponse
{
    protected $request;

    /**
     * Create a new Sage Pay response
     *
     * @param string The raw response body
     * @param Request The original Omnipay Request object
     * @return Response
     */
    public static function create($response, Request $request)
    {
        $data = static::decode($response);

        // do we need to redirect for 3D authentication?
        if (isset($data['Status']) && '3DAUTH' === $data['Status']) {
            $redirectData = array(
                'PaReq' => $data['PAReq'],
                'TermUrl' => $request->getReturnUrl(),
                'MD' => $data['MD'],
            );

            return new FormRedirectResponse($data['ACSURL'], $redirectData);
        }

        // handle Sage Pay Server redirect
        if (isset($data['Status']) && 'OK' === $data['Status'] && !empty($data['NextURL'])) {
            return new RedirectResponse($data['NextURL']);
        }

        return new static($data, $request);
    }

    /**
     * Decode raw ini-style response body
     *
     * @param string The raw response body
     * @return array
     */
    protected static function decode($response)
    {
        $lines = explode("\n", $response);
        $data = array();

        foreach ($lines as $line) {
            $line = explode('=', $line, 2);
            if (!empty($line[0])) {
                $data[trim($line[0])] = isset($line[1]) ? trim($line[1]) : '';
            }
        }

        return $data;
    }

    /**
     * Create a new Response object
     *
     * @param array The decoded response data
     * @param Request The original Omnipay Request object
     */
    public function __construct($data, Request $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    public function isSuccessful()
    {
        return isset($this->data['Status']) && 'OK' === $this->data['Status'];
    }

    /**
     * Gateway Reference
     *
     * Unfortunately Sage Pay requires the original VendorTxCode as well as 3 separate
     * fields from the response object to capture or refund transaction in the future.
     *
     * Active Merchant solves this dilemma by returning the gateway reference in the following
     * custom format: VendorTxCode;VPSTxId;TxAuthNo;SecurityKey
     *
     * We have opted to return this reference as JSON, as the keys are much more explicit.
     */
    public function getGatewayReference()
    {
        if ($this->isSuccessful() && isset($this->data['TxAuthNo'])) {
            return json_encode(
                array(
                    'SecurityKey' => $this->data['SecurityKey'],
                    'TxAuthNo' => $this->data['TxAuthNo'],
                    'VPSTxId' => $this->data['VPSTxId'],
                    'VendorTxCode' => $this->request->getTransactionId(),
                )
            );
        }
    }

    public function getMessage()
    {
        return isset($this->data['StatusDetail']) ? $this->data['StatusDetail'] : null;
    }

    /**
     * Request
     *
     * The original Omnipay Request object
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Confirm (Sage Pay Server only)
     *
     * Sage Pay Server does things backwards compared to every other gateway (including Sage Pay
     * Direct). The return URL is called by their server, and they expect you to confirm receipt
     * and then pass a URL for them to forward the customer to.
     *
     * Because of this, an extra step is required. In your return controller, after calling
     * $gateway->completePurchase(), you should update your database with details of the
     * successful payment. You must then call $response->confirm() to notify Sage Pay you
     * received the payment details, and provide a URL to forward the customer to.
     *
     * Keep in mind your original confirmPurchase() script is being called by Sage Pay, not
     * the customer.
     *
     * @param string URL to foward the customer to
     */
    public function confirm($nextUrl)
    {
        exit("Status=OK\r\nRedirectUrl=".$nextUrl);
    }
}
