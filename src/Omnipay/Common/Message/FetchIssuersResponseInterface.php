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
     * @return \Omnipay\Common\Issuer[]
     */
    public function getIssuers();
}
