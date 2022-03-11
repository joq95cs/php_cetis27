<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_encargado = 18;

//RECEPCÍON DE VARIABLES
$received_encargado = $_POST['encargado'];

if(!$received_encargado) {

    die(json_encode('error'));
}

$received_espacio = Functions::cleanString($received_encargado);

class CheckSpace {

    private $connection;
    private $encargado;

    public function __construct($encargado) {

        $this->encargado = $encargado;

        $this->connection = Functions::getConnection();
    }

    public function check() {

        $sql = 'SELECT * FROM espacios WHERE encargado = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $this->encargado);
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows == 1) {

            $data = $result->fetch_assoc();
            
            return array(
                $data['id_espacio'],
                $data['nombre'],
                $data['encargado'],
                $data['tipo'],
            );
        }
        
        return false;
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$checkSpace = new CheckSpace($received_encargado);
$space = $checkSpace->check();

if($space) {

    echo json_encode($space);
 
} else { 

    echo json_encode('error');
}