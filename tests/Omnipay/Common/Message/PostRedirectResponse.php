<?php

namespace Omnipay\Common\Message;

class PostRedirectResponse extends AbstractResponse implements RedirectResponseInterface
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
        return 'POST';
    }

    public function getRedirectData()
    {
        return array(
            'foo' => 'bar',
        );
    }
}
