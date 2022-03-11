<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_id = 29;
//$received_estatus = 'Atendido';

//RECEPCÍON DE VARIABLES
$received_id = $_POST['id'];
$received_estatus = $_POST['estatus'];

if(!$received_id or !$received_estatus) {
   
    die(json_encode('error')); 
}

$received_id = Functions::cleanString($received_id);
$received_estatus = Functions::cleanString($received_estatus);

//DECLARACIÓN DE CLASE PARA EL NUEVO REPORTE
class SetStatusReport {

    private $connection;
    private $id;
    private $estatus;

    public function __construct($id, $estatus) {

        $this->id = $id;
        $this->estatus = $estatus;
        
        $this->connection = Functions::getConnection(); 
    }

    public function set() {
        $sql = 'UPDATE reportes SET estatus = ? WHERE id_reporte = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('si', $this->estatus, $this->id); 
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return 'success';

        } else {

            return false;
        }
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$setStatusReport = new SetStatusReport($received_id, $received_estatus);
$result = $setStatusReport->set();

if($result) { 
    
    echo json_encode($result);

} else { 

    echo json_encode('error');
}