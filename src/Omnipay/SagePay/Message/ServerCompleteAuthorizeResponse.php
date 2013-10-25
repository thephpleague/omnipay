<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * Sage Pay Server Complete Authorize Response
 */
class ServerCompleteAuthorizeResponse extends Response
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function getTransactionReference()
    {
        if (isset($this->data['TxAuthNo'])) {
            $reference = json_decode($this->getRequest()->getTransactionReference(), true);
            $reference['VendorTxCode'] = $this->getRequest()->getTransactionId();
            $reference['TxAuthNo'] = $this->data['TxAuthNo'];

            return json_encode($reference);
        }
    }

    /**
     * Confirm (Sage Pay Server only)
     *
     * Notify Sage Pay you received the payment details and wish to confirm the payment, and
     * provide a URL to forward the customer to.
     *
     * @param string URL to forward the customer to. Note this is different to your standard
     *               return controller action URL.
     * @param string Optional human readable reasons for accepting the transaction.
     */
    public function confirm($nextUrl, $detail = null)
    {
        $this->sendResponse('OK', $nextUrl, $detail);
    }

    /**
     * Error (Sage Pay Server only)
     *
     * Notify Sage Pay you received the payment details but there was an error and the payment
     * cannot be completed. Error should be called rarely, and only when something unforseen
     * has happened on your server or database.
     *
     * @param string URL to foward the customer to. Note this is different to your standard
     *               return controller action URL.
     * @param string Optional human readable reasons for not accepting the transaction.
     */
    public function error($nextUrl, $detail = null)
    {
        $this->sendResponse('ERROR', $nextUrl, $detail);
    }

    /**
     * Invalid (Sage Pay Server only)
     *
     * Notify Sage Pay you received the payment details but they were invalid and the payment
     * cannot be completed. Invalid should be called if you are not happy with the contents
     * of the POST, such as the MD5 hash signatures did not match or you do not wish to proceed
     * with the order.
     *
     * @param string URL to foward the customer to. Note this is different to your standard
     *               return controller action URL.
     * @param string Optional human readable reasons for not accepting the transaction.
     */
    public function invalid($nextUrl, $detail = null)
    {
        $this->sendResponse('INVALID', $nextUrl, $detail);
    }

    /**
     * Respond to SagePay confirming or rejecting the payment.
     *
     * Sage Pay Server does things backwards compared to every other gateway (including Sage Pay
     * Direct). The return URL is called by their server, and they expect you to confirm receipt
     * and then pass a URL for them to forward the customer to.
     *
     * Because of this, an extra step is required. In your return controller, after calling
     * $gateway->completePurchase(), you should attempt to process the payment. You must then call
     * either $response->confirm(), $response->error() or $response->invalid() to notify Sage Pay
     * whether to complete the payment or not, and provide a URL to forward the customer to.
     *
     * Keep in mind your original confirmPurchase() script is being called by Sage Pay, not
     * the customer.
     *
     * @param string The status to send to Sage Pay, either OK, INVALID or ERROR.
     * @param string URL to forward the customer to. Note this is different to your standard
     *               return controller action URL.
     * @param string Optional human readable reasons for accepting the transaction.
     */
    public function sendResponse($status, $nextUrl, $detail = null)
    {
        $message = "Status=$status\r\nRedirectUrl=$nextUrl";

        if (null !== $detail) {
            $message .= "\r\nStatusDetail=".$detail;
        }

        $this->exitWith($message);
    }

    /**
     * Exit to ensure no other HTML, headers, comments, or text are included.
     *
     * @access private
     * @codeCoverageIgnore
     */
    public function exitWith($message)
    {
        echo $message;
        exit;
    }
}
