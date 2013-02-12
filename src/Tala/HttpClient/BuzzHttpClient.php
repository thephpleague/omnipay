<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\HttpClient;

use Buzz\Browser;
use Tala\Exception\InvalidResponseException;

/**
 * Buzz Http Client implementation
 */
class BuzzHttpClient implements HttpClientInterface
{
    private $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function getBrowser()
    {
        return $this->browser;
    }

    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }

    public function get($url, $headers = array())
    {
        $response = $this->browser->get($url, $headers);

        if (!$response->isSuccessful()) {
            throw new InvalidResponseException;
        }

        return $response->getContent();
    }

    public function post($url, $data, $headers = array())
    {
        if (is_array($data)) {
            $data = http_build_query($data);
        }

        $response = $this->browser->post($url, $headers, $data);

        if (!$response->isSuccessful()) {
            throw new InvalidResponseException;
        }

        return $response->getContent();
    }
}
