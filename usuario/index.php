<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: aplication/json");

include("../Connection.php");
include("../model/ModelUsuario.php");
include("../controller/ControllerUsuario.php");

$conexao = new Connection();

$model = new ModelUsuario($conexao->returnConnection());

$controller = new ControllerUsuario($model);

$dados = $controller->router();

echo json_encode(array("status"=>"Success","data"=>$dados))


?>