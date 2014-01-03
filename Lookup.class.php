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
require_once( 'Db.class.php');
class Lookup   
{
    /*
    # Your Table name 
    protected $table = 'users';
    # Primary Key of the Table
    protected $pk	 = 'id';
    */



    private $db;
    
    
    public $variables;
    public function __construct($data = array()) 
    {   
        $this->db =  new DB();	
        $this->variables  = $data;
    }
    /*
    public function __set($name,$value){
            if(strtolower($name) === $this->pk)
                {$this->variables[$this->pk] = $value;}
            else 
               {$this->variables[$name] = $value;}
    }
  
    public function __get($name)
    {	
            if(is_array($this->variables)) {
                    if(array_key_exists($name,$this->variables)) 
                    { return $this->variables[$name]; }
            }

            $trace = debug_backtrace();
            trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
            return null;
    }
    */
  
    public function count($field,$table) 
    {
        if($field)
        { 
            return $this->db->single("SELECT COUNT(" . $field . ")" . " FROM " . $table); 
            
        }
    }		
}
  /*
    public function save($id = "0") 
    {
        $this->variables[$this->pk] = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

        $fieldsvals = '';
        $columns = array_keys($this->variables);

        foreach($columns as $column)
        {
                if($column !== $this->pk)
                { $fieldsvals .= $column . " = :". $column . ","; }
        }

        $fieldsvals = substr_replace($fieldsvals , '', -1);

        if(count($columns) > 1 ) {
                $sql = "UPDATE " . $this->table .  " SET " . $fieldsvals . " WHERE " . $this->pk . "= :" . $this->pk;
                return $this->db->query($sql,$this->variables);
        }
    }
    */
    /*
    public function create() 
    { 
            $bindings   	= $this->variables;

            if(!empty($bindings)) {
                    $fields     =  array_keys($bindings);
                    $fieldsvals =  array(implode(",",$fields),":" . implode(",:",$fields));
                    $sql 		= "INSERT INTO ".$this->table." (".$fieldsvals[0].") VALUES (".$fieldsvals[1].")";
            }
            else 
                { $sql 		= "INSERT INTO ".$this->table." () VALUES ()"; }

            return $this->db->query($sql,$bindings);
    }
    */
    /*
    public function delete($id = "")         
    {
            $id = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

            if(!empty($id)) {
                    $sql = "DELETE FROM " . $this->table . " WHERE " . $this->pk . "= :" . $this->pk. " LIMIT 1" ;
                    return $this->db->query($sql,array($this->pk=>$id));
            }
    }
    */
    /*
    public function find($id = "") 
    {
            $id = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

            if(!empty($id)) {
                    $sql = "SELECT * FROM " . $this->table ." WHERE " . $this->pk . "= :" . $this->pk . " LIMIT 1";	
                    $this->variables = $this->db->row($sql,array($this->pk=>$id));
            }
    }
    */
    /*
    public function all()
    {return $this->db->query("SELECT * FROM " . $this->table);}
    */
    /*
    public function min($field)          
    {
        if($field)
        {
            return $this->db->single("SELECT MIN(" . $field . ")" . " FROM " . $this->table); 
            
        }
    } 
    */
    /*
    public function max($field)  
    {
        if($field)
        { 
            return $this->db->single("SELECT MAX(" . $field . ")" . " FROM " . $this->table);
            
        }
    }
    */
    /*
    public function avg($field)  
    {
        if($field)
        { 
            return $this->db->single("SELECT avg(" . $field . ")" . " FROM " . $this->table); 
            
        }
    }
    */

    /*
     public function sum($field)  
    {
        if($field)
        { return $this->db->single("SELECT SUM(" . $field . ")" . " FROM " . $this->table);}
    }
     */

?>

