<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

class GetDepartments {

    private $connection;

    public function __construct() {

        $this->connection = Functions::getConnection();
    }

    public function get() {

        $sql = 'SELECT * FROM departamentos';
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
$getDepartments = new GetDepartments();
$departments = $getDepartments->get();

if($departments) {

    $i = 0;
    echo '[';

    while($department = $departments->fetch_assoc()) {

        if($i != 0) {

            echo ', ';
        }

        echo json_encode($department);
        $i++;
    }

    echo ']';
 
} else { 

    echo json_encode('error');
}