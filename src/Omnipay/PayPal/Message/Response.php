<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {
        return isset($this->data['ACK']) && in_array($this->data['ACK'], array('Success', 'SuccessWithWarning'));
    }

    public function getTransactionReference()
    {
        foreach (array('REFUNDTRANSACTIONID', 'TRANSACTIONID', 'PAYMENTINFO_0_TRANSACTIONID') as $key) {
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
        }
    }

    public function getCardReference()
    {
        return isset($this->data['TRANSACTIONID']) ? $this->data['TRANSACTIONID'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['L_LONGMESSAGE0']) ? $this->data['L_LONGMESSAGE0'] : null;
    }

    public function getFullMessage()
    {
        $msg = '';
        $lmsg = '';

        // L_LONGMESSAGE0 often says "This transaction cannot be processed",
        // which does not give much information about why the transaction
        // failed.  To give a more complete picture, combine both messages
        // to return a message like:
        //   "Processor Decline|This transaction cannot be processed"
        if (isset($this->data['L_SHORTMESSAGE0']) && $this->data['L_SHORTMESSAGE0']) {
            $msg = $this->data['L_SHORTMESSAGE0'];
        }

        if (isset($this->data['L_LONGMESSAGE0']) && $this->data['L_LONGMESSAGE0']) {
            $lmsg = $this->data['L_LONGMESSAGE0'];
        }

        if ($msg || $lmsg) {
            $msg .= "|$lmsg";
            return $msg;
        }

        return null;
    }
}
