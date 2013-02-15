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
    }, Omnipay\Common\GatewayFactory::getAvailableGateways());

    return $app['twig']->render('index.twig', array(
        'gateways' => $gateways,
    ));
});

// gateway settings
$app->get('/gateways/{name}', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $settings = $app['session']->get('gateway.'.$gateway->getShortName());
    $gateway->initialize($settings);

    return $app['twig']->render('gateway.twig', array(
        'gateway' => $gateway,
        'settings' => $gateway->toArray(),
    ));
});

// save gateway settings
$app->post('/gateways/{name}', function($name) use ($app) {
    // store gateway settings in session
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $settings = $app['request']->get('gateway');
    $gateway->initialize($settings);
    $app['session']->set('gateway.'.$gateway->getShortName(), $gateway->toArray());

    // redirect to gateway settings page
    $app['session']->setFlash('success', 'Gateway settings updated!');

    return $app->redirect($app['request']->getPathInfo());
});

// start gateway purchase
$app->get('/gateways/{name}/purchase', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $settings = $app['session']->get('gateway.'.$gateway->getShortName());
    $gateway->initialize($settings);

    $params = $app['session']->get('params', array());
    $card = new Omnipay\Common\CreditCard($app['session']->get('card'));

    return $app['twig']->render('purchase.twig', array(
        'gateway' => $gateway,
        'params' => $params,
        'card' => $card->toArray(),
    ));
});

// make gateway purchase
$app->post('/gateways/{name}/purchase', function($name) use ($app) {
    $gateway = Omnipay\Common\GatewayFactory::create($name);
    $settings = $app['session']->get('gateway.'.$gateway->getShortName());
    $gateway->initialize($settings);

    // load POST data
    $params = $app['request']->get('params');
    $card = new Omnipay\Common\CreditCard($app['request']->get('card'));

    // save POST data into session
    $app['session']->set('params', $params);
    $app['session']->set('card', $card);

    $params['card'] = $card;
    $response = $gateway->purchase($params);

    return $app['twig']->render('response.twig', array(
        'gateway' => $gateway,
        'response' => $response,
    ));
});

$app->run();
