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
$received_espacio = 6;
$received_departamento = 4;
$received_subdepartamento = 5;*/

//RECEPCÍON DE VARIABLES
$received_nombre = $_POST['nombre'];
$received_descripcion = $_POST['descripcion'];
$received_espacio = $_POST['espacio'];
$received_departamento = $_POST['departamento'];
$received_subdepartamento = $_POST['subdepartamento'];


if(!$received_nombre or !$received_descripcion or !$received_espacio or !$received_departamento or !$received_subdepartamento) {
    //Verifica que los valores recibidos no sean vacíos
    die(json_encode('error')); //Si los valores son vaciós muere el script y codifica 'error' en json
}

$received_nombre = Functions::cleanString($received_nombre);
$received_descripcion = Functions::cleanString($received_descripcion);
$received_espacio = Functions::cleanString($received_espacio);
$received_departamento = Functions::cleanString($received_departamento);
$received_subdepartamento = Functions::cleanString($received_subdepartamento);

class NewCategory {

    private $nombre;
    private $descripcion;
    private $espacio;
    private $departamento;
    private $subdepartamento;

    public function __construct($nombre, $descripcion, $espacio, $departamento, $subdepartamento) { //El constructor recibe los campos del reporte

        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->espacio = $espacio;
        $this->departamento = $departamento;
        $this->subdepartamento = $subdepartamento;
        
        $this->connection = Functions::getConnection(); //Inicializa la variable de conexión llamando la función para ello
    }

    public function add() { //Método público encargado de agregar un nuevo registro a la tabla de reportes

        $sql = 'INSERT INTO categorias VALUES (null, ?, ?, ?)';
        $statement = $this->connection->prepare($sql); //Se crea una consulta preparada
        $statement->bind_param('ssi', $this->nombre, $this->descripcion, $this->espacio); //Se ligan los parámetros para la consulta preparada, los cuales corresponden a los campos de clase privados
        $statement->execute();

        if($this->connection->affected_rows == 1) { //Si se agregó un registro a la tabla se regresa 'success' en json

            return true;

        } else {

            return false; //De lo contrario se regresa false
        }
    }

    public function getID() {

        $sql = 'SELECT id_categoria FROM categorias WHERE nombre = ? AND descripcion = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('ss', $this->nombre, $this->descripcion);
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows == 1) {

            return $result->fetch_assoc()['id_categoria'];

        } else {

            return false;
        }
    }
    
    public function addTarget($id) {

        $sql = 'INSERT INTO categorias_departamentos VALUES (null, ?, ?)';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('ii', $id, $this->departamento);
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return true;

        } else {

            return false; 
        }
    }

    public function addSubtarget($id) {

        $sql = 'INSERT INTO categorias_subdepartamentos VALUES (null, ?, ?)';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('ii', $id, $this->subdepartamento);
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return true;

        } else {

            return false; 
        }
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$newCategory = new NewCategory($received_nombre, $received_descripcion, $received_espacio, $received_departamento, $received_subdepartamento);

if($newCategory->add()) {

    if($received_departamento != 'null') {

        $id = $newCategory->getID();

        if(!$id) {

            die(json_encode('error'));
        }
    
        if($newCategory->addTarget($id)) {

            if($received_subdepartamento != 'null') {

                if($newCategory->addSubtarget($id)) {

                    echo json_encode('success');
    
                } else { 
    
                    echo json_encode('error');
                }

            } else {

                echo json_encode('success');
            }

        } else { 

            echo json_encode('error');
        }

    } else {

        echo json_encode('success');
    }

} else { 

    echo json_encode('error');
}