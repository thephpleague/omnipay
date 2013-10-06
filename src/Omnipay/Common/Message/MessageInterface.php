<?php

namespace Omnipay\Common\Message;

/**
 * Message Interface
 */
interface MessageInterface
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData();
}
