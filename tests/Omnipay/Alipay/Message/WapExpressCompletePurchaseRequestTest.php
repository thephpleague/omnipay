<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-3 上午12:52
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Tests\TestCase;

class WapExpressCompletePurchaseRequestTest extends TestCase
{

    public function setUp()
    {
        $this->request = new WapExpressCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                 'request_params' => array(
                     'notify_data' => '<xml></xml>',
                     'trade_status' => 'TRADE_SUCCESS',
                 ),
                 'private_key'    => '-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDELAccoy5epvo9TEKr4sXLVNbM0ZXAu24G9z/k2D3SHtFuONCh
H1O5nF73332hSA1k1x/nexGNAMlot/H6IlucdRaL8zHcSA5AKVw0iCnD9BoVnXCG
tElayPXQeLgtEP5FAJ9Ba1w28UWTgkgTj8dAFwYxADiAMm9i4LfWMtay0wIDAQAB
AoGAdxtASivtqHx7bSJTTKeIblcZgAw0f2uDwHj4a0q75krd361RRrKNlCGUK62f
SoBD2Zkf/tzjIBh9MT6WBcg8lCZ1UaNxwmXoyZ76G3IrjeJd02foRd648v663Top
fTjoKjv2KrzSmUu2Km4uE+NZqFSL+Jd1z0DwHbhfd0I8BGECQQDvCg7muPcEUi7o
3GpK0QVsk8EzP1Q8fdlebpr+FcCvfL5uTMIY6z27fO4p0dONJL8s9gV7r464XekP
KnfImBnRAkEA0hdScmQaZuZLUsJwhWWPRmYraJ3FsplvPJ5opt+zeemgiW2sxOfx
cVY2eFSt0qstmqau/FbSFRjCyrs8hlAHYwJAezorLmPh65dWWXLvVLxmWG/fJEVW
K30RJq5MNnoOSCk9nmzxjpkOzO19+YgSz+tGpq35a6a4I3E+KTRSZdWLUQJAaMPa
iFKk29VRkHaHt+26Mcf3M5cho/thfiAcXcLF9DBtrrpzYkmrm/H6/ax0dc6I0kr2
jb0ZzA1p7cDK4Mt9swJACh0wFnEQvfFBVUZo/zWW5nEBnVQ4l1QhfG6DoWJJA866
jdamyj2vQOFHLE2qpD+wprkUa86FJsdaEcuKjUl1lw==
-----END RSA PRIVATE KEY-----',
                 'partner'        => '451235632',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('<xml></xml>', $data['request_params']['notify_data']);
        $this->assertSame('TRADE_SUCCESS', $data['request_params']['trade_status']);
        $this->assertSame('-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDELAccoy5epvo9TEKr4sXLVNbM0ZXAu24G9z/k2D3SHtFuONCh
H1O5nF73332hSA1k1x/nexGNAMlot/H6IlucdRaL8zHcSA5AKVw0iCnD9BoVnXCG
tElayPXQeLgtEP5FAJ9Ba1w28UWTgkgTj8dAFwYxADiAMm9i4LfWMtay0wIDAQAB
AoGAdxtASivtqHx7bSJTTKeIblcZgAw0f2uDwHj4a0q75krd361RRrKNlCGUK62f
SoBD2Zkf/tzjIBh9MT6WBcg8lCZ1UaNxwmXoyZ76G3IrjeJd02foRd648v663Top
fTjoKjv2KrzSmUu2Km4uE+NZqFSL+Jd1z0DwHbhfd0I8BGECQQDvCg7muPcEUi7o
3GpK0QVsk8EzP1Q8fdlebpr+FcCvfL5uTMIY6z27fO4p0dONJL8s9gV7r464XekP
KnfImBnRAkEA0hdScmQaZuZLUsJwhWWPRmYraJ3FsplvPJ5opt+zeemgiW2sxOfx
cVY2eFSt0qstmqau/FbSFRjCyrs8hlAHYwJAezorLmPh65dWWXLvVLxmWG/fJEVW
K30RJq5MNnoOSCk9nmzxjpkOzO19+YgSz+tGpq35a6a4I3E+KTRSZdWLUQJAaMPa
iFKk29VRkHaHt+26Mcf3M5cho/thfiAcXcLF9DBtrrpzYkmrm/H6/ax0dc6I0kr2
jb0ZzA1p7cDK4Mt9swJACh0wFnEQvfFBVUZo/zWW5nEBnVQ4l1QhfG6DoWJJA866
jdamyj2vQOFHLE2qpD+wprkUa86FJsdaEcuKjUl1lw==
-----END RSA PRIVATE KEY-----', $data['private_key']);
        $this->assertSame('451235632', $data['partner']);
    }
}
 