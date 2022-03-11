<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_id = 18;

//RECEPCÍON DE VARIABLES
$received_id = $_POST['id'];

if(!$received_id) {

    die(json_encode('error'));
}

$received_id = Functions::cleanString($received_id);

class GetSentReportsUser {

    private $connection;
    private $id;

    public function __construct($id) {

        $this->id = $id;

        $this->connection = Functions::getConnection();
    }

    public function get() {

        $sql = 'SELECT * FROM reportes WHERE usuario = ? LIMIT 25';
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
$getSentReportsUser = new GetSentReportsUser($received_id);
$reports = $getSentReportsUser->get();

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