<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

class GetCategories {

    private $connection;

    public function __construct() {

        $this->connection = Functions::getConnection();
    }

    public function get() {

        $sql = 'SELECT * FROM categorias';
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
$getCategories = new GetCategories();
$categories = $getCategories->get();

if($categories) {

    $i = 0;
    echo '[';

    while($categorie = $categories->fetch_assoc()) {

        if($i != 0) {

            echo ', ';
        }

        echo json_encode($categorie);
        $i++;
    }

    echo ']';
 
} else { 

    echo json_encode('error');
}