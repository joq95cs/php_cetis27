<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_id = 24;

//RECEPCÍON DE VARIABLES
$received_id = $_POST['id'];

if(!$received_id) {
   
    die(json_encode('error'));
}

$received_id = Functions::cleanString($received_id);

//DECLARACIÓN DE CLASE PARA EL NUEVO REPORTE
class DeletePendingCategory {

    private $connection;
    private $id;

    public function __construct($id) { 

        $this->id = $id;
        
        $this->connection = Functions::getConnection(); 
    }

    public function delete() { 

        $sql = 'DELETE FROM categorias_pendientes WHERE id_categoria_pendiente = ?';
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
$deletePendingCategory = new DeletePendingCategory ($received_id);
$result = $deletePendingCategory->delete();

if($result) { 
    
    echo json_encode($result);

} else { 

    echo json_encode('error');
}