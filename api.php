<?php 
abstract class api
{
    protected $uri;
    
    protected $server;

    protected $prefix;
    
    protected $method;
    
    protected $name;
     
    protected $endpoint;
    
    protected $args;
    
    protected $version;
    
    protected $data;
    
    public function __construct(){
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        
        $this->uri = $_SERVER['REQUEST_URI'];
        
        $this->server = $_SERVER['HTTP_HOST'];
        
        $this->args = explode('/', ltrim($this->uri,'/'));
        
        $this->prefix = array_shift($this->args);

        //El name debe ser seteado por el nombre de clase
        $this->name = array_shift($this->args);
        
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->endpoint = array_shift($this->args);
        }
        
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
        if ($this->method == 'POST' || $this->method == 'PUT' || $this->method == 'DELETE') {
            $this->data = $_POST;
        }            
    }
    
    public function processAPI() {       
        
        //index
        if (trim($this->uri,'/') == $this->prefix."/".$this->name ) {
            return $this->_response($this->{'index'}($this->args));
        }
        
        //read
        if ($this->endpoint == "" && is_array($this->args) && count($this->args) == 1 && is_numeric($this->args[0]) && method_exists($this, 'read')){            
            return $this->_response($this->{'read'}($this->args));
        }

        //create

        //update

        //delete

        //else
        if (method_exists($this, $this->endpoint)) {
            return $this->_response($this->{$this->endpoint}($this->args));
        }

        //error
        return $this->_response("No Endpoint: $this->endpoint", 404);
    }
    
    private function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data);
    }
    
    private function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
    
}

?>
