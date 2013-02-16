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
    $gateway->initialize($app['session']->get($sessionVar));

    return $app['twig']->render('gateway.twig', array(
        'gateway' => $gateway,
        'settings' => $gateway->toArray(),
    ));
});

// save gateway settings
$app->post('/gateways/{name}', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize($app['request']->get('gateway'));

    // save gateway settings in session
    $app['session']->set($sessionVar, $gateway->toArray());

    // redirect back to gateway settings page
    $app['session']->setFlash('success', 'Gateway settings updated!');

    return $app->redirect($app['request']->getPathInfo());
});

// create gateway authorize
$app->get('/gateways/{name}/authorize', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize($app['session']->get($sessionVar));

    $params = $app['session']->get($sessionVar.'.authorize', array());
    $card = new Omnipay\Common\CreditCard($app['session']->get($sessionVar.'.card'));

    return $app['twig']->render('request.twig', array(
        'gateway' => $gateway,
        'method' => 'authorize',
        'params' => $params,
        'card' => $card->toArray(),
    ));
});

// submit gateway authorize
$app->post('/gateways/{name}/authorize', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize($app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');
    $card = $app['request']->get('card');

    // save POST data into session
    $app['session']->set($sessionVar.'.authorize', $params);
    $app['session']->set($sessionVar.'.card', $card);

    $params['card'] = $card;
    $response = $gateway->authorize($params);

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

// create gateway capture
$app->get('/gateways/{name}/capture', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize($app['session']->get($sessionVar));

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
    $gateway->initialize($app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');

    // save POST data into session
    $app['session']->set($sessionVar.'.capture', $params);

    $response = $gateway->capture($params);

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

// create gateway purchase
$app->get('/gateways/{name}/purchase', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize($app['session']->get($sessionVar));

    $params = $app['session']->get($sessionVar.'.purchase', array());
    $card = new Omnipay\Common\CreditCard($app['session']->get($sessionVar.'.card'));

    return $app['twig']->render('request.twig', array(
        'gateway' => $gateway,
        'method' => 'purchase',
        'params' => $params,
        'card' => $card->toArray(),
    ));
});

// submit gateway purchase
$app->post('/gateways/{name}/purchase', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $sessionVar = 'omnipay.'.$gateway->getShortName();
    $gateway->initialize($app['session']->get($sessionVar));

    // load POST data
    $params = $app['request']->get('params');
    $card = $app['request']->get('card');

    // save POST data into session
    $app['session']->set($sessionVar.'.purchase', $params);
    $app['session']->set($sessionVar.'.card', $card);

    $params['card'] = $card;
    $response = $gateway->purchase($params);

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

$app->run();
