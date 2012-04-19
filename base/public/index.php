<?php

//
// Slim is used for routing.
// Twig is used for by Slim for template rendering of pages.
//
require_once '../lib/Slim/Slim.php';
require_once '../lib/Slim-Extras/Views/TwigView.php';

//
// Configuration of Twig and Slim
//
TwigView::$twigDirectory = '../lib/Twig/';
TwigView::$twigExtensions = array('Twig_Extensions_Slim');
TwigView::$twigOptions = array(
    'debug' => false,
    'charset' => 'utf-8',
    'cache' => realpath('../templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);

$app = new Slim(array(
    'debug' => true,
    'log.enabled' => true,
    'log.level' => 4,
    'templates.path' => '../templates',
    'view' => new TwigView()
));

//
// Useful variables available to all view templates
//
$app->view()->setData('rootUri', $app->request()->getRootUri() );

// GET routes
$app->get('/', function ($name) use ($app) {
    return $app->render('index.html', array('name' => $name ));
});

$app->get('/test/:name', function ($name) use ($app) {
    return $app->render('index.html', array('name' => $name ));
});

//
// JSON API 
//
$app->get('/api/v0/json_test/:name', function ($name) {    
    $data = array(
        'name' => $name,
        'numbers' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9)
    );
    echo json_encode($data);
});

$app->run();
