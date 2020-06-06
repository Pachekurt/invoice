<?php 
	$ruta="../../";
	include_once($ruta."class/producto.php");
	$producto=new producto;
	$arreglo["data"]=$producto->mostrarTodo("");
	$json_string = json_encode($arreglo);
	echo $json_string;

?>