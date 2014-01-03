<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午5:03
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;

class MobileExpressPurchaseResponse extends AbstractResponse implements ResponseInterface
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return true;
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}
