<?php
require_once "api.php";

class usuarios extends api
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
        
        return "Hello World Users!!";
    }
    
    /*
        Crea un mensaje
    */
    protected function create(){
        if ($this->method == "POST"){
            return $_POST;        
        }else{
        return "method not allowed";        
        }

    }
    
    /*
        Muestra un mensaje
    */
    protected function read($id){
        print_r($id);
        return "read";
    }
    
    /*
        Borra un mensaje
    */
    protected function delete($id){
        return "delete";
    }
}
?>
