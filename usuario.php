<?php
require_once "api.php";
require_once "api_db.php";

class usuario extends api
{

    protected $db;    

    public function __construct($db){
        parent::__construct();
        $this->db = $db;
    }
    
    /*
        Muestra todos los mensajes
    */
    protected function index(){
        $model = $this->db->getModel("usuario");
        return $model->select();;
    }
    
    /*
        Crea un mensaje
    */
    protected function create(){
        $model = $this->db->getModel("usuario");

        $model->insert(Array( "username" => "yerkooo", "mail" => "yerko.palma.serrano@gmail.com"));

    }
    
    /*
        Muestra un mensaje
    */
    protected function read($id){
        $model = $this->db->getModel("usuario"); 

        //$model->select();
        //echo "\n";

        //$model->select("", "", Array("username"));
        //echo "\n";

        return $model->select("id", "= " . $id[0]);
        //echo "\n";

        //return $model->select("username", "= 'YerkoPalma'", Array("username", "mail"));
        //

        //return $model;
    }
    
    /*
        Borra un mensaje
    */
    protected function delete($id){
        return "delete";
    }
}
?>
