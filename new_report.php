<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
/*$received_asunto = 'Hola';
$received_descripcion = 'Hola';
$received_foto = 'Foto';
$received_usuario = 3;
$received_categoria = 1;
$received_espacio = 2; 
$received_estatus = 'Pendiente';
*/

//RECEPCÍON DE VARIABLES
$received_asunto = $_POST['asunto'];
$received_descripcion = $_POST['descripcion'];
$received_foto = $_POST['foto'];
$received_usuario = $_POST['usuario'];
$received_categoria = $_POST['categoria'];
$received_espacio = $_POST['espacio']; //Recibe los campos del reporte por el método POST
$received_estatus = $_POST['estatus'];

if(!$received_asunto or !$received_descripcion or !$received_foto or !$received_usuario or !$received_categoria or !$received_espacio or !$received_estatus) {
    //Verifica que los valores recibidos no sean vacíos
    die(json_encode('error')); //Si los valores son vaciós muere el script y codifica 'error' en json
}

$received_asunto = Functions::cleanString($received_asunto);
$received_descripcion = Functions::cleanString($received_descripcion);
$received_foto = Functions::cleanString($received_foto);
$received_usuario = Functions::cleanString($received_usuario);
$received_categoria = Functions::cleanString($received_categoria);
$received_espacio = Functions::cleanString($received_espacio); //Llama a una función que limpia las cadenas para prevenir la inyección de código
$received_estatus = Functions::cleanString($received_estatus);

//DECLARACIÓN DE CLASE PARA EL NUEVO REPORTE
class NewReport {

    private $connection;
    private $asunto;
    private $descripcion;
    private $foto;
    private $usuario;
    private $categoria;
    private $espacio;
    private $estatus;

    public function __construct($asunto, $descripcion, $foto, $usuario, $categoria, $espacio, $estatus) { //El constructor recibe los campos del reporte

        $this->asunto = $asunto;
        $this->descripcion = $descripcion;
        $this->foto = $foto;
        $this->usuario = $usuario;
        $this->categoria = $categoria;
        $this->espacio = $espacio; //Inicializa las variables
        $this->estatus = $estatus;
        
        $this->connection = Functions::getConnection(); //Inicializa la variable de conexión llamando la función para ello
    }

    public function add() { //Método público encargado de agregar un nuevo registro a la tabla de reportes

        $sql = 'INSERT INTO reportes VALUES (null, ?, ?, ?, NOW(), ?, ?, ?, ?)';
        $statement = $this->connection->prepare($sql); //Se crea una consulta preparada
        $statement->bind_param('sssiiis', $this->asunto, $this->descripcion, $this->foto, $this->usuario, $this->categoria, $this->espacio, $this->estatus); //Se ligan los parámetros para la consulta preparada, los cuales corresponden a los campos de clase privados
        $statement->execute();

        if($this->connection->affected_rows == 1) { //Si se agregó un registro a la tabla se regresa 'success' en json

            return 'success';

        } else {

            return false; //De lo contrario se regresa false
        }
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$newReport = new NewReport($received_asunto, $received_descripcion, $received_foto, $received_usuario, $received_categoria, $received_espacio, $received_estatus);
$result = $newReport->add();

if($result) { //Si la variable no es falsa, se devuelve su valor en condificación json
    
    echo json_encode($result);

} else { //Si la variable es false, devuelve 'error' en codificación json

    echo json_encode('error');
}