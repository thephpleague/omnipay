<?php

namespace Omnipay\Tpay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Tpay Response
 */
class Response extends AbstractResponse implements ResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, json_decode($data, true));
    }

    public function isPending()
    {
        return isset($this->data['3ds_url']);
    }

    public function isSuccessful()
    {
        return isset($this->data['result']) && (int)$this->data['result'] === 1;
    }

    public function isRedirect()
    {
        return isset($this->data['3ds_url']);
    }

    public function getRedirectUrl()
    {
        return $this->isRedirect() ? $this->data['3ds_url'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['sale_auth']) ? $this->data['sale_auth'] : null;
    }

    public function isPaid()
    {
        return isset($this->data['status']) && $this->data['status'] === 'correct';
    }

    public function getRejectionMessage()
    {
        return isset($this->data['reason']) ? $this->data['reason'] : null;
    }

    public function getErrorCode()
    {
        return isset($this->data['err_code']) ? $this->data['err_code'] : null;
    }

    public function getErrorMessage()
    {
        return isset($this->data['err_desc']) ? $this->data['err_desc'] : null;
    }

    public function getToken()
    {
        return isset($this->data['cli_auth']) ? $this->data['cli_auth'] : null;
    }
}
