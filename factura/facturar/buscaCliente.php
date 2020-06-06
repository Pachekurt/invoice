<?php 
	$ruta="../../";
	include_once($ruta."class/cliente.php");
	$cliente=new cliente;
	extract($_POST);
	//echo $idnit;
    $dCliente=$cliente->mostrarTodo("nit='".$idnit."'");
	if (count($dCliente)>0) {
		$dCliente=array_shift($dCliente);
		$datos = array(
			'estado' => '1',
			'idcliente' => $dCliente['idcliente'],
			'nombre' => $dCliente['nombre']
		);
	}
	else{
		$datos = array(
			'estado' => '0',
			'estado' => '0',
			'nombre' => ''
		);
	}
		echo json_encode($datos, JSON_FORCE_OBJECT);

?>