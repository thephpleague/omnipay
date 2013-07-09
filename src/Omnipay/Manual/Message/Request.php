<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Manual\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Manual Request
 */
class Request extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount');

        return $this->getParameters();
    }

    public function send(array $datas = array(), $doMerge = true)
    {
        if($datas)
        	$datas = $doMerge ?array_merge($this->getData(), $datas) :$datas;
        else
        	$datas = $this->getData();
        
        return $this->response = new Response($this, $datas);
    }
}