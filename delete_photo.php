<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//ZONA DE ARCHIVOS REQUERIDOS
require 'constants.php';
require 'functions.php';

//RECEPCÍON DE VARIABLES
$received_foto = $_POST['foto'];

if(!$received_foto) {

    die(json_encode('error'));
}

$received_foto = Functions::cleanString($received_foto);

if(!unlink($received_foto)) {

    echo json_encode('error');

} else {

    echo json_encode('success');
}