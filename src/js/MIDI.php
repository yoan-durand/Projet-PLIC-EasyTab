<?php

    header("application/json; charset=utf-8");
    function __autoload($class_name)
    {
        include $class_name . '.php';
    }
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    $encoded = $_POST["encoded"];
    
    $decoded = json_decode($encoded, true);
    
    $partition = $decoded["encoded"];

    $json = json_encode($partition); // On renvoie le tout sous format JSON

    echo $json;

?>
