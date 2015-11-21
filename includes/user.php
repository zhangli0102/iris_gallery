<?php
require_once(LIB_PATH.DS."database.php");

class User extends DatabaseObject {
    
    protected static $table_name = "users";
    protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');
    
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    
    public function full_name() {
        if(isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . " " . $this->last_name;
        } else {
            return "";
        }
    }
    
    public static function authenticate($username="",$password="") {
        global $database;
        $username = $database->escape_value($username);
        $password = $database->escape_value($password);
        
        $sql  ="SELECT * FROM users ";
        $sql .="WHERE username = '{$username}' ";
        $sql .="AND password = '{$password}' ";
        $sql .="LIMIT 1";
        $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    protected function attributes() {
        foreach(self::$db_fields as $field) {
            if(property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }
    
    protected function sanitized_attributes() {
        global $database;
        $clean_attributes = array();
        foreach($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $database->escape_value($value);
        }
        return $clean_attributes;
    }
    
    public function save() {
        return isset($this->id) ? $this->update() : $this->create();
    }
    
    public function create() {
        global $database;
        $attributes = $this->sanitized_attributes();
        
        $sql  = "insert into ".self::$table_name." (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") values (' ";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        if($database->query($sql)) {
            $this_id = $database->insert_id();
            return true;
        } else {
            return false;
        }
    }
    
    public function update() {
        global $database;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach($attributes as $key => $value) {
            $attribute_paris[] = "{$key}='{$value}'";
        }
        $sql  ="update ".self::$table_name." set ";
        $sql .=join(", ", $attribute_pairs);
        $sql .=" where id=". $database->escape_value($this->id);
        $database->query($sql);
        return($database->affected_rows()==1)?true:false;
    }
    
    public function delete() {
        global $database;
        $sql  ="delete from ".self::$table_name;
        $sql .=" where id=". $database->escape_value($this->id);
        $sql .=" limit 1";
        $database->query($sql);
        return($database->affected_rows()==1)?true:false;
    }
    
    
}

?>