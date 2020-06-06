<?php 
	$ruta="../../";
	include_once($ruta."class/cliente.php");
	$cliente=new cliente;
	$arreglo["data"]=$cliente->mostrarTodo("");
	$json_string = json_encode($arreglo);
	echo $json_string;
?>