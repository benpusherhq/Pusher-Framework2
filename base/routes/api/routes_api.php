<?php

//===========================================================================//
// Routes table
//===========================================================================//

$app->get	('/api/login/:user_id',			'routes_get_api_login');
$app->put	('/api/user',					'routes_put_api_user');

//===========================================================================//
// Routing functions
//===========================================================================//

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