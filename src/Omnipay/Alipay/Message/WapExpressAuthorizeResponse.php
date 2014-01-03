<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午3:18
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;

class WapExpressAuthorizeResponse extends AbstractResponse implements ResponseInterface
{

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $data;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if (isset($this->data['request_token']) && $this->data['request_token']) {
            return true;
        } else {
            return false;
        }
    }

    public function getToken()
    {
        if ($this->isSuccessful()) {
            return $this->data['request_token'];
        } else {
            return '';
        }
    }
}
