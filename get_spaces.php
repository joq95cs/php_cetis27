<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

class GetSpaces {

    private $connection;

    public function __construct() {

        $this->connection = Functions::getConnection();
    }

    public function get() {

        $sql = 'SELECT * FROM espacios';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows >= 1) {

            return $result;

        } else {

            return false;
        }
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$getSpaces = new GetSpaces();
$spaces = $getSpaces->get();

if($spaces) {

    $i = 0;
    echo '[';

    while($space = $spaces->fetch_assoc()) {

        if($i != 0) {

            echo ', ';
        }

        echo json_encode($space);
        $i++;
    }

    echo ']';
 
} else { 

    echo json_encode('error');
}