<?php
require_once "db.php";

class model
{
    public $name;
    public $db;

    public function __construct($name, $db){
        $this->name = $name;
        $this->db = $db;
    }

    public function select($name = "", $condition = "", $columns = Array()){
        
        $query = "";
        //select * from $this->name
        if ($name == "" && $condition == "" && is_array($columns) && count($columns) == 0){
            $query = "SELECT * FROM " . $this->name;
        }

        //select $columns from $this->name
        if ($name == "" && $condition == "" && is_array($columns) && count($columns) > 0){
            $query = "SELECT ". implode(", ", $columns) . " FROM " . $this->name;
        }

        //select * from $this->name where $name $condition
        if ($name != "" && $condition != "" && is_array($columns) && count($columns) == 0){
            $query = "SELECT * FROM " . $this->name . " WHERE " . $name . " " . $condition;
        }

        //select $columns from $this->name where $name $condition 
        if ($name != "" && $condition != "" && is_array($columns) && count($columns) > 0){
            $query = "SELECT ". implode(", ", $columns) . " FROM " . $this->name . " WHERE " . $name . " " . $condition;
        }
        
        $query = $query . ";";
        $stmt = $this->db->query($query);
        $result = Array();

        //echo $query . "\n\n";

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($result, $row);
            #echo $row['id'].", ".$row['api_id'].", ".$row['contacto_id'].", ".$row['texto'];
        }
        return $result;
    }

    function prepare_string($obj){
        return is_numeric($obj) ? $obj : "'" . $obj . "'";
    }

    public function insert($arr = Array()){

        //es asociativo
        $is_asociative = array_keys($arr) !== range(0, count($arr) - 1);

        if (!$is_asociative) {
            $query = "INSERT INTO " . $this->name . " VALUES (" . implode(", ", array_map(function($s) {return $this->prepare_string($s);}, $arr)) . ")" ;
        }else{
            $query = "INSERT INTO " . $this->name . " ( " . implode(", ", array_keys($arr)) . ") VALUES (". implode(", ", array_map(function($s) {return $this->prepare_string($s);}, $arr)) . ")";	
        }
        
        $query = $query . ";";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $this->db->lastInsertId("usuario_id_seq");

    }

    public function update($id, $data = Array()){

        //$this->method == "PUT" &&
        if( is_array($data) && count($data) > 0 ){
            $query = "UPDATE " . $this->name . " SET ";
            $i = 0;           

            foreach ( $data as $column => $value ){
                
                $i++;
                $tmp = (is_numeric($value) ? $value : "'" . $value . "'");
                $query .= $column . " = " . $tmp;
                $query .= ($i == count($data) ? "" : ", ");
            }            

            //echo "query " . $query . "\n\n";

            $query .= " WHERE id = " . $id . ";";

            $stmt = $this->db->prepare($query);

            $stmt->execute();

            return $this->select("id", " = " . $id);
            //echo $query;
        }
    }
}

class api_database
{
    public $db;

    public function __construct(){

        try{
            $db = new PDO('pgsql:dbname=api_mensajes;host=127.0.0.1',
                Config::user,
                Config::pass);            
            $this->db = $db;
            //print_r($db);
        } catch (PDOException $e) {
            echo  $e->getMessage(); 
        }
    }

    public function getModel($tableName){
        if(isset($this->db)){
            $query = "select schemaname, tablename, tableowner, tablespace, hasindexes, hasrules, hastriggers from pg_tables where schemaname='public' and tablename='".$tableName."';";
            $stmt = $this->db->query($query);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(isset($row) && is_array($row) && count($row) > 0){
                return new model($tableName, $this->db);            
            }else{
                echo $tableName . " not found";
                return "";            
            } 
        }
    }
}
?>
