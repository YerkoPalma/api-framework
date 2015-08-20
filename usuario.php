<?php
require_once "lib/api.php";
require_once "lib/api_db.php";

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
        return $model->select();
    }

    /*
        Crea un mensaje
    */
    protected function create(){

        //verificar que el metodo sea post
        if ( $this->method == "POST" ) {
            $model = $this->db->getModel("usuario");

            if ( isset($_POST) && isset($_POST["username"]) && isset($_POST["mail"]) ) {
                $id = $model->insert(Array( "username" => $_POST["username"], "mail" => $_POST["mail"]));
                //return $_POST;
                return Array("id" => $id);
            }
        }
        //error
        return Array("error" => "method not allowed");

    }

    /*
        Muestra un mensaje
    */
    protected function read($id){

        $model = $this->db->getModel("usuario");

        return $model->select("id", "= " . $id[0]);
    }

    /*
        Muestra un mensaje
    */
    protected function update($id){

        if ($this->method == "PUT"){
            $model = $this->db->getModel("usuario");
            return $model->update($id[0], Array("username" => "Toribio"));
        }

        return Array("error" => "method not allowed");
    }


    /*
        Borra un mensaje
    */
    protected function delete($id){
        return "delete";
    }
}
?>
