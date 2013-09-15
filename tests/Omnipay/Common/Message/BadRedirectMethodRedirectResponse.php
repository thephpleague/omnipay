<?php

namespace Omnipay\Common\Message;

class BadRedirectMethodRedirectResponse extends GetRedirectResponse
{
    public function getRedirectMethod()
    {
        // Must be GET|POST
        return 'PUT';
    }
}
