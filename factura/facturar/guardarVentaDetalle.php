<?php 
	session_start();
	extract($_POST);
	$idusuario=$_SESSION["codusuario"];
	$ruta="../../";	
	include_once($ruta."funciones/funciones.php");
	include_once($ruta."class/ventadetalle.php");
	$ventadetalle=new ventadetalle;
	include_once($ruta."class/producto.php");
	$producto=new producto;
	if ($idproducto>0) {
		$valores=array(
		    "nombre"=>"'$nombre'",
		    "precio"=>"'$costo'"
		);	
		$producto->actualizar($valores,$idproducto);
		echo "datos actualizados";
	}
	else{
		$valores=array( "nombre"=>"'$nombre'",
						"precio"=>"'$costo'",
						"idmedida"=>"'30'"
		);
		if($producto->insertar($valores))
		{
			$dproducto=$producto->mostrarTodo("nombre='".$nombre."' and precio=".$costo." and usuariocreacion=".$idusuario);
	        $dproducto=array_shift($dproducto);
	        $idproducto=$dproducto['idproducto'];
			echo "Nuevo Producto ";
	    }
	}
    $valores=array(
	    "idventa"=>$idventa,
	    "idproducto"=>"'$idproducto'",
	    "cantidad"=>"'$cantidad'",
	    "precio"=>"'$costo'"
	);	
	if($ventadetalle->insertar($valores))
	{		
    	echo "INSERTADO CORRECTAMENTE";
    	
	}else{
		echo "Algo no anda bien";
		
	}
?>