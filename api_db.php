<?php
require_once "db.php";

class model
{
    public $name;

    public function __construct($name){
        $this->name = $name;
    }
}

class database
{
    private $db;

    public function __construct(){

        try{
            $db = new PDO('pgsql:dbname=api_mensajes;host=127.0.0.1',
                Config::user,
                Config::pass);
            print_r($db);
            $this->db = $db;
        } catch (PDOException $e) {
            echo  $e->getMessage(); 
        }
    }

    public function getModel($tableName){
        if(isset($this->db)){
            $query = "select schemaname, tablename, tableowner, tablespace, hasindexes, hasrules, hastriggers from pg_tables where schemaname='public' and tablename='".$tableName."';";
            $row = $this->db->query($query);
            if(isset($row) && is_array($row) && count($row) > 0){
                return new model($tableName);            
            }else{
                return "nothing!";            
            } 
        }
    }
}
?>
