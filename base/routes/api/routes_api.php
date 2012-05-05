<?php

//===========================================================================//
// Routes table
//===========================================================================//

$app->put	('/api/user',					'routes_put_api_user');
$app->post  ('/api/login2',                 'routes_post_api_login2');

$app->post  ('/api/db/post/:table',         'routes_post_api_db_post');


$app->get   ('/api/notecards',              'routes_get_api_notecards');
$app->post  ('/api/notecards',              'routes_post_api_notecards');

//===========================================================================//
// Routing functions
//===========================================================================//

function naan_json_success ($data = array())
{
    echo json_encode(
        array(
            'success' => 1,
            'data' => $data
        )
    );
}

function naan_json_error ($text, $data = array())
{
    echo json_encode(
        array(
            'error' => 1,
            'text' => $text,
            'data' => $data
        )
    );
}

/*
    Given a login name (i.e. e-mail address), if that user exists
    in the database, make them the current user.  Otherwise, create
    a new user with that id.
 */
function routes_post_api_login2()
{
    try
    {
        global $app;
        $data = (object)$app->request()->params('data');
        
        $user = naan_db_get_row("users", array('id' => $data->email));
        if (!$user)
        {
            naan_db_add_row("users", array(
                'id'         => $data->email,
                'first_name' => 'New',
                'last_name'  => 'User'
            ));
            $user = naan_db_get_row("users", array('id' => $data->email));
        }
        naan_set_current_user($user->id);
        
        return naan_json_success($user);
        
    } catch (Exception $e)
    {
        return naan_json_error("PHP Exception", array(
            'function' => __FUNCTION__,
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ));
    }
}

function routes_get_api_login($user_id)
{
    global $app;

    try
    {
        naan_set_current_user($user_id);
        
        $user = app_get_current_user();
        if (!$user)
        {
            naan_db_add_row("users", array(
                'id' => $user_id,
                'first_name' => 'New',
                'last_name' => 'User'
            ));
            $user = app_get_current_user();
        }
        
        echo json_encode(array(
            'success' => 1
        ));
        $app->redirect( $app->request()->getReferrer() );
        
    } catch (Exception $e)
    {
        error_log($e->getMessage());
        echo json_encode(array(
            'error' => array(
                'message' => $e->getMessage()
            )
        ));
    }
}


function routes_put_api_user()
{
    global $app;
    
    $user_id = naan_current_user_id();
    $data = $app->request()->params('data');
    
    naan_db_update_row("users", array('id' => $user_id), $data);
    
    echo json_encode(array(
        'success' => 1
    ));
}

function routes_post_api_db_post($table)
{
    try
    {
        global $app;
        $data = $app->request()->params('data');        
        naan_db_add_row($table, $data);
        return naan_json_success();
        
    } catch (Exception $e)
    {
        return naan_json_error("PHP Exception", array(
            'function' => __FUNCTION__,
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ));
    }
}

function routes_get_api_notecards()
{
    global $app;
    
    $user_id = naan_current_user_id();
    
    $rows = naan_db_get_rows('notecard_notes', array(
        'owner_id' => $user_id
    ));    
    if ($rows)
    {
        $results = array();
        foreach ($rows as $note)
        {
            $results[] = array(
                'id' => $note['id'],
                'content' => $note['content']
            );
        }
        
        return naan_json_success($results);
    }
    return naan_json_error();
}

function routes_post_api_notecards()
{
    global $app;
    
    $user_id = naan_current_user_id();
    $data = $app->request()->params('data');
    
    if ($data)
    {
        naan_db_add_row('notecard_notes', array(
            'owner_id' => $user_id,
            'content' => $data['content']
        ));
        return naan_json_success();
    }
    return naan_json_error();
}