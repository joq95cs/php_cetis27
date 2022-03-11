<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//VARIABLES DE PRUEBA
//$received_id = 122;

//RECEPCÍON DE VARIABLES
$received_id = $_POST['id'];

if(!$received_id) {
   
    die(json_encode('error'));
}

$received_id = Functions::cleanString($received_id);

//DECLARACIÓN DE CLASE PARA EL NUEVO REPORTE
class DeleteCategory {

    private $connection;
    private $id;

    public function __construct($id) { 

        $this->id = $id;
        
        $this->connection = Functions::getConnection(); 
    }

    public function delete() { 

        $sql = 'DELETE FROM categorias WHERE id_categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $this->id);
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return true;

        } else {

            return false;
        }
    }

    public function deleteDepartment() {

        $sql = 'DELETE FROM categorias_departamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $this->id);
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return true;

        } else {

            return false;
        }
    }

    public function deleteSubdepartment() {

        $sql = 'DELETE FROM categorias_subdepartamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $this->id);
        $statement->execute();

        if($this->connection->affected_rows == 1) { 

            return true;

        } else {

            return false;
        }
    }

    public function checkDepartment() {

        $sql = 'SELECT * FROM categorias_departamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $this->id);
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows == 1) {

            return true;

        } else {

            return false;
        }
    }

    public function checkSubdepartment() {

        $sql = 'SELECT * FROM categorias_subdepartamentos WHERE categoria = ?';
        $statement = $this->connection->prepare($sql); 
        $statement->bind_param('i', $this->id);
        $statement->execute();
        $result = $statement->get_result();

        if($result->num_rows == 1) {

            return true;

        } else {

            return false;
        }
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$deleteCategory = new DeleteCategory($received_id);

if($deleteCategory->checkDepartment()) {

    $result = $deleteCategory->deleteDepartment();

    //echo $result;

    if(!$result) {

        die(json_encode('error'));
    }

    if($deleteCategory->checkSubdepartment()) {

        $result = $deleteCategory->deleteSubdepartment();

        //echo $result;

        if(!$result) {

            die(json_encode('error'));
        }
    }

    $result = $deleteCategory->delete();

    //echo $result;

    if($result) { 
    
        echo json_encode('success');
    
    } else { 
    
        echo json_encode('error');
    }

} else {

    $result = $deleteCategory->delete();

    if($result) { 
    
        echo json_encode('success');
    
    } else { 
    
        echo json_encode('error');
    }
}
