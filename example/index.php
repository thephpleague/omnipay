<?php

/*
 * This file is part of the Tala Payments package.
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
    $gateways = Tala\GatewayFactory::getAvailableGateways();

    return $app['twig']->render('index.twig', array(
        'gateways' => $gateways,
    ));
});

// gateway settings
$app->get('/gateways/{name}', function($name) use ($app) {
    $gateway = Tala\GatewayFactory::create($name);
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
    $gateway = Tala\GatewayFactory::create($name);
    $settings = $app['request']->get('gateway');
    $gateway->initialize($settings);
    $app['session']->set('gateway.'.$gateway->getShortName(), $gateway->toArray());

    // redirect to gateway settings page
    $app['session']->setFlash('success', 'Gateway settings updated!');
    return $app->redirect($app['request']->getPathInfo());
});

$app->run();
