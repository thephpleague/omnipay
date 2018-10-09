# Omnipay

**An easy to use, consistent payment processing library for PHP**

[![Build Status](https://travis-ci.org/thephpleague/omnipay-common.svg?branch=master)](https://travis-ci.org/thephpleague/omnipay-common)
[![Latest Stable Version](https://poser.pugx.org/omnipay/common/version)](https://packagist.org/packages/omnipay/common)
[![Total Downloads](https://poser.pugx.org/omnipay/common/d/total)](https://packagist.org/packages/omnipay/common)

Omnipay is a payment processing library for PHP. It has been designed based on
ideas from [Active Merchant](http://activemerchant.org/), plus experience implementing
dozens of gateways for [CI Merchant]. It has a clear and consistent API,
is fully unit tested, and even comes with an example application to get you started.

**Why use Omnipay instead of a gateway's official PHP package/example code?**

* Because you can learn one API and use it in multiple projects using different payment gateways
* Because if you need to change payment gateways you won't need to rewrite your code
* Because most official PHP payment gateway libraries are a mess
* Because most payment gateways have exceptionally poor documentation
* Because you are writing a shopping cart and need to support multiple gateways

## TL;DR

Just want to see some code?

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Stripe');
$gateway->setApiKey('abc123');

$formData = array('number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2030', 'cvv' => '123');
$response = $gateway->purchase(array('amount' => '10.00', 'currency' => 'USD', 'card' => $formData))->send();

if ($response->isRedirect()) {
    // redirect to offsite payment gateway
    $response->redirect();
} elseif ($response->isSuccessful()) {
    // payment was successful: update database
    print_r($response);
} else {
    // payment failed: display message to customer
    echo $response->getMessage();
}
```

As you can see, Omnipay has a consistent, well thought out API. We try to abstract as much
as possible the differences between the various payments gateways.

## Package Layout

Omnipay is a collection of packages which all depend on the
[omnipay/common](https://github.com/thephpleague/omnipay-common) package to provide
a consistent interface. There are no dependencies on official payment gateway PHP packages -
we prefer to work with the HTTP API directly. Under the hood, we use the popular and powerful
[PHP-HTTP](http://docs.php-http.org/en/latest/index.html) library to make HTTP requests. 
A [Guzzle](http://guzzlephp.org/) adapter is required by default, when using `league/omnipay`.

New gateways can be created by cloning the layout of an existing package. When choosing a
name for your package, please don't use the `omnipay` vendor prefix, as this implies that
it is officially supported. You should use your own username as the vendor prefix, and prepend
`omnipay-` to the package name to make it clear that your package works with Omnipay.
For example, if your GitHub username was `santa`, and you were implementing the `giftpay`
payment library, a good name for your composer package would be `santa/omnipay-giftpay`.

## Installation

Omnipay is installed via [Composer](https://getcomposer.org/). 
For most uses, you will need to require `league/omnipay` and an individual gateway:

```
composer require league/omnipay:^3 omnipay/paypal
```

If you want to use your own HTTP Client instead of Guzzle (which is the default for `league/omnipay`),
you can require `league/common` and any `php-http/client-implementation` (see [PHP Http](http://docs.php-http.org/en/latest/clients.html))

```
composer require league/common:^3 omnipay/paypal php-http/buzz-adapter
```

## Upgrade from v2 to v3

If your gateway is supported for v3, you can require that version. Make sure you require `league/omnipay` or a separate Http Adapter.

If there is no version for v3 yet, please raise an issue or upgrade the gateways yourself and create a PR.
See the [Upgrade guide for omnipay/common](https://github.com/thephpleague/omnipay-common/blob/master/UPGRADE.md)

> Note: The package name has been changed from `omnipay/omnipay` to `league/omnipay` for v3

## Payment Gateways

All payment gateways must implement [GatewayInterface](https://github.com/thephpleague/omnipay-common/blob/master/src/Common/GatewayInterface.php), and will usually
extend [AbstractGateway](https://github.com/thephpleague/omnipay-common/blob/master/src/Common/AbstractGateway.php) for basic functionality.

The following gateways are available:

Gateway | 2.x | 3.x | Composer Package | Maintainer
--- | --- | --- | --- | ---
[2c2p](https://github.com/dilab/omnipay-2c2p) | ✓ | ✓ | dilab/omnipay-2c2p | [Xu Ding](https://github.com/dilab)
[2Checkout](https://github.com/thephpleague/omnipay-2checkout) | ✓ | - | omnipay/2checkout | [Omnipay](https://github.com/thephpleague/omnipay)
[2Checkout Improved](https://github.com/collizo4sky/omnipay-2checkout) | ✓ | - | collizo4sky/omnipay-2checkout | [Agbonghama Collins](https://github.com/collizo4sky)
[Agms](https://github.com/agmscode/omnipay-agms) | ✓ | - | agmscode/omnipay-agms | [Maanas Royy](https://github.com/maanas)
[Alipay(Global)](https://github.com/lokielse/omnipay-global-alipay) | ✓ | ✓ | lokielse/omnipay-global-alipay | [Loki Else](https://github.com/lokielse)
[Alipay](https://github.com/lokielse/omnipay-alipay) | ✓ | ✓ | lokielse/omnipay-alipay | [Loki Else](https://github.com/lokielse)
[99Bill](https://github.com/laraveler/omnipay-99bill) | - | ✓ | x-class/omnipay-99bill | [Laraveler](https://github.com/laraveler)
[Allied Wallet](https://github.com/delatbabel/omnipay-alliedwallet) | ✓ | - | delatbabel/omnipay-alliedwallet | [Del](https://github.com/delatbabel)
[Authorize.Net](https://github.com/thephpleague/omnipay-authorizenet) | ✓ | ✓ | omnipay/authorizenet | [Jason Judge](https://github.com/judgej)
[Authorize.Net API](https://github.com/academe/omnipay-authorizenetapi) | - | ✓ | academe/omnipay-authorizenetapi | [Jason Judge](https://github.com/judgej)
[Authorize.Net Recurring Billing](https://github.com/cimpleo/omnipay-authorizenetrecurring) | - | ✓ | cimpleo/omnipay-authorizenetrecurring | [CimpleO](https://github.com/cimpleo)
[Barclays ePDQ](https://github.com/digitickets/omnipay-barclays-epdq) | ✓ | - | digitickets/omnipay-barclays-epdq | [DigiTickets](https://github.com/digitickets)
[Beanstream](https://github.com/lemonstand/omnipay-beanstream) | ✓ | - | lemonstand/omnipay-beanstream | [LemonStand](https://github.com/lemonstand)
[BitPay](https://github.com/hiqdev/omnipay-bitpay) | ✓ | - | hiqdev/omnipay-bitpay | [HiQDev](https://github.com/hiqdev)
[BKM Express](https://github.com/yasinkuyu/omnipay-bkm) | ✓ | - | yasinkuyu/omnipay-bkm | [Yasin Kuyu](https://github.com/yasinkuyu)
[BlueSnap](https://github.com/vimeo/omnipay-bluesnap) | ✓ | - | vimeo/omnipay-bluesnap | [Vimeo](https://github.com/vimeo)
[Braintree](https://github.com/thephpleague/omnipay-braintree) | ✓ | - | omnipay/braintree | [Omnipay](https://github.com/thephpleague/omnipay)
[Buckaroo](https://github.com/thephpleague/omnipay-buckaroo) | ✓ | - | omnipay/buckaroo | [Omnipay](https://github.com/thephpleague/omnipay)
[CardGate](https://github.com/cardgate/omnipay-cardgate) | ✓ | - | cardgate/omnipay-cardgate | [CardGate](https://github.com/cardgate)
[CardSave](https://github.com/thephpleague/omnipay-cardsave) | ✓ | - | omnipay/cardsave | [Omnipay](https://github.com/thephpleague/omnipay)
[CashBaBa](https://github.com/tapos007/omnipay-cashbaba) | ✓ | ✓ | omnipay/cashbaba | [Recursion Technologies Ltd](https://github.com/tapos007)
[Checkout.com](https://github.com/fotografde/omnipay-checkoutcom) | ✓ | - | fotografde/checkoutcom | [fotograf.de](https://github.com/fotografde)
[CloudBanking](https://github.com/spsingh/omnipay-cloudbanking) | ✓ | - | cloudbanking/omnipay-cloudbanking | [Cloudbanking](http://cloudbanking.com.au/)
[Coinbase](https://github.com/thephpleague/omnipay-coinbase) | ✓ | - | omnipay/coinbase | [Omnipay](https://github.com/thephpleague/omnipay)
[CoinGate](https://github.com/coingate/omnipay-coingate) | ✓ | - | coingate/omnipay-coingate | [CoinGate](https://github.com/coingate)
[CoinPayments](https://github.com/InkedCurtis/omnipay-coinpayments) | ✓ | ✓ | InkedCurtis/omnipay-coinpayments | [InkedCurtis](https://github.com/InkedCurtis)
[Creditcall](https://github.com/meebio/omnipay-creditcall) | ✓ | - | meebio/omnipay-creditcall | [John Jablonski](https://github.com/jan-j)
[Cybersource](https://github.com/dioscouri/omnipay-cybersource) | ✓ | - | dioscouri/omnipay-cybersource | [Dioscouri Design](https://github.com/dioscouri)
[Cybersource SOAP](https://github.com/Klinche/omnipay-cybersource-soap) | ✓ | - | dabsquared/omnipay-cybersource-soap | [DABSquared](https://github.com/DABSquared)
[DataCash](https://github.com/digitickets/omnipay-datacash) | ✓ | - | digitickets/omnipay-datacash | [DigiTickets](https://github.com/digitickets)
[Datatrans](https://github.com/w-vision/omnipay-datatrans) | ✓ | - | w-vision/datatrans | [Dominik Pfaffenbauer](https://github.com/dpfaffenbauer)
[Datatrans](https://github.com/academe/omnipay-datatrans) | ✓ | ✓ | academe/omnipay-datatrans | [Jason Judge](https://github.com/judgej)
[Docdata Payments](https://github.com/Uskur/omnipay-docdata-payments) | ✓ | - | uskur/omnipay-docdata-payments | [Uskur](https://github.com/Uskur)
[Dummy](https://github.com/thephpleague/omnipay-dummy) | ✓ | ✓ | omnipay/dummy | [Del](https://github.com/delatbabel)
[eGHL](https://github.com/dilab/omnipay-eghl) | ✓ | ✓ | dilab/omnipay-eghl | [Xu Ding](https://github.com/dilab)
[eCoin](https://github.com/hiqdev/omnipay-ecoin) | ✓ | - | hiqdev/omnipay-ecoin | [HiQDev](https://github.com/hiqdev)
[ecoPayz](https://github.com/dercoder/omnipay-ecopayz) | ✓ | - | dercoder/omnipay-ecopayz | [Alexander Fedra](https://github.com/dercoder)
[EgopayRu](https://github.com/pinguinjkeke/omnipay-egopaymentru) | ✓ | - | pinguinjkeke/omnipay-egopaymentru | [Alexander Avakov](https://github.com/pinguinjkeke)
[Elavon](https://github.com/lemonstand/omnipay-elavon) | ✓ | - | lemonstand/omnipay-elavon | [LemonStand](https://github.com/lemonstand)
[ePayments](https://github.com/hiqdev/omnipay-epayments) | ✓ | - | hiqdev/omnipay-epayments | [HiQDev](https://github.com/hiqdev)
[ePayService](https://github.com/hiqdev/omnipay-epayservice) | ✓ | - | hiqdev/omnipay-epayservice | [HiQDev](https://github.com/hiqdev)
[eWAY](https://github.com/thephpleague/omnipay-eway) | ✓ | ✓ | omnipay/eway | [Del](https://github.com/delatbabel)
[Fasapay](https://github.com/andreas22/omnipay-fasapay) | ✓ | - | andreas22/omnipay-fasapay | [Andreas Christodoulou](https://github.com/andreas22)
[Fat Zebra](https://github.com/delatbabel/omnipay-fatzebra) | ✓ | - | delatbabel/omnipay-fatzebra | [Del](https://github.com/delatbabel)
[FreeKassa](https://github.com/hiqdev/omnipay-freekassa) | ✓ | - | hiqdev/omnipay-freekassa | [HiQDev](https://github.com/hiqdev)
[First Data](https://github.com/thephpleague/omnipay-firstdata) | ✓ | - | omnipay/firstdata | [OmniPay](https://github.com/thephpleague/omnipay)
[Flo2cash](https://github.com/guisea/omnipay-flo2cash) | ✓ | - | guisea/omnipay-flo2cash | [Aaron Guise](https://github.com/guisea)
[Free / Zero Amount](https://github.com/colinodell/omnipay-zero) | ✓ | - | colinodell/omnipay-zero | [Colin O'Dell](https://github.com/colinodell)
[GiroCheckout](https://github.com/academe/Omnipay-GiroCheckout) | ✓ | ✓ | academe/omnipay-girocheckout | [Jason Judge](https://github.com/judgej)
[Globalcloudpay](https://github.com/dercoder/omnipay-globalcloudpay) | ✓ | - | dercoder/omnipay-globalcloudpay | [Alexander Fedra](https://github.com/dercoder)
[GoCardless](https://github.com/thephpleague/omnipay-gocardless) | ✓ | - | omnipay/gocardless | [Del](https://github.com/delatbabel)
[GovPayNet](https://github.com/flexcoders/omnipay-govpaynet) | ✓ | - | omnipay/omnipay-govpaynet | [FlexCoders](https://github.com/flexcoders)
[GVP (Garanti)](https://github.com/yasinkuyu/omnipay-gvp) | ✓ | - | yasinkuyu/omnipay-gvp | [Yasin Kuyu](https://github.com/yasinkuyu)
[Helcim](https://github.com/academe/omnipay-helcim) | ✓ | - | academe/omnipay-helcim | [Jason Judge](https://github.com/judgej)
[iDram](https://github.com/ptuchik/omnipay-idram) | - | ✓ | ptuchik/omnipay-idram | [Avik Aghajanyan](https://github.com/ptuchik)
[iPay88](https://github.com/dilab/omnipay-ipay88) | ✓ | ✓ | dilab/omnipay-ipay88 | [Xu Ding](https://github.com/dilab)
[IfthenPay](https://github.com/ifthenpay/omnipay-ifthenpay) | ✓ | - | ifthenpay/omnipay-ifthenpay | [Rafael Almeida](https://github.com/rafaelcpalmeida)
[InterKassa](https://github.com/hiqdev/omnipay-interkassa) | ✓ | - | hiqdev/omnipay-interkassa | [HiQDev](https://github.com/hiqdev)
[Iyzico](https://github.com/yasinkuyu/omnipay-iyzico) | ✓ | - | yasinkuyu/omnipay-iyzico | [Yasin Kuyu](https://github.com/yasinkuyu)
[Judo Pay](https://github.com/Transportersio/omnipay-judopay) | ✓ | - | transportersio/omnipay-judopay | [Transporters.io](https://github.com/Transportersio)
[Klarna Checkout](https://github.com/MyOnlineStore/omnipay-klarna-checkout) | ✓ | ✓ | myonlinestore/omnipay-klarna-checkout | [MyOnlineStore](https://github.com/MyOnlineStore)
[Komerci (Rede, former RedeCard)](https://github.com/byjg/omnipay-komerci) | ✓ | - | byjg/omnipay-komerci | [João Gilberto Magalhães](https://github.com/byjg)
[Komoju](https://github.com/dannyvink/omnipay-komoju) | ✓ | - | vink/omnipay-komoju | [Danny Vink](https://github.com/dannyvink)
[Midtrans](https://github.com/dilab/omnipay-midtrans) | ✓ | ✓ | dilab/omnipay-midtrans | [Xu Ding](https://github.com/dilab)
[Magnius](https://github.com/fruitcake/omnipay-magnius) | - | ✓ | fruitcake/omnipay-magnius | [Fruitcake](https://github.com/fruitcake)
[Manual](https://github.com/thephpleague/omnipay-manual) | ✓ | - | omnipay/manual | [Del](https://github.com/delatbabel)
[Migs](https://github.com/thephpleague/omnipay-migs) | ✓ | - | omnipay/migs | [Omnipay](https://github.com/thephpleague/omnipay)
[Mollie](https://github.com/thephpleague/omnipay-mollie) | ✓ | ✓ | omnipay/mollie | [Barry vd. Heuvel](https://github.com/barryvdh)
[MOLPay](https://github.com/leesiongchan/omnipay-molpay) | ✓ | - | leesiongchan/molpay | [Lee Siong Chan](https://github.com/leesiongchan)
[MultiCards](https://github.com/incube8/omnipay-multicards) | ✓ | - | incube8/omnipay-multicards | [Del](https://github.com/delatbabel)
[MultiSafepay](https://github.com/thephpleague/omnipay-multisafepay) | ✓ | - | omnipay/multisafepay | [Alexander Deruwe](https://github.com/aderuwe)
[MyCard](https://github.com/xxtime/omnipay-mycard) | ✓ | - | xxtime/omnipay-mycard | [Joe Chu](https://github.com/xxtime)
[National Australia Bank (NAB) Transact](https://github.com/sudiptpa/omnipay-nabtransact) | ✓ | ✓ | sudiptpa/omnipay-nabtransact | [Sujip Thapa](https://github.com/sudiptpa)
[NestPay (EST)](https://github.com/yasinkuyu/omnipay-nestpay) | ✓ | - | yasinkuyu/omnipay-nestpay | [Yasin Kuyu](https://github.com/yasinkuyu)
[Netaxept (BBS)](https://github.com/thephpleague/omnipay-netaxept) | ✓ | - | omnipay/netaxept | [Omnipay](https://github.com/thephpleague/omnipay)
[Netbanx](https://github.com/thephpleague/omnipay-netbanx) | ✓ | - | omnipay/netbanx | [Maks Rafalko](https://github.com/borNfreee)
[Neteller](https://github.com/dercoder/omnipay-neteller) | ✓ | - | dercoder/omnipay-neteller | [Alexander Fedra](https://github.com/dercoder)
[NetPay](https://github.com/netpay/omnipay-netpay) | ✓ | - | netpay/omnipay-netpay | [NetPay](https://github.com/netpay)
[Network Merchants Inc. (NMI)](https://github.com/mfauveau/omnipay-nmi) | ✓ | - | mfauveau/omnipay-nmi | [Matthieu Fauveau](https://github.com/mfauveau)
[Nocks](https://github.com/nocksapp/checkout-omnipay) | ✓ | - | nocksapp/omnipay-nocks | [Nocks](https://github.com/nocksapp)
[OkPay](https://github.com/hiqdev/omnipay-okpay) | ✓ | - | hiqdev/omnipay-okpay | [HiQDev](https://github.com/hiqdev)
[OnePay](https://github.com/dilab/omnipay-onepay) | ✓ | ✓ | dilab/omnipay-onepay | [Xu Ding](https://github.com/dilab)
[Oppwa](https://github.com/vdbelt/omnipay-oppwa) | ✓ | ✓ | vdbelt/omnipay-oppwa | [Martin van de Belt](https://github.com/vdbelt)
[Payoo](https://github.com/dilab/omnipay-payoo) | ✓ | ✓ | dilab/omnipay-payoo | [Xu Ding](https://github.com/dilab)
[Pacnet](https://github.com/mfauveau/omnipay-pacnet) | ✓ | - | mfauveau/omnipay-pacnet | [Matthieu Fauveau](https://github.com/mfauveau)
[Pagar.me](https://github.com/descubraomundo/omnipay-pagarme) | ✓ | - | descubraomundo/omnipay-pagarme | [Descubra o Mundo](https://github.com/descubraomundo)
[Paratika (Asseco)](https://github.com/yasinkuyu/omnipay-paratika) | ✓ | - | yasinkuyu/omnipay-paratika | [Yasin Kuyu](https://github.com/yasinkuyu)
[PayFast](https://github.com/thephpleague/omnipay-payfast) | ✓ | - | omnipay/payfast | [Omnipay](https://github.com/thephpleague/omnipay)
[Payflow](https://github.com/thephpleague/omnipay-payflow) | ✓ | - | omnipay/payflow | [Del](https://github.com/delatbabel)
[PaymentExpress (DPS)](https://github.com/thephpleague/omnipay-paymentexpress) | ✓ | - | omnipay/paymentexpress | [Del](https://github.com/delatbabel)
[PaymentExpress / DPS (A2A)](https://github.com/onlinesid/omnipay-paymentexpress-a2a) | ✓ | - | onlinesid/omnipay-paymentexpress-a2a | [Sid](https://github.com/onlinesid)
[PaymentgateRu](https://github.com/pinguinjkeke/omnipay-paymentgateru) | ✓ | ✓ | pinguinjkeke/omnipay-paymentgateru | [Alexander Avakov](https://github.com/pinguinjkeke)
[PaymentSense](https://github.com/digitickets/omnipay-paymentsense) | ✓ | - | digitickets/omnipay-paymentsense | [DigiTickets](https://github.com/digitickets)
[PaymentWall](https://github.com/incube8/omnipay-paymentwall) | ✓ | - | incube8/omnipay-paymentwall | [Del](https://github.com/delatbabel)
[PayPal](https://github.com/thephpleague/omnipay-paypal) | ✓ | ✓ | omnipay/paypal | [Del](https://github.com/delatbabel)
[PayPro](https://github.com/payproNL/omnipay-paypro) | ✓ | - | paypronl/omnipay-paypro | [Fruitcake](https://github.com/fruitcake)
[PAYONE](https://github.com/academe/omnipay-payone) | ✓ | ✓ | academe/omnipay-payone | [Jason Judge](https://github.com/judgej)
[Paysafecard](https://github.com/dercoder/omnipay-paysafecard) | ✓ | - | dercoder/omnipay-paysafecard | [Alexander Fedra](https://github.com/dercoder)
[Paysera](https://github.com/povils/omnipay-paysera) | ✓ | - | povils/omnipay-paysera | [Povils](https://github.com/povils)
[PaySimple](https://github.com/dranes/omnipay-paysimple) | ✓ | - | dranes/omnipay-paysimple | [Dranes](https://github.com/dranes)
[PaySsion](https://github.com/InkedCurtis/omnipay-payssion) | ✓ | - | inkedcurtis/omnipay-payssion | [Curtis](https://github.com/inkedcurtis)
[PayTrace](https://github.com/iddqdidkfa/omnipay-paytrace) | ✓ | - | softcommerce/omnipay-paytrace | [Oleg Ilyushyn](https://github.com/iddqdidkfa)
[PayU](https://github.com/efesaid/omnipay-payu) | ✓ | - | omnipay/payu | [efesaid](https://github.com/efesaid)
[Paxum](https://github.com/hiqdev/omnipay-paxum) | ✓ | - | hiqdev/omnipay-paxum | [HiQDev](https://github.com/hiqdev)
[Pelecard](https://github.com/Uskur/omnipay-pelecard) | ✓ | - | uskur/omnipay-pelecard | [Uskur](https://github.com/Uskur)
[Pin Payments](https://github.com/thephpleague/omnipay-pin) | ✓ | - | omnipay/pin | [Del](https://github.com/delatbabel)
[Ping++](https://github.com/phoenixg/omnipay-pingpp) | ✓ | - | phoenixg/omnipay-pingpp | [Huang Feng](https://github.com/phoenixg)
[POLi](https://github.com/burnbright/omnipay-poli) | ✓ | - | burnbright/omnipay-poli | [Sid](https://github.com/onlinesid)
[Portmanat](https://github.com/dercoder/omnipay-portmanat) | ✓ | - | dercoder/omnipay-portmanat | [Alexander Fedra](https://github.com/dercoder)
[Posnet](https://github.com/yasinkuyu/omnipay-posnet) | ✓ | - | yasinkuyu/omnipay-posnet | [Yasin Kuyu](https://github.com/yasinkuyu)
[Postfinance](https://github.com/bummzack/omnipay-postfinance) | ✓ | - | bummzack/omnipay-postfinance | [Roman Schmid](https://github.com/bummzack)
[Qiwi](https://github.com/hiqdev/omnipay-qiwi) | ✓ | - | hiqdev/omnipay-qiwi | [HiQDev](https://github.com/hiqdev)
[Quickpay](https://github.com/NobrainerWeb/omnipay-quickpay) | ✓ | - | nobrainerweb/omnipay-quickpay | [Nobrainer Web](https://github.com/NobrainerWeb)
[Realex](https://github.com/digitickets/omnipay-realex) | ✓ | - | digitickets/omnipay-realex | [DigiTickets](https://github.com/digitickets)
[RedSys](https://github.com/jsampedro77/sermepa-omnipay) | ✓ | - | nazka/sermepa-omnipay | [Javier Sampedro](https://github.com/jsampedro77)
[RentMoola](https://github.com/rentmoola/omnipay-rentmoola) | ✓ | - | rentmoola/omnipay-rentmoola | [Geoff Shaw](https://github.com/Shawg)
[RoboKassa](https://github.com/hiqdev/omnipay-robokassa) | ✓ | - | hiqdev/omnipay-robokassa | [HiQDev](https://github.com/hiqdev)
[Sage Pay](https://github.com/thephpleague/omnipay-sagepay) | ✓ | ✓ | omnipay/sagepay | [Jason Judge](https://github.com/judgej)
[Sberbank](https://github.com/AndrewNovikof/omnipay-sberbank) | - | ✓ | andrewnovikof/omnipay-sberbank | [Andrew Novikov](https://github.com/AndrewNovikof)
[SecPay](https://github.com/justinbusschau/omnipay-secpay) | ✓ | - | justinbusschau/omnipay-secpay | [Justin Busschau](https://github.com/justinbusschau)
[SecurePay](https://github.com/thephpleague/omnipay-securepay) | ✓ | ✓ | omnipay/securepay | [Omnipay](https://github.com/thephpleague/omnipay)
[Secure Trading](https://github.com/meebio/omnipay-secure-trading) | ✓ | - | meebio/omnipay-secure-trading | [John Jablonski](https://github.com/jan-j)
[Sisow](https://github.com/fruitcake/omnipay-sisow) | ✓ | ✓ | fruitcakestudio/omnipay-sisow | [Fruitcake](https://github.com/fruitcake)
[Skrill](https://github.com/alfaproject/omnipay-skrill) | ✓ | - | alfaproject/omnipay-skrill | [João Dias](https://github.com/alfaproject)
[Sofort](https://github.com/aimeoscom/omnipay-sofort) | ✓ | - | aimeoscom/omnipay-sofort | [Aimeos GmbH](https://github.com/aimeoscom)
[Spreedly](https://github.com/gregoriohc/omnipay-spreedly) | ✓ | - | gregoriohc/omnipay-spreedly | [Gregorio Hernández Caso](https://github.com/gregoriohc)
[Square](https://github.com/Transportersio/omnipay-square) | ✓ | - | transportersio/omnipay-square | [Transporters.io](https://github.com/Transportersio)
[Stripe](https://github.com/thephpleague/omnipay-stripe) | ✓ | ✓ | omnipay/stripe | [Del](https://github.com/delatbabel)
[TargetPay](https://github.com/thephpleague/omnipay-targetpay) | ✓ | - | omnipay/targetpay | [Alexander Deruwe](https://github.com/aderuwe)
[UnionPay](https://github.com/lokielse/omnipay-unionpay) | ✓ | ✓ | lokielse/omnipay-unionpay | [Loki Else](https://github.com/lokielse)
[Vantiv](https://github.com/lemonstand/omnipay-vantiv) | ✓ | - | lemonstand/omnipay-vantiv | [LemonStand](https://github.com/lemonstand)
[Veritrans](https://github.com/andylibrian/omnipay-veritrans) | ✓ | - | andylibrian/omnipay-veritrans | [Andy Librian](https://github.com/andylibrian)
[Vindicia](https://github.com/vimeo/omnipay-vindicia) | ✓ | - | vimeo/omnipay-vindicia | [Vimeo](https://github.com/vimeo)
[VivaPayments](https://github.com/delatbabel/omnipay-vivapayments) | ✓ | - | delatbabel/omnipay-vivapayments | [Del](https://github.com/delatbabel)
[WebMoney](https://github.com/dercoder/omnipay-webmoney) | ✓ | - | dercoder/omnipay-webmoney | [Alexander Fedra](https://github.com/dercoder)
[WeChat](https://github.com/labs7in0/omnipay-wechat) | ✓ | - | labs7in0/omnipay-wechat | [7IN0's Labs](https://github.com/labs7in0)
[WechatPay](https://github.com/lokielse/omnipay-wechatpay) | ✓ | ✓ | lokielse/omnipay-wechatpay |  [Loki Else](https://github.com/lokielse)
[WePay](https://github.com/collizo4sky/omnipay-wepay) | ✓ | - | collizo4sky/omnipay-wepay | [Agbonghama Collins](https://github.com/collizo4sky)
[Wirecard](https://github.com/igaponov/omnipay-wirecard) | ✓ | ✓ | igaponov/omnipay-wirecard | [Igor Gaponov](https://github.com/igaponov)
[Wirecard](https://github.com/academe/omnipay-wirecard) | ✓ | - | academe/omnipay-wirecard | [Jason Judge](https://github.com/judgej)
[Worldpay XML Direct Corporate Gateway](https://github.com/teaandcode/omnipay-worldpay-xml) | ✓ | - | teaandcode/omnipay-worldpay-xml | [Dave Nash](https://github.com/teaandcode)
[Worldpay XML Hosted Corporate Gateway](https://github.com/comicrelief/omnipay-worldpay-cg-hosted) | ✓ | - | comicrelief/omnipay-worldpay-cg-hosted | [Comic Relief](https://github.com/comicrelief)
[Worldpay Business Gateway](https://github.com/thephpleague/omnipay-worldpay) | ✓ | ✓ | omnipay/worldpay | [Omnipay](https://github.com/thephpleague/omnipay)
[Yandex.Money](https://github.com/yandex-money/yandex-money-cms-omnipay) | ✓ | - | yandexmoney/omnipay | [Roman Ananyev](https://github.com/aTastyCookie/)
[Yandex.Money for P2P payments](https://github.com/hiqdev/omnipay-yandexmoney) | ✓ | - | hiqdev/omnipay-yandexmoney | [HiQDev](https://github.com/hiqdev)
[Tpay](https://github.com/tpay-com/omnipay-tpay) | ✓ | - | omnipay/tpay | [Tpay.com](https://github.com/tpay-com)


Gateways are created and initialized like so:

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('PayPal_Express');
$gateway->setUsername('adrian');
$gateway->setPassword('12345');
```

Most settings are gateway specific. If you need to query a gateway to get a list
of available settings, you can call `getDefaultParameters()`:

```php
$settings = $gateway->getDefaultParameters();
// default settings array format:
array(
    'username' => '', // string variable
    'testMode' => false, // boolean variable
    'landingPage' => array('billing', 'login'), // enum variable, first item should be treated as default
);
```

Generally most payment gateways can be classified as one of two types:

* Off-site gateways such as PayPal Express, where the customer is redirected to a third party site to enter payment details
* On-site (merchant-hosted) gateways such as PayPal Pro, where the customer enters their credit card details on your site

However, there are some gateways such as Sage Pay Direct, where you take credit card details on site, then optionally redirect
if the customer's card supports 3D Secure authentication. Therefore, there is no point differentiating between the two types of
gateway (other than by the methods they support).

## Credit Card / Payment Form Input

User form input is directed to an [CreditCard](https://github.com/thephpleague/omnipay-common/blob/master/src/Common/CreditCard.php)
object. This provides a safe way to accept user input.

The `CreditCard` object has the following fields:

* firstName
* lastName
* number
* expiryMonth
* expiryYear
* startMonth
* startYear
* cvv
* issueNumber
* type
* billingAddress1
* billingAddress2
* billingCity
* billingPostcode
* billingState
* billingCountry
* billingPhone
* shippingAddress1
* shippingAddress2
* shippingCity
* shippingPostcode
* shippingState
* shippingCountry
* shippingPhone
* company
* email

Even off-site gateways make use of the `CreditCard` object, because often you need to pass
customer billing or shipping details through to the gateway.

The `CreditCard` object can be initialized with untrusted user input via the constructor.
Any fields passed to the constructor which are not recognized will be ignored.

```php
$formInputData = array(
    'firstName' => 'Bobby',
    'lastName' => 'Tables',
    'number' => '4111111111111111',
);
$card = new CreditCard($formInputData);
```

You can also just pass the form data array directly to the gateway, and a `CreditCard` object
will be created for you.

CreditCard fields can be accessed using getters and setters:

```php
$number = $card->getNumber();
$card->setFirstName('Adrian');
```

If you submit credit card details which are obviously invalid (missing required fields, or a number
which fails the Luhn check), [InvalidCreditCardException](https://github.com/thephpleague/omnipay-common/blob/master/src/Omnipay/Common/Exception/InvalidCreditCardException.php)
will be thrown.  You should validate the card details using your framework's validation library
before submitting the details to your gateway, to avoid unnecessary API calls.

For on-site payment gateways, the following card fields are generally required:

* firstName
* lastName
* number
* expiryMonth
* expiryYear
* cvv

You can also verify the card number using the Luhn algorithm by calling `Helper::validateLuhn($number)`.

## Gateway Methods

The main methods implemented by gateways are:

* `authorize($options)` - authorize an amount on the customer's card
* `completeAuthorize($options)` - handle return from off-site gateways after authorization
* `capture($options)` - capture an amount you have previously authorized
* `purchase($options)` - authorize and immediately capture an amount on the customer's card
* `completePurchase($options)` - handle return from off-site gateways after purchase
* `refund($options)` - refund an already processed transaction
* `void($options)` - generally can only be called up to 24 hours after submitting a transaction
* `acceptNotification()` - convert an incoming request from an off-site gateway to a generic notification object
  for further processing

On-site gateways do not need to implement the `completeAuthorize` and `completePurchase` methods. Gateways that don't
receive payment notifications don't need to implement `acceptNotification`. If any gateway does not support certain
features (such as refunds), it will throw `BadMethodCallException`.

All gateway methods except `acceptNotification` take an `$options` array as an argument. The `acceptNotification` method
does not take any parameters and will access the HTTP URL variables or POST data implicitly. Each gateway differs in
which parameters are required, and the gateway will throw `InvalidRequestException` if you omit any required parameters.
All gateways will accept a subset of these options:

* card
* token
* amount
* currency
* description
* transactionId
* clientIp
* returnUrl
* cancelUrl

Pass the options through to the method like so:

```php
$card = new CreditCard($formData);
$request = $gateway->authorize(array(
    'amount' => '10.00', // this represents $10.00
    'card' => $card,
    'returnUrl' => 'https://www.example.com/return',
));
```

When calling the `completeAuthorize` or `completePurchase` methods, the exact same arguments should be provided as
when you made the initial `authorize` or `purchase` call (some gateways will need to verify for example the actual
amount paid equals the amount requested). The only parameter you can omit is `card`.

To summarize the various parameters you have available to you:

* Gateway settings (e.g. username and password) are set directly on the gateway. These settings apply to all payments, and generally you will store these in a configuration file or in the database.
* Method options are used for any payment-specific options, which are not set by the customer. For example, the payment `amount`, `currency`, `transactionId` and `returnUrl`.
* CreditCard parameters are data which the user supplies. For example, you want the user to specify their `firstName` and `billingCountry`, but you don't want a user to specify the payment `currency` or `returnUrl`.

## The Payment Response

The payment response must implement [ResponseInterface](https://github.com/thephpleague/omnipay-common/blob/master/src/Omnipay/Common/Message/ResponseInterface.php). There are two main types of response:

* Payment was successful (standard response)
* Website requires redirect to off-site payment form (redirect response)

### Successful Response

For a successful responses, a reference will normally be generated, which can be used to capture or refund the transaction
at a later date. The following methods are always available:

```php
$response = $gateway->purchase(array('amount' => '10.00', 'card' => $card))->send();

$response->isSuccessful(); // is the response successful?
$response->isRedirect(); // is the response a redirect?
$response->getTransactionReference(); // a reference generated by the payment gateway
$response->getTransactionId(); // the reference set by the originating website if available.
$response->getMessage(); // a message generated by the payment gateway
```

In addition, most gateways will override the response object, and provide access to any extra fields returned by the gateway.

### Redirect Response

The redirect response is further broken down by whether the customer's browser must redirect using GET (RedirectResponse object), or
POST (FormRedirectResponse). These could potentially be combined into a single response class, with a `getRedirectMethod()`.

After processing a payment, the cart should check whether the response requires a redirect, and if so, redirect accordingly:

```php
$response = $gateway->purchase(array('amount' => '10.00', 'card' => $card))->send();
if ($response->isSuccessful()) {
    // payment is complete
} elseif ($response->isRedirect()) {
    $response->redirect(); // this will automatically forward the customer
} else {
    // not successful
}
```

The customer isn't automatically forwarded on, because often the cart or developer will want to customize the redirect method
(or if payment processing is happening inside an AJAX call they will want to return JS to the browser instead).

To display your own redirect page, simply call `getRedirectUrl()` on the response, then display it accordingly:

```php
$url = $response->getRedirectUrl();
// for a form redirect, you can also call the following method:
$data = $response->getRedirectData(); // associative array of fields which must be posted to the redirectUrl
```

## Error Handling

You can test for a successful response by calling `isSuccessful()` on the response object. If there
was an error communicating with the gateway, or your request was obviously invalid, an exception
will be thrown. In general, if the gateway does not throw an exception, but returns an unsuccessful
response, it is a message you should display to the customer. If an exception is thrown, it is
either a bug in your code (missing required fields), or a communication error with the gateway.

You can handle both scenarios by wrapping the entire request in a try-catch block:

```php
try {
    $response = $gateway->purchase(array('amount' => '10.00', 'card' => $card))->send();
    if ($response->isSuccessful()) {
        // mark order as complete
    } elseif ($response->isRedirect()) {
        $response->redirect();
    } else {
        // display error to customer
        exit($response->getMessage());
    }
} catch (\Exception $e) {
    // internal error, log exception and display a generic message to the customer
    exit('Sorry, there was an error processing your payment. Please try again later.');
}
```

## Test mode and developer mode
  Most gateways allow you to set up a sandbox or developer account which uses a different url
  and credentials. Some also allow you to do test transactions against the live site, which does
  not result in a live transaction.
  
  Gateways that implement only the developer account (most of them) call it testMode. Authorize.net,
  however, implements both and refers to this mode as developerMode.  
  
  When implementing with multiple gateways you should use a construct along the lines of the following:
```php
if ($is_developer_mode) {
    if (method_exists($gateway, 'setDeveloperMode')) {
        $gateway->setDeveloperMode(TRUE);
    } else {
        $gateway->setTestMode(TRUE);
    }
}
```

## Token Billing

Token billing allows you to store a credit card with your gateway, and charge it at a later date.
Token billing is not supported by all gateways. For supported gateways, the following methods
are available:

* `createCard($options)` - returns a response object which includes a `cardReference`, which can be used for future transactions
* `updateCard($options)` - update a stored card, not all gateways support this method
* `deleteCard($options)` - remove a stored card, not all gateways support this method

Once you have a `cardReference`, you can use it instead of the `card` parameter when creating a charge:

    $gateway->purchase(array('amount' => '10.00', 'cardReference' => 'abc'));

## Recurring Billing

At this stage, automatic recurring payments functionality is out of scope for this library.
This is because there is likely far too many differences between how each gateway handles
recurring billing profiles. Also in most cases token billing will cover your needs, as you can
store a credit card then charge it on whatever schedule you like. Feel free to get in touch if
you really think this should be a core feature and worth the effort.

## Incoming Notifications

Some gateways (e.g. Cybersource, GoPay) offer HTTP notifications to inform the merchant about the completion (or, in
general, status) of the payment. To assist with handling such notifications, the `acceptNotification()` method will
extract the transaction reference and payment status from the HTTP request and return a generic `NotificationInterface`.

```php
$notification = $gateway->acceptNotification();

$notification->getTransactionReference(); // A reference provided by the gateway to represent this transaction
$notification->getTransactionStatus(); // Current status of the transaction, one of NotificationInterface::STATUS_*
$notification->getMessage(); // Additional message, if any, provided by the gateway

// update the status of the corresponding transaction in your database
```

**Note:** some earlier gateways used the `completeAuthorize` and `completePurchase` messages to handle the incoming
notifications. These are being converted and the `complete*` messages deprecated.
They won't be removed in OmniPay 2.x, but it is advisable to switch to the `acceptNotification` message when convenient.
An example is Sage Pay Server [completeAuthorize](https://github.com/thephpleague/omnipay-sagepay/blob/master/src/ServerGateway.php#L81)
which is now handled by [acceptNotification](https://github.com/thephpleague/omnipay-sagepay/blob/master/src/ServerGateway.php#L40).

## Example Application

An example application is provided in the [omnipay/example](https://github.com/thephpleague/omnipay-example) repo.
You can run it using PHP's built in web server (PHP 5.4+):

    $ php composer.phar update --dev
    $ php -S localhost:8000

For more information, see the [Omnipay example application](https://github.com/thephpleague/omnipay-example).

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the GitHub issue tracker
for the appropriate package, or better yet, fork the library and submit a pull request.

## Security
If you discover any security related issues, please email barryvdh@gmail.com instead of using the issue tracker.


## Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please head on over to the [mailing list](https://groups.google.com/forum/#!forum/omnipay)
and point out what you do and don't like, or fork the project and make suggestions. **No issue is too small.**
