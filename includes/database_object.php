<?php
require_once(LIB_PATH.DS."database.php");

class DatabaseObject {
    
    public static function find_all() {
        return static::find_by_sql("select * from ".static::$table_name);
    }
    
    public static function find_by_id($id=0) {
        global $database;
        $result_array = static::find_by_sql("select * from ".static::$table_name." where id ={$id} limit 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function find_by_sql($sql="") {
        global $database;
        $result_set = $database->query($sql);
        $object_array = array();
        while ($row = $database->fetch_array($result_set)) {
            $object_array[] = static::instantiate($row);
        }
        return $object_array;
    }
    
    public static function count_all() {
        global $database;
        $sql = "SELECT COUNT(*) FROM " .static::$table_name;
        $result_set = $database->query($sql);
        $row = $database->fetch_array($result_set);
        return array_shift($row);
    }
    
    private static function instantiate($record) {
        $object = new static;
//        $object = new static();
//        $object->id = $record['id'];
//        $object->username = $record['username'];
//        $object->password = $record['password'];
//        $object->first_name = $record['first_name'];
//        $object->last_name = $record['last_name'];
        
        foreach($record as $attribute=>$value) {
            if($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }
    
    private function has_attribute($attribute) {
        $object_vars = $this->attributes();
        return array_key_exists($attribute, $object_vars);
    }
}