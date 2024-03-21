<?php
// backend/db.class.php
/*
Copyright © 2024 NA7KR Kevin Roberts. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
class DB {
    private $pdo; // PDO object for database connections
    private $loggingEnabled; // Boolean flag to enable or disable logging

    // Constructor to initialize the database connection
    public function __construct() {
        try {
            // Establish the database connection using PDO
            $this->pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->loggingEnabled = DB_LOGGING; // Set logging status based on global configuration
        } catch (PDOException $e) {
            // Handle connection errors by terminating the script and showing an error message
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Method to dynamically enable or disable logging
    public function setLogging($flag) {
        $this->loggingEnabled = $flag;
    }

    // Method for executing SQL statements like insert, update, and delete
   
    public function executeSQL($sql, $params, $action = 'custom', $tablename = 'unknown') {
            try {
                $db = $this->pdo->prepare($sql); // Prepare the SQL statement
                // Log SQL query and parameters
                //error_log("SQL Query: " . $sql);
                //error_log("Parameters: " . print_r($params, true));

                $db->execute($params); // Execute the statement with bound parameters
                $affectedRows = $db->rowCount(); // Get the number of affected rows
        
                if ($this->loggingEnabled) {
                    // Log the action if logging is enabled
                    $record_id = isset($params['id']) ? $params['id'] : 'N/A';
                    $details = json_encode(['query' => $sql, 'params' => $params]);
                    $this->logAction($action, $tablename, $record_id, $details);
                }
             
        
                return $affectedRows; // Return the count of affected rows
            } catch (PDOException $e) {
                throw new Exception("Error executing SQL: " . $e->getMessage());
            }
        }



    // Private method for logging database actions
    private function logAction($action, $tablename, $record_id, $details = '') {
        if ($this->loggingEnabled) {
            try {
                // Convert non-integer record_id values to string
                if (!is_int($record_id)) {
                    $record_id = substr((string) $record_id, 0, 255); // Truncate to fit the column size
                }
                
                $sql = "INSERT INTO db_logs (action, tablename, record_id, details) 
                        VALUES (:action, :tablename, :record_id, :details)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':action' => $action,
                    ':tablename' => $tablename,
                    ':record_id' => $record_id,
                    ':details' => $details
                ]);
            } catch (PDOException $e) {
                error_log("Failed to log database action: " . $e->getMessage());
            }
        }
    }

    public function select($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            foreach ($params as $key => $value) {
                // Bind parameters with colon prefix
                $stmt->bindValue($key, $value);
            }
            // Log SQL query and parameters
            //error_log("SQL Query: " . $sql);
            //error_log("Parameters: " . print_r($params, true));
    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // It's a good practice to log errors for further analysis
            error_log("DB Error in select: " . $e->getMessage());
            throw new Exception("Error in select: " . $e->getMessage());
        }
    }


    // Method for safely escaping values
    public function safe($value) {
        // Implement your safe value handling logic here
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // Method for executing count queries with parameter binding
    public function executeCount($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Error in count: " . $e->getMessage());
        }
    }

}

