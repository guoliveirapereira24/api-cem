<?php

require("./Connection.php");
require("model/ModelProduto.php");

$conexao = new Connection();

$modelProduto = new ModelProduto($conexao->returnConnection());

$dados = $modelProduto->findAll();

echo '<pre>';
var_dump($dados);
echo '</pre>';

//AEEEE CABOOOO
//FINALMENTEEEEE
//SÓ 6 HORAS DE DOR TRISTEZA E SOFRIMENTO
//SÓ NÉ
?>