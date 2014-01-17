<?php
/* * *************************************************************************
 * 			NA7KR Log Program 
 * ************************************************************************* */

/* * *************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * ************************************************************************* */
require_once( 'db.class.php');
class Lookup {
    private $db;
    public $variables;
    public function __construct($data = array()) {
        $this->db = new DB();
        $this->variables = $data;
    }
    public function count($field, $table) {
        if ($field) {
            return $this->db->single("SELECT COUNT(" . $field . ")" . " FROM " . $table);
        }
    }
}
?>