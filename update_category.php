<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
/*$received_id = 128;
$received_nombre = 'Categoría modificada';
$received_descripcion = 'Esta es una categoría modificada';
$received_espacio = 4;
$received_departamento = '12';
$received_subdepartamento = 'null';*/

//RECEPCÍON DE VARIABLES
$received_id = $_POST['id'];
$received_nombre = $_POST['nombre'];
$received_descripcion = $_POST['descripcion'];
$received_espacio = $_POST['espacio'];
$received_departamento = $_POST['departamento'];
$received_subdepartamento = $_POST['subdepartamento'];

if(!$received_id or !$received_nombre or !$received_descripcion or !$received_espacio or !$received_departamento or !$received_subdepartamento) {
    
    die(json_encode('error')); 
}

$received_id = Functions::cleanString($received_id);
$received_nombre = Functions::cleanString($received_nombre);
$received_descripcion = Functions::cleanString($received_descripcion);
$received_espacio = Functions::cleanString($received_espacio);
$received_departamento = Functions::cleanString($received_departamento);
$received_subdepartamento = Functions::cleanString($received_subdepartamento);

class UpdateCategory {

    private $id;
    private $nombre;
    private $descripcion;
    private $espacio;
    private $departamento;
    private $subdepartamento;

    public function __construct($id, $nombre, $descripcion, $espacio, $departamento, $subdepartamento) { 

        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->espacio = $espacio;
        $this->departamento = $departamento;
        $this->subdepartamento = $subdepartamento;
        
        $this->connection = Functions::getConnection(); 
    }

    public function update() { 

        $sql = 'UPDATE categorias SET nombre = ?, descripcion = ?, espacio = ? WHERE id_categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('ssii', $this->nombre, $this->descripcion, $this->espacio, $this->id); 
        $statement->execute();

        $sql = 'SELECT * FROM categorias WHERE nombre = ? AND descripcion = ? AND espacio = ? AND id_categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('ssii', $this->nombre, $this->descripcion, $this->espacio, $this->id); 
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows == 1) { 

            return true;

        } else {

            return false; 
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
    
    public function deleteTarget($id) {

        $sql = 'DELETE FROM categorias_departamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $id);
        $statement->execute();

        $sql = 'SELECT * FROM categorias_departamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows == 0) {

            return true;

        } else {

            return false; 
        }
    }

    public function deleteSubtarget($id) {

        $sql = 'DELETE FROM categorias_subdepartamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $id);
        $statement->execute();

        $sql = 'SELECT * FROM categorias_subdepartamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows == 0) {

            return true;

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
$updateCategory = new UpdateCategory($received_id, $received_nombre, $received_descripcion, $received_espacio, $received_departamento, $received_subdepartamento);

if($updateCategory->update()) {

    $id = $updateCategory->getID();

    if(!$id) {

        die(json_encode('error'));
    }

    if(!$updateCategory->deleteTarget($id)) {

        die(json_encode('error'));
    }

    if($received_departamento != 'null') {
    
        if($updateCategory->addTarget($id)) {

            if(!$updateCategory->deleteSubtarget($id)) {

                die(json_encode('error'));
            }

            if($received_subdepartamento != 'null') {

                if($updateCategory->addSubtarget($id)) {

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