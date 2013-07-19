<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Sips\Message;

use Omnipay\Common\CreditCard;

class ResponseCall extends SipsBinaryCall
{
    public function send()
    {
        $params = $this->getSipsParamString();
        $path_bin = $this->getSipsResponseExecPath();

        $result = exec("$path_bin $params");

        return $this->response = new ResponseResult($this, $result);
    }

    protected function getSipsParamString()
    {
        $params = 'message=' . $this->getSipsData();
        $params .= " pathfile=".$this->getSipsPathFilePath();

        return trim($params);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return array('DATA' => $this->getSipsData());
    }
}
