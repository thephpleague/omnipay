<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Message;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Abstract Response
 */
abstract class AbstractResponse implements ResponseInterface
{
    protected $request;
    protected $data;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessage()
    {
        return null;
    }

    public function getCode()
    {
        return null;
    }

    public function getTransactionReference()
    {
        return null;
    }

    /**
     * Automatically perform any required redirect
     *
     * This method is meant to be a helper for simple scenarios. If you want to customize the
     * redirection page, just call the getRedirectUrl() and getRedirectData() methods directly.
     */
    public function redirect()
    {
        $this->getRedirectResponse()->send();
        exit;
    }

    public function getRedirectResponse()
    {
        if (!$this instanceof RedirectResponseInterface || !$this->isRedirect()) {
            throw new RuntimeException('This response does not support redirection.');
        }

        if (!$this->isValidRedirectMethod()) {
            $message = sprintf('Invalid redirect method "%s".', $this->getRedirectMethod());
            throw new RuntimeException($message);
        }

        if ('GET' === $this->getRedirectMethod()) {
            return HttpRedirectResponse::create($this->getRedirectUrl());
        }

        $output = $this->getRedirectHtml($this->getRedirectUrl(), $this->getRedirectData());

        return HttpResponse::create($output);
    }

    protected function isValidRedirectMethod()
    {
        $validMethods = array('GET', 'POST');

        return in_array($this->getRedirectMethod(), $validMethods);
    }

    /**
     * @param string $url           URL to redirect to
     * @param array  $redirectData  Key-value pairs to create as hidden form fields
     * @return string
     */
    protected function getRedirectHtml($url, array $redirectData = array())
    {
        $hiddenFields = '';
        foreach ($redirectData as $key => $value) {
            $hiddenFields .= sprintf(
                '<input type="hidden" name="%1$s" value="%2$s" />',
                htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
            )."\n";
        }

        $html = '<!DOCTYPE html>
<html>
    <head>
        <title>Redirecting...</title>
    </head>
    <body onload="document.forms[0].submit();">
        <form action="%1$s" method="post">
            <p>Redirecting to payment page...</p>
            <p>
                %2$s
                <input type="submit" value="Continue" />
            </p>
        </form>
    </body>
</html>';

        return sprintf($html, htmlspecialchars($url, ENT_QUOTES, 'UTF-8'), $hiddenFields);
    }
}
