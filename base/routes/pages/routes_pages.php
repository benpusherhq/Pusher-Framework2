<?php

//===========================================================================//
// Routes table
//===========================================================================//

$app->get('/',                              'routes_get_landing');
$app->get('/home',                          'routes_get_home');
$app->get('/tools/tasks',                   'routes_get_tools_tasks');
$app->get('/documentation/:page_name',      'routes_get_documentation');

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

function routes_get_tools_tasks()
{
    global $app;
    return $app->render('tasks.html'); 
}

/*
    This creates a generic page servering directory.  This avoids needing to
    create a simple page render function for every single page in the
    directory, but still maintains an overall strategy of requiring explicit
    route names for every page on the site.
 */
function routes_get_documentation($page_name)
{
    global $app;
    return $app->render("documentation/$page_name.html");
}

