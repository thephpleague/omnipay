<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\AuthorizeNet;

use Tala\Exception;
use Tala\Exception\InvalidResponseException;
use Tala\FormRedirectResponse;
use Tala\Request;

/**
 * Authorize.Net SIM Class
 */
class SIMGateway extends AIMGateway
{
    public function getName()
    {
        return 'Authorize.Net SIM';
    }

    public function authorize($options)
    {
        $data = $this->buildAuthorizeOrPurchase($options, 'AUTH_ONLY');

        return new FormRedirectResponse($this->getCurrentEndpoint(), $data);
    }

    public function completeAuthorize($options)
    {
        $request = new Request($options);
        if (!$this->validateReturnHash($request)) {
            throw new InvalidResponseException();
        }

        $responseCode = isset($_POST['x_response_code']) ? $_POST['x_response_code'] : '';
        $message = isset($_POST['x_response_reason_text']) ? $_POST['x_response_reason_text'] : '';
        $reference = isset($_POST['x_trans_id']) ? $_POST['x_trans_id'] : '';

        if ($responseCode == '1') {
            return new Response($reference, $message);
        }

        throw new Exception($message);
    }

    public function purchase($options)
    {
        $data = $this->buildAuthorizeOrPurchase($options, 'AUTH_CAPTURE');

        return new FormRedirectResponse($this->getCurrentEndpoint(), $data);
    }

    public function completePurchase($options)
    {
        return $this->completeAuthorize($request);
    }

    protected function buildAuthorizeOrPurchase($options, $method)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'returnUrl'));
        $source = $request->getCard();

        $data = array();
        $data['x_login'] = $this->apiLoginId;
        $data['x_type'] = $method;
        $data['x_fp_sequence'] = mt_rand();
        $data['x_fp_timestamp'] = time();
        $data['x_delim_data'] = 'FALSE';
        $data['x_show_form'] = 'PAYMENT_FORM';
        $data['x_relay_response'] = 'TRUE';
        $data['x_relay_url'] = $request->getReturnUrl();
        $data['x_cancel_url'] = $request->getCancelUrl();

        if ($this->testMode) {
            $data['x_test_request'] = 'TRUE';
        }

        $this->addBillingDetails($request, $source, $data);

        $data['x_fp_hash'] = $this->generateHash($data);

        return $data;
    }

    protected function generateHash($data)
    {
        $fingerprint = implode(
            '^',
            array(
                $this->apiLoginId,
                $data['x_fp_sequence'],
                $data['x_fp_timestamp'],
                $data['x_amount']
            )
        ).'^';

        return hash_hmac('md5', $fingerprint, $this->transactionKey);
    }

    protected function validateReturnHash($request)
    {
        $expected = strtoupper(md5($this->apiLoginId.$request->getTransactionId().$request->getAmountDollars()));
        $actual = isset($_POST['x_MD5_Hash']) ? strtoupper($_POST['x_MD5_Hash']) : '';

        return $expected == $actual;
    }
}
