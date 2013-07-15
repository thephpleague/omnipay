<?php

namespace Omnipay\Sips\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Sips Authorize Response
 */
class AuthorizeResponse extends AbstractResponse
{
    private $code;
    private $debug;
    private $message;

    public function __construct(AuthorizeRequest $request, $data)
    {
        parent::__construct($request, $data);

        $this->code = -1;

        $results = explode("!", "$data");
        if (count($results) > 3) {
            $this->code = $results[1];
            $this->debug = $results[2];
            $this->message = $results[3];
        }
    }

    public function isSuccessful()
    {
        return ($this->code == 0);
    }

    public function getTransactionReference()
    {
        /** @var AuthorizeRequest $request */
        $request = $this->request;

        return $request->getTransactionReference();
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getDebug()
    {
        return $this->debug;
    }
}
