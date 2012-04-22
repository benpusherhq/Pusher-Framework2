<?php

$app->get	('/api/internal/createdatabase',			'routes_get_api_internal_createdatabase');


function routes_get_api_internal_createdatabase()
{
	try
	{
		app_create_database();
		echo "Ok";
		error_log('TEST 123');
		
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
