<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_id = 30;

//RECEPCÍON DE VARIABLES
$received_id = $_POST['id'];

if(!$received_id) {
   
    die(json_encode('error'));
}

$received_id = Functions::cleanString($received_id);

//DECLARACIÓN DE CLASE PARA EL NUEVO REPORTE
class DeleteReport {

    private $connection;
    private $id;

    public function __construct($id) { 

        $this->id = $id;
        
        $this->connection = Functions::getConnection(); 
    }

    public function delete() { 

        $sql = 'DELETE FROM reportes WHERE id_reporte = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $this->id);
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return 'success';

        } else {

            return false;
        }
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$deleteReport = new DeleteReport($received_id);
$result = $deleteReport->delete();

if($result) { 
    
    echo json_encode($result);

} else { 

    echo json_encode('error');
}