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

// use standard php sessions
if(!isset($_SESSION)) 
{ 
    session_cache_limiter(false);
    session_start();
}

$app = new Slim(array(
    'debug' => true,
    'log.enabled' => true,
    'log.level' => 4,
    'templates.path' => '../templates',
    'view' => new TwigView()
));

//
//
//
require_once "../lib/Naan/Naan.php";
require_once "../lib/Naan/App.php";

require_once "../config/config-app.php";

//
// Useful variables available to all view templates
//

$app->view()->setData('app',        $applicationConfig);

$app->view()->setData('hostUri', $app->request()->getScheme() . "://" . $app->request()->getHost() . $app->request()->getRootUri() );
$app->view()->setData('rootUri', $app->request()->getRootUri() );

$app->view()->setData('userId',     naan_current_user_id() );
$app->view()->setData('user',       app_get_current_user() );

require_once "../routes/pages/routes_pages.php";

require_once "../routes/api/routes_api.php";
require_once "../routes/api/internal/routes_api_internal.php";


//===========================================================================//
// Routes table
//===========================================================================//



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
