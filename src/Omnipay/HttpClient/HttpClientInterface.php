<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\HttpClient;

/**
 * Http client interface
 *
 * Allows Omnipay to depend on any available HTTP client library.
 */
interface HttpClientInterface
{
    /**
     * Perform an HTTP GET request.
     *
     * @param  string $url     The request URL.
     * @param  array  $headers Any extra HTTP headers to be sent with the request.
     * @return string
     */
    public function get($url, $headers = array());

    /**
     * Perform an HTTP POST request.
     *
     * @param string $url  The request URL
     * @param mixed  $data The POST data. If an array is passed, must be automatically
     *                         URL encoded. Otherwise must be cast to string.
     * @param  array  $headers Any extra HTTP headers to be sent with the request.
     * @return string
     */
    public function post($url, $data, $headers = array());
}
