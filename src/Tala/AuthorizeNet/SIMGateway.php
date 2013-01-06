<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\AuthorizeNet;

use Tala\Exception;
use Tala\Exception\InvalidResponseException;
use Tala\FormRedirectResponse;
use Tala\Request;

/**
 * Authorize.Net SIM Class
 */
class SIMGateway extends AIMGateway
{
    public function authorize(Request $request, $source)
    {
        $data = $this->buildAuthorizeOrPurchase($request, $source, 'AUTH_ONLY');

        return new FormRedirectResponse($this->getCurentEndpoint(), $data);
    }

    public function completeAuthorize(Request $request)
    {
        if ( ! $this->validateReturnHash()) {
            throw new InvalidResponseException();
        }

        $responseCode = isset($_POST['x_response_code']) ? $_POST['x_response_code'] : '';
        $message = isset($_POST['x_response_reason_text']) ? $_POST['x_response_reason_text'] : '';
        $reference = isset($_POST['x_trans_id']) ? $_POST['x_trans_id'] : '';

        if ($response_code == '1') {
            return new Response($reference, $message);
        }

        throw new Exception($message);
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildAuthorizeOrPurchase($request, $source, 'AUTH_CAPTURE');

        return new FormRedirectResponse($this->getCurentEndpoint(), $data);
    }

    public function completePurchase(Request $request)
    {
        return $this->completeAuthorize($request);
    }

    protected function buildAuthorizeOrPurchase($request, $source, $method)
    {
        $request->validateRequired(array('amount', 'returnUrl'));

        $data = array();
        $data['x_login'] = $this->apiLoginId;
        $data['x_type'] = $method;
        $data['x_fp_sequence'] = mt_rand();
        $data['x_fp_timestamp'] = time();
        $data['x_delim_data'] = 'FALSE';
        $data['x_show_form'] = 'PAYMENT_FORM';
        $data['x_relay_response'] = 'TRUE';
        $data['x_relay_url'] = $request->returnUrl;
        $data['x_cancel_url'] = $request->cancelUrl;

        if ($this->testMode) {
            $data['x_test_request'] = 'TRUE';
        }

        $this->addBillingDetails($request, $source, $data);

        $data['x_fp_hash'] = $this->generateHash($data);

        return $data;
    }

    protected function generateHash($data)
    {
        $fingerprint = implode('^', array(
            $this->apiLoginId,
            $data['x_fp_sequence'],
            $data['x_fp_timestamp'],
            $data['x_amount'])).'^';

        return hash_hmac('md5', $fingerprint, $this->transactionKey);
    }

    protected function validateReturnHash($request)
    {
        $expected = strtoupper(md5($this->apiLoginId.$request->transactionId.$request->amountDollars));
        $actual = isset($_POST['x_MD5_Hash']) ? strtoupper($_POST['x_MD5_Hash']) : '';

        return $expected == $actual;
    }
}
