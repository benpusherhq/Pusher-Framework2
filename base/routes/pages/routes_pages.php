<?php

//===========================================================================//
// Routes table
//===========================================================================//

$app->get('/',                  'routes_get_landing');
$app->get('/home',              'routes_get_home');
$app->get('/architecture',      'routes_get_architecture');
$app->get('/tools/tasks',       'routes_get_tools_tasks');

//===========================================================================//
// Routing functions
//===========================================================================//

function routes_get_landing()
{
    global $app;
    return $app->render('landing.html');
}

function routes_get_home()
{
    global $app;
    $data = array(
        'userDataString'    => print_r(app_get_current_user(), true)
    );
    return $app->render('home.html', $data);
}

function routes_get_architecture()
{
    global $app;
    return $app->render('architecture.html'); 
}

function routes_get_tools_tasks()
{
    global $app;
    return $app->render('tasks.html'); 
}

