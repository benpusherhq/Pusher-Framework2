<?php

class Naan
{
	protected $functions = array();
	
	public function __get($name) {
        return $this->$functions[$name];
    }
	
	public function __call($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling object method '$name' "
             . implode(', ', $arguments). "\n";
    }
    
    public function register ($name, $method_name) {
		$this->functions[$name] = $enum;
    }
}

//===========================================================================//
// Database
//===========================================================================//

function naan_db_connect() 
{
	$dbhost ="localhost";
	$dbuser ="naan_user";
	$dbpass ="naan_pass";
	$dbname ="naan_db";
	
	$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	return $db;
}

function naan_db_create_table ($db, $table, $primary_key, $fields)
{
	//
	// Create the table
	// (Silently fail if it already exists)
	//
	try {
		$sql = "CREATE TABLE IF NOT EXISTS $table (";
		foreach ($fields as $key => $value)
			$sql .= "$key $value, ";
		$sql .= "PRIMARY KEY($primary_key) )";        
		$db->exec($sql);
	} catch (PDOException $e) {

	}
}

function naan_db_get_row ($tableName, $row)
{
	$sqlWhere = "WHERE ";
	$index = 0;
	$count = count((array)$row);
	foreach ($row as $key => $value)
	{
		$sqlWhere .= "$key=:$key";
		
		if (++$index < $count)
			$sqlWhere .= " AND ";
	}
	
	try 
	{
		$db = naan_db_connect();
		
		$stmt = $db->prepare("SELECT * FROM $tableName $sqlWhere");
		foreach ($row as $key => $value)
			$stmt->bindValue(":$key", $value);
		$stmt->execute();
		
		$obj = $stmt->fetchObject();
		$db = null;
		return $obj;
	}
	catch (PDOException $e) {
		throw $e;
	}
}
    
//
//
function naan_db_add_row ($tableName, $row)
{
	$sqlNames = "";
	$sqlValues = "";
	$index = 0;
	$count = count( (array)$row);
	foreach ($row as $key => $value)
	{
		$sqlNames .= "$key";
		$sqlValues .= ":$key";
		
		if (++$index < $count) {
			$sqlNames .= ", ";
			$sqlValues .= ", ";
		}
	}
	
	try 
	{
		$db = naan_db_connect();
		
		$stmt = $db->prepare("INSERT INTO $tableName ($sqlNames) VALUES ($sqlValues)");           
		foreach ($row as $key => $value)
			$stmt->bindValue(":$key", $value);
		$stmt->execute();
		
		$id = $db->lastInsertId();
		$db = null;
		return $id;
	}
	catch (PDOException $e) {
		throw $e;
	}
}

function naan_db_update_row ($tableName, $key, $values)
{    
	$sqlValues = "";
	$index = 0;
	$count = count((array)$values);
	foreach ($values as $name => $value)
	{
		$sqlValues .= "$name=:$name";
		if (++$index < $count)
			$sqlValues .= ", ";
	}
	
	$sqlWhere = "";
	$index = 0;
	$count = count($key);
	foreach ($key as $name => $value)
	{
		$sqlWhere .= "$name=:$name";
		if (++$index < $count)
			$sqlWhere .= " AND ";            
	}
	
	try 
	{
		$db = naan_db_connect();
		
		$stmt = $db->prepare("UPDATE $tableName SET $sqlValues WHERE $sqlWhere");
		foreach ($values as $name => $value)
			$stmt->bindValue(":$name", $value);
		foreach ($key  as $name => $value)
			$stmt->bindValue(":$name", $value);                
		$stmt->execute();
		
		$id = $db->lastInsertId();
		$db = null;
		return $id;
	}
	catch (PDOException $e) {
		throw $e;
	}
}

function naan_db_delete_row ($tableName, $row)
{
	$sqlCondition = "";
	$index = 0;
	$count = count((array)$row);
	foreach ($row as $key => $value)
	{
		$sqlCondition .= "$key = :$key";
		if (++$index < $count)
			$sqlCondition .= " AND ";
	}
	
	try 
	{
		$db = naan_db_connect();
		
		$stmt = $db->prepare("DELETE FROM $tableName WHERE $sqlCondition");
		foreach ($row as $key => $value)
			$stmt->bindValue(":$key", $value);
		$stmt->execute();
		
		$db = null;
	}
	catch (PDOException $e) {
		throw $e;
	}	
}


//===========================================================================//
// User Session
//===========================================================================//

function naan_current_user_id () 
{
	if (isset($_SESSION['naan_user_id']))
		return $_SESSION['naan_user_id'];
	else
		return null;
}

function naan_set_current_user ($user_id) 
{
	$_SESSION['naan_user_id'] = $user_id;
}


