<?php

namespace Omnipay\Common\Message;

class GetRedirectResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return 'http://redirect.domain.com/';
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return array(
            'foo' => 'bar',
        );
    }
}
