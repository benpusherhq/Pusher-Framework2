<?php


//-----------------------------------------------------------------------------------//
// _validate_array
//-----------------------------------------------------------------------------------//

/*
    Takes in an array "data" that presumably has come externally from an API call 
    (i.e. needs some degree of validation).  Throws an exception if any required
    data is not part of the array and fills in any optional data with default values.
    
    Compares the keys in "data" versus the array of key names in "required".  If data
    is missing any of those keys, an Exception is thrown.
    
    Then compares all the keys in "data" to the "defaults" associative array; if data
    is missing any of the keys, the default is put into the array.
    
    Extraneous keys in neither "required" or "defaults" are discarded from the final
    array.
    
    Returns a array composed solely of the required and default key names, with each
    value set either to the original value or the default value.
    
    Note: this does not validation that the value itself is meaningful, well-formed,
    or correct.
 */
function naan_validate_array($data, $required, $defaults)
{
    $result = array();
    
    foreach ($required as $index => $key)
    {
        if (!array_key_exists($key, $data))
            throw new Exception("Required parameter does not exist '$key'");
        else
            $result[$key] = $data[$key];
    }
    foreach ($defaults as $key => $value)
    {
        if (!array_key_exists($key, $data))
            $result[$key] = $value;
        else
            $result[$key] = $data[$key];
    }
        
    return (object)$result;
}

function naan_recognized_keys ($data, $recognized)
{
    $result = array();
    foreach ($recognized as $index => $key)
    {
        if (array_key_exists($key, $data))
            $result[$key] = $data[$key];
    }
    return $result;
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

function naan_db_get_rows ($tableName, $row)
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
        
        $obj = $stmt->fetchAll();
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


