<?php

namespace Omnipay\Myfatoorah\Message;

class Response implements \Omnipay\Common\Message\ResponseInterface {

    public function __construct($request, $response) {
        $this->request  = $request;
        $this->response = $response;
    }

    public function getRequest() {
        $this->request;
    }

    public function isSuccessful() {
        $json = json_decode($this->response->getBody()->getContents(), true);
        return $json; 
        return ($this->response->getStatusCode() >= 200 && $this->response->getStatusCode() <= 299 && isset($json['IsSuccess']) && $json['IsSuccess']);
    }

    public function isRedirect() {
        $this->response->getBody()->rewind();
        $json = json_decode($this->response->getBody()->getContents(), true);
        if (!empty($json['Data']['InvoiceURL'])) {
            return true;
        }
        return false;
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl() {
        $this->response->getBody()->rewind();
        $json = json_decode($this->response->getBody()->getContents(), true);
        if (!empty($json['Data']['InvoiceURL'])) {
            return $json['Data']['InvoiceURL'];
        } else {
            return false;
        }
    }

    public function isCancelled() {
        return false;
    }

    public function getMessage() {
        $this->response->getBody()->rewind();
        $res  = ($this->response->getBody()->getContents());
        $json = json_decode($res);

        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return $res;
        }
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');
            $err       = implode(', ', array_map(function ($k, $v) {
                        return "$k: $v";
                    }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $err = $json->Data->ErrorMessage;
        }

        if (empty($err)) {
            $err = (isset($json->Message)) ? $json->Message : (!empty($res) ? $res : 'Kindly, review your Myfatoorah API configuration due to a wrong entry.');
        }

        return $err;
    }

    public function getCode() {
        return $this->response->getStatusCode();
    }

    public function getTransactionReference() {
        $this->response->getBody()->rewind();
        $json = json_decode($this->response->getBody()->getContents(), true);
        return $json['Data']['InvoiceId'];
    }

    public function getData() {
        return $this->request->getData();
    }

    public function getPaymentStatus($orderId, $paymentId, $KeyType = 'PaymentId') {
        $res  = $this->getMessage();
        $json = json_decode($res);
        if ($orderId && $json->Data->CustomerReference != $orderId) {
            return 'Trying to call data of another order';
        } else if ($json->Data->InvoiceStatus == 'DuplicatePayment') {
            return 'Duplicate Payment'; //success with Duplicate
        }

        if ($KeyType == 'PaymentId') {
            foreach ($json->Data->InvoiceTransactions as $transaction) {
                if ($transaction->PaymentId == $paymentId && $transaction->Error && $json->Data->InvoiceStatus != 'Paid') {
                    return 'Failed with Error (' . $transaction->Error . ')'; //faild order
                }
            }
        }
        if ($json->Data->InvoiceStatus != 'Paid') {
            //------------------
            //case 1:
            $lastInvoiceTransactions = end($json->Data->InvoiceTransactions);
            if ($lastInvoiceTransactions && $lastInvoiceTransactions->Error) {
                return 'Failed with Error (' . $lastInvoiceTransactions->Error . ')'; //faild order
            }

            //------------------
            //case 2:
            //all myfatoorah gateway is set to Asia/Kuwait
            $ExpiryDate  = new \DateTime($json->Data->ExpiryDate, new \DateTimeZone('Asia/Kuwait'));
            $ExpiryDate->modify('+1 day'); ///????????????$ExpiryDate without any hour so for i added the 1 day just in case. this should be changed after adding the tome to the expire date
            $currentDate = new \DateTime('now', new \DateTimeZone('Asia/Kuwait'));

            if ($ExpiryDate < $currentDate) {
                return'Invoice is expired since: ' . $ExpiryDate->format('Y-m-d'); //cancelled order
            }

            //------------------
            //case 3:
            //payment is pending .. user has not paid yet and the invoice is not expired
            return 'Payment is pending';
        }

        return print_r($json->Data, 1);
    }

    public function getRefundInfo() {
        return $this->getMessage();
    }

}
