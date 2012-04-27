<?php

//===========================================================================//
// Routes table
//===========================================================================//

$app->get('/partials/tasktable',       'routes_get_partial_tasktable');

//===========================================================================//
// Routing functions
//===========================================================================//

function routes_get_partial_tasktable()
{
    global $app;
    
    $id = naan_current_user_id();
    $rows = naan_db_get_rows("tasks", array(
        'user_id' => $id
    ));
 
    return $app->render('tasktable.html', array(
        'tasks' => $rows,
    ));
}

