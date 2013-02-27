<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../vendor/autoload.php';

// create basic Silex application
$app = new Silex\Application();
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// enable Silex debugging
$app['debug'] = true;

// twig globals
$app->before( function() use ( $app ) {
    $notice = $app['session']->getFlash('notice');
    if ($notice) {
        $app['twig']->addGlobal('notice', $notice);
    }
});

// root route
$app->get('/', function() use ($app) {
    $gateways = array_map(function($name) {
        return Omnipay\Common\GatewayFactory::create($name);
    }, Omnipay\Common\GatewayFactory::find());

    return $app['twig']->render('index.twig', array(
        'gateways' => $gateways,
    ));
});

// gateway settings
$app->get('/gateways/{name}', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    return $app['twig']->render('gateway.twig', array(
        'gateway' => $gateway,
        'settings' => $gateway->getParameters(),
    ));
});

// save gateway settings
$app->post('/gateways/{name}', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['request']->get('gateway'));

    // save gateway settings in session
    $app['session']->set($sessionVar, $gateway->getParameters());

    // redirect back to gateway settings page
    $app['session']->setFlash('success', 'Gateway settings updated!');

    return $app->redirect($app['request']->getPathInfo());
});

// create gateway authorize
$app->get('/gateways/{name}/authorize', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    $params = $app['session']->get($sessionVar.'.authorize', array());
    $card = new Omnipay\Common\CreditCard($app['session']->get($sessionVar.'.card'));

    return $app['twig']->render('request.twig', array(
        'gateway' => $gateway,
        'method' => 'authorize',
        'params' => $params,
        'card' => $card->getParameters(),
    ));
});

// submit gateway authorize
$app->post('/gateways/{name}/authorize', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');
    $card = $app['request']->get('card');

    // save POST data into session
    $app['session']->set($sessionVar.'.authorize', $params);
    $app['session']->set($sessionVar.'.card', $card);

    $params['card'] = $card;
    $params['clientIp'] = $app['request']->getClientIp();
    $response = $gateway->authorize($params)->send();

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

// create gateway capture
$app->get('/gateways/{name}/capture', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    $params = $app['session']->get($sessionVar.'.capture', array());

    return $app['twig']->render('request.twig', array(
        'gateway' => $gateway,
        'method' => 'capture',
        'params' => $params,
    ));
});

// submit gateway capture
$app->post('/gateways/{name}/capture', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');

    // save POST data into session
    $app['session']->set($sessionVar.'.capture', $params);

    $params['clientIp'] = $app['request']->getClientIp();
    $response = $gateway->capture($params)->send();

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

// create gateway purchase
$app->get('/gateways/{name}/purchase', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    $params = $app['session']->get($sessionVar.'.purchase', array());
    $card = new Omnipay\Common\CreditCard($app['session']->get($sessionVar.'.card'));

    return $app['twig']->render('request.twig', array(
        'gateway' => $gateway,
        'method' => 'purchase',
        'params' => $params,
        'card' => $card->getParameters(),
    ));
});

// submit gateway purchase
$app->post('/gateways/{name}/purchase', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');
    $card = $app['request']->get('card');

    // save POST data into session
    $app['session']->set($sessionVar.'.purchase', $params);
    $app['session']->set($sessionVar.'.card', $card);

    $params['card'] = $card;
    $params['clientIp'] = $app['request']->getClientIp();
    $response = $gateway->purchase($params)->send();

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

// gateway purchase return
// this won't work for gateways which require an internet-accessible URL (yet)
$app->match('/gateways/{name}/completePurchase', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    // load request data from session
    $params = $app['session']->get($sessionVar.'.purchase', array());

    $params['clientIp'] = $app['request']->getClientIp();
    $response = $gateway->completePurchase($params)->send();

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

// create gateway store
$app->get('/gateways/{name}/store', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    $params = $app['session']->get($sessionVar.'.store', array());
    $card = new Omnipay\Common\CreditCard($app['session']->get($sessionVar.'.card'));

    return $app['twig']->render('request.twig', array(
        'gateway' => $gateway,
        'method' => 'store',
        'params' => $params,
        'card' => $card->getParameters(),
    ));
});

// submit gateway store
$app->post('/gateways/{name}/store', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');
    $card = $app['request']->get('card');

    // save POST data into session
    $app['session']->set($sessionVar.'.store', $params);
    $app['session']->set($sessionVar.'.card', $card);

    $params['card'] = $card;
    $params['clientIp'] = $app['request']->getClientIp();
    $response = $gateway->store($params)->send();

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

// create gateway unstore
$app->get('/gateways/{name}/unstore', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    $params = $app['session']->get($sessionVar.'.unstore', array());

    return $app['twig']->render('request.twig', array(
        'gateway' => $gateway,
        'method' => 'unstore',
        'params' => $params,
    ));
});

// submit gateway unstore
$app->post('/gateways/{name}/unstore', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize((array) $app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');

    // save POST data into session
    $app['session']->set($sessionVar.'.unstore', $params);

    $params['clientIp'] = $app['request']->getClientIp();
    $response = $gateway->unstore($params)->send();

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

$app->run();
