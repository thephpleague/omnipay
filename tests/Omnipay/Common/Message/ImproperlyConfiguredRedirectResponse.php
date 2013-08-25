<?php

namespace Omnipay\Common\Message;

class ImproperlyConfiguredRedirectResponse extends GetRedirectResponse
{
    public function isRedirect()
    {
        // Should always be true
        return false;
    }
}
