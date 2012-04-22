<?php

function app_create_database()
{
	$db = naan_db_connect();
	
	naan_db_create_table($db, "todo_list", "id", array(
		'id'		=>	'INT AUTO_INCREMENT',
		'user_id'	=> 	'INT',
		'title'		=>  'TEXT'
	));
	
	naan_db_create_table($db, "users", "id", array(
		'id'			=>	'INT AUTO_INCREMENT',
		'first_name'	=>	'TEXT',
		'last_name'		=>	'TEXT',
	));
	
	$db = null;
}

function app_get_current_user()
{
	$user_id = naan_current_user_id();
	return naan_db_get_row("users", array('id' => $user_id));
}

