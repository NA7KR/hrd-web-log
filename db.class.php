<?php
/***************************************************************************
*			NA7KR Log Program 
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
require("log.class.php");
class DB
{
	private $pdo;	# @object, The PDO object
	private $sQuery;	# @object, PDO statement object
	private $settings;	# @array,  The database settings
	private $bConnected = false;	# @bool ,  Connected to the database	
	private $log;	# @object, Object for logging exceptions
	private $parameters;	# @array, The parameters of the SQL query
		
	public function __construct()	#Default Constructor 
	{ 			
		$this->log = new Log();	# Instantiate Log class.
		$this->Connect();	# Connect to database.
		$this->parameters = array();	# Creates the parameter array. 
	}
	
	private function Connect() # This method makes connection to the database.
	{
		include_once (__DIR__ . '/../config.php');
		$dsn = "mysql:host={$dbhost};dbname={$dbnameWEB}"; # Puts content into the settings array.
		try # Tries to connect to the database.
		{
			$this->pdo = new PDO($dsn,$dbuname, $dbpass , array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # We can now log any exceptions on Fatal error. 
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); # Disable emulation of prepared statements, use REAL prepared statements instead.
			$this->bConnected = true; # Connection succeeded, set the boolean to true.
		}
		catch (PDOException $e) #If connection failed, exception is displayed and a log file gets created.
		{
			echo $this->ExceptionLog($e->getMessage()); # Write into log
			die();
		}
	}
  
	private function Init($query,$parameters = "") # Every method which needs to execute a SQL query uses this method.
	{
		# Connect to database
		if(!$this->bConnected) { $this->Connect(); } # If not connected, connect to the database.
		try 
		{
			$this->sQuery = $this->pdo->prepare($query); # Prepare query
			$this->bindMore($parameters); # Add parameters to the parameter array	
			
			if(!empty($this->parameters))  # Bind parameters
			{
				foreach($this->parameters as $param)
				{
					$parameters = explode("\x7F",$param);
					$this->sQuery->bindParam($parameters[0],$parameters[1]);
				}		
			}
			$this->succes 	= $this->sQuery->execute();	# Execute SQL 
		}
		catch(PDOException $e)
		{
			echo $this->ExceptionLog($e->getMessage(), $query ); # Write into log and display Exception
			die();
		}
		$this->parameters = array(); # Reset the parameters
	}
		
	public function bind($para, $value) # Add the parameter to the parameter array @param string $para @param string $value 
	{	
		$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . $value;
	}
		
	public function bindMore($parray) # Add more parameters to the parameter array	@param array $parray
	{
		if(empty($this->parameters) && is_array($parray)) 
		{
			$columns = array_keys($parray);
			foreach($columns as $i => &$column)	
			{
				$this->bind($column, $parray[$column]);
			}
		}
	}
	
	#   If the SQL query  contains a SELECT statement it returns an array containing all of the result set row
	#	If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
	#  	@param  string $query	@param  array  $params	@param  int    $fetchmode	@return mixed *			
	public function query($query,$params = null,$fetchmode = PDO::FETCH_ASSOC)
	{
		$query = trim($query);
		$this->Init($query,$params);
		if (stripos($query, 'select') === 0)
		{
			return $this->sQuery->fetchAll($fetchmode);
		}
		elseif (stripos($query, 'insert') === 0 ||  stripos($query, 'update') === 0 || stripos($query, 'delete') === 0) 
		{
			return $this->sQuery->rowCount();	
		}	
		else 
		{
			return NULL;
		}
	}
		
	public function lastInsertId() # Returns the last inserted id.  @return string
	{
		return $this->pdo->lastInsertId();
	}	
		
	# Returns an array which represents a column from the result set @param  string $query	@param  array  $params @return array	
	public function column($query,$params = null)
	{
		$this->Init($query,$params);
		$Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);		
		$column = null;
		foreach($Columns as $cells) 
		{
			$column[] = $cells[0];
		}
		return $column;
		
	}	

	#	Returns an array which represents a row from the result set 
	#	@param  string $query	@param  array  $params	@param  int    $fetchmode	@return array
	public function row($query,$params = null,$fetchmode = PDO::FETCH_ASSOC)
	{				
		$this->Init($query,$params);
		return $this->sQuery->fetch($fetchmode);			
	}
	
	#	Returns the value of one single field/column
	#	@param  string $query	@param  array  $params	@return string	
	public function single($query,$params = null)
	{
		$this->Init($query,$params);
		return $this->sQuery->fetchColumn();
	}
     
	# Writes the log and returns the exception @param  string $message @param  string $sql
	private function ExceptionLog($message , $sql = "")
	{
		$exception  = 'Unhandled Exception. <br />';
		$exception .= $message;
		$exception .= "<br /> You can find the error back in the log.";
		if(!empty($sql)) 
		{
			$message .= "\r\nRaw SQL : "  . $sql; 
		}
		$this->log->write($message); # Write into log
		return $exception;
	}	
    //public function quote($value)
	//{
	//	return $pdo->quote($value) . "\n";
	//}
}
?>
