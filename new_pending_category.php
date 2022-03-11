<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
/*$received_nombre = 'Categoría';
$received_descripcion = 'Esta es una categoría';
$received_espacio = 'Espacio';*/

//RECEPCÍON DE VARIABLES
$received_nombre = $_POST['nombre'];
$received_descripcion = $_POST['descripcion'];
$received_espacio = $_POST['espacio'];

if(!$received_nombre or !$received_descripcion or !$received_espacio) {
    //Verifica que los valores recibidos no sean vacíos
    die(json_encode('error')); //Si los valores son vaciós muere el script y codifica 'error' en json
}

$received_nombre = Functions::cleanString($received_nombre);
$received_descripcion = Functions::cleanString($received_descripcion);
$received_espacio = Functions::cleanString($received_espacio);

class NewPendingCategory {

    private $nombre;
    private $descripcion;
    private $espacio;

    public function __construct($nombre, $descripcion, $espacio) { //El constructor recibe los campos del reporte

        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->espacio = $espacio;
        
        $this->connection = Functions::getConnection(); //Inicializa la variable de conexión llamando la función para ello
    }

    public function add() { //Método público encargado de agregar un nuevo registro a la tabla de reportes

        $sql = 'INSERT INTO categorias_pendientes VALUES (null, ?, ?, ?)';
        $statement = $this->connection->prepare($sql); //Se crea una consulta preparada
        $statement->bind_param('ssi', $this->nombre, $this->descripcion, $this->espacio); //Se ligan los parámetros para la consulta preparada, los cuales corresponden a los campos de clase privados
        $statement->execute();

        if($this->connection->affected_rows == 1) { //Si se agregó un registro a la tabla se regresa 'success' en json

            return 'success';

        } else {

            return false; //De lo contrario se regresa false
        }
    }

}

//EJECUCIÓN DE INSTRUCCIONES
$newPendingCategory = new NewPendingCategory($received_nombre, $received_descripcion, $received_espacio);
$result = $newPendingCategory->add();

if($result) { //Si la variable no es falsa, se devuelve su valor en condificación json
    
    echo json_encode($result);

} else { //Si la variable es false, devuelve 'error' en codificación json

    echo json_encode('error');
}