<?php

//CABECERAS PARA TRABAJAR CON JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
 
$bytesFoto = $_POST['bytesFoto'];
$nombreFoto = $_POST['nombreFoto'];
 
$fotoFinal = base64_decode($bytesFoto);
 
file_put_contents($nombreFoto, $fotoFinal);
 
echo json_encode('success');