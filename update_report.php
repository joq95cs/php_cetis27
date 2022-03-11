<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
/*$received_id = 30;
$received_asunto = 'Hola';
$received_descripcion = 'Hola';
$received_foto = 'Foto';
$received_usuario = 7;
$received_categoria = 14;
$received_espacio = 6; 
$received_estatus = 'Atendido';*/

//RECEPCÍON DE VARIABLES
$received_id = $_POST['id'];
$received_asunto = $_POST['asunto'];
$received_descripcion = $_POST['descripcion'];
$received_foto = $_POST['foto'];
$received_usuario = $_POST['usuario'];
$received_categoria = $_POST['categoria'];
$received_espacio = $_POST['espacio']; 
$received_estatus = $_POST['estatus'];

if(!$received_id or !$received_asunto or !$received_descripcion or !$received_foto or !$received_usuario or !$received_categoria or !$received_espacio or !$received_estatus) {
  
    die(json_encode('error')); 
}

$received_id = Functions::cleanString($received_id);
$received_asunto = Functions::cleanString($received_asunto);
$received_descripcion = Functions::cleanString($received_descripcion);
$received_foto = Functions::cleanString($received_foto);
$received_usuario = Functions::cleanString($received_usuario);
$received_categoria = Functions::cleanString($received_categoria);
$received_espacio = Functions::cleanString($received_espacio); 
$received_estatus = Functions::cleanString($received_estatus);

//DECLARACIÓN DE CLASE PARA EL NUEVO REPORTE
class UpdateReport {

    private $connection;
    private $id;
    private $asunto;
    private $descripcion;
    private $foto;
    private $usuario;
    private $categoria;
    private $espacio;
    private $estatus;

    public function __construct($id, $asunto, $descripcion, $foto, $usuario, $categoria, $espacio, $estatus) { 

        $this->id = $id;
        $this->asunto = $asunto;
        $this->descripcion = $descripcion;
        $this->foto = $foto;
        $this->usuario = $usuario;
        $this->categoria = $categoria;
        $this->espacio = $espacio; 
        $this->estatus = $estatus;
        
        $this->connection = Functions::getConnection(); 
    }

    public function update() { 

        $sql = 'UPDATE reportes SET asunto = ?, descripcion = ?, foto = ?, fecha = NOW(), usuario = ?, categoria = ?, espacio = ?, estatus = ? WHERE id_reporte = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('sssiiisi', $this->asunto, $this->descripcion, $this->foto, $this->usuario, $this->categoria, $this->espacio, $this->estatus, $this->id); 
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return 'success';

        } else {

            return false; 
        }
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$updateReport = new UpdateReport($received_id, $received_asunto, $received_descripcion, $received_foto, $received_usuario, $received_categoria, $received_espacio, $received_estatus);
$result = $updateReport->update();

if($result) {
    
    echo json_encode($result);

} else {
    echo json_encode('error');
}