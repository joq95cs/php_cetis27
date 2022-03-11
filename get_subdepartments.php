<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_id = 4;

//RECEPCÍON DE VARIABLES
$received_id = $_POST['departamento'];

if(!$received_id) {

    die(json_encode('error'));
}

$received_id = Functions::cleanString($received_id);

class GetSubdepartments {

    private $connection;
    private $id;

    public function __construct($id) {

        $this->id = $id;

        $this->connection = Functions::getConnection();
    }

    public function get() {

        $sql = 'SELECT * FROM subdepartamentos WHERE departamento = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $this->id);
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
$getSubdepartments = new GetSubdepartments($received_id);
$subdepartments = $getSubdepartments->get();

if($subdepartments) {

    $i = 0;
    echo '[';

    while($subdepartment = $subdepartments->fetch_assoc()) {

        if($i != 0) {

            echo ', ';
        }

        echo json_encode($subdepartment);
        $i++;
    }

    echo ']';
 
} else { 

    echo json_encode('error');
}