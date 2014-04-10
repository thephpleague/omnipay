<?php

namespace Omnipay\Common\Message;

/**
 * Fetch Issuers Response interface
 */
interface FetchIssuersResponseInterface extends ResponseInterface
{
    /**
     * Get the returned list of issuers.
     *
     * These represent banks which the user must choose between.
     *
     * @return array An array of \Omnipay\Common\Issuer objects
     */
    public function getIssuers();
}
