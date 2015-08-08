<?php
    
    require_once "mensajes.php";
    require_once "usuarios.php";

    $dbname = "api_mensajes";
    $host = "localhost";
    $user = "postgres";
    $pass = "postgres";
    $port = 5432;
    $dbh;

    try{
        $db = new PDO('pgsql:dbname=api_mensajes;host=127.0.0.1',
            $user,
            $pass);
    } catch (PDOException $e) {
        echo  $e->getMessage(); 
    }

    //proceso la uri
    $uri = $_SERVER['REQUEST_URI'];
    $uriArgs = explode('/', ltrim($uri,'/'));

    $uriClass = $uriArgs[1];
    
    if ($uriClass == "") {
        echo "not found";    
    }else{
        $apiObj = new $uriClass();
        //$msg = new mensajes($db);

        echo $apiObj->processAPI();

    }

?>
