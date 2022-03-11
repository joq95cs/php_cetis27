<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//RECEPCÍON DE VARIABLES
$received_username = $_POST['username'];
$received_password = $_POST['password']; //Recibe el usuario y la contraseña por el método POST

if(!$received_username or !$received_password) { //Verifica que los valores recibidos no sean vacíos
    
    die(json_encode('error')); //Si los valores son vaciós muere el script y codifica 'error' en json
}

//LIMPIEZA DE CADENAS
$received_username = Functions::cleanString($received_username);
$received_password = Functions::cleanString($received_password); //Llama a una función que limpia las cadenas para prevenir la inyección de código

//DECLARACIÓN DE CLASE PARA EL LOGIN
class Login {

    private $connection; //Contiene campos privados para la conexión a la base de datos, el usuario y la contraseña
    private $username;
    private $password;

    public function __construct($username, $password) { //El constructor recibe el usuario y la contraseña

        $this->username = $username;
        $this->password = $password; //Inicializa las variables
        
        $this->connection = Functions::getConnection(); //Inicializa la variable de conexión llamando la función para ello
    }

    public function verify() { //Método público que realiza todo el proceso de verificación para iniciar sesión

        $sql = 'SELECT * FROM usuarios WHERE username = ? AND password = ?'; //Instrucción sql que obtiene todos los registros de la tabla según usuario y contraseña
        $statement = $this->connection->prepare($sql); //Se crea una consulta preparada
        $statement->bind_param('ss', $this->username, $this->password); //Se ligan los parámetros para la consulta preparada, los cuales corresponden a los campos de clase privados
        $statement->execute();
        $result = $statement->get_result(); //Se obtienen los resultados y se guardan en una variable

        if($result->num_rows == 1) { //Se verifica que solamente se haya obtenido un registro, ya que se trata de un login

            $data = $result->fetch_assoc(); //Se obtiene el registro como array asociativo y se guarda en otra variable
            
            return array(
                $data['id_usuario'],
                $data['nombre'],
                $data['apellido_paterno'],
                $data['apellido_materno'],
                $data['nivel'],
                $data['tipo']
            ); //Devuelve un array donde cada posición es un campo del registro
        }

        return false; //En caso de que se haya obtenido más de 1 registro, se regresa un valor false
    }
}

//EJECUCIÓN DE INSTRUCCIONES
$login = new Login($received_username, $received_password);
$user = $login->verify();

if($user) { //Si la variable no es falsa, se devuelve su valor en condificación json

    echo json_encode($user);
 
} else { //Si la variable es false, devuelve 'error' en codificación json

    echo json_encode('error');
}