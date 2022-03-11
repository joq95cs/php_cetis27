<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_espacio = 4;

//RECEPCÍON DE VARIABLES
$received_espacio = $_POST['espacio'];

if(!$received_espacio) {

    die(json_encode('error'));
}

$received_espacio = Functions::cleanString($received_espacio);

class GetPendingCategories {

    private $connection;
    private $espacio;

    public function __construct($espacio) {

        $this->espacio = $espacio;

        $this->connection = Functions::getConnection();
    }

    public function get() {

        $sql = 'SELECT * FROM categorias WHERE espacio = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $this->espacio);
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
$getPendingCategories = new GetPendingCategories($received_espacio);
$reports = $getPendingCategories->get();

if($reports) {

    $i = 0;
    echo '[';

    while($report = $reports->fetch_assoc()) {

        if($i != 0) {

            echo ', ';
        }

        echo json_encode($report);
        $i++;
    }

    echo ']';
 
} else { 

    echo json_encode('error');
}