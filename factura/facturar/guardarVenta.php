<?php 
	session_start();
	$idusuario=$_SESSION["codusuario"];
	$ruta="../../";
	include_once($ruta."funciones/codigo.php");
	include_once($ruta."funciones/funciones.php");
	include_once($ruta."class/cliente.php");
	$cliente=new cliente;
	include_once($ruta."class/miempresa.php");
	$miempresa=new miempresa;
	include_once($ruta."class/vusuario2.php");
	$vusuario2=new vusuario2;
	include_once($ruta."class/admdosificacion.php");
	$admdosificacion=new admdosificacion;
	include_once($ruta."class/venta.php");
	$venta=new venta;
	extract($_POST);
	if ($idcliente>0) {
		$valores=array(
		    "nit"=>"'$idnit'",
		    "nombre"=>"'$idnombre'"
		);	
		$cliente->actualizar($valores,$idcliente);
		//echo "datos actualizados";
	}
	else{
		$valores=array("nit"=>"'$idnit'","nombre"=>"'$idnombre'");
		if($cliente->insertar($valores))
		{
			$dCliente=$cliente->mostrarTodo("nit=".$idnit." and usuariocreacion=".$idusuario);
	        $dCliente=array_shift($dCliente);
	        $idcliente=$dCliente['idcliente'];
			//echo "Nuevo Cliente ";
	    }
	}

$datousuario= $vusuario2->muestra($idusuario);
//$datousuario= array_shift($datousuario);

	$dempresa=$miempresa->mostrarTodo("usuariocreacion=".$idusuario." and estado=1");
	$dempresa=array_shift($dempresa);
	$idmiempresa=$dempresa['idmiempresa'];

    $dfactura=$admdosificacion->mostrarTodo("idadmsucursal= ".$datousuario['idsede']." and estado=1");
    $dfactura=array_shift($dfactura);


    $idadmdosificacion=$dfactura['idadmdosificacion'];
    $numAut=$dfactura['autorizacion'];
    $numFactura=$dfactura['nro'];
    $nitCli=$idnit;

    $fTransaccion=$idfecha;
    $date = date_create($fTransaccion);
	$fTransaccion=date_format($date, 'Y-m-d');
	$fTransaccion=str_replace("-", "", $fTransaccion);
    
    $monto=$montototal;
    $llave=$dfactura['llave'];
    //echo "numAut-> ".$numAut."\n";
    //echo "numFactura-> ".$numFactura."\n";
    //echo "nitCli-> ".$nitCli."\n";
    //echo "fTransaccion-> ".$fTransaccion."\n";
    //echo "monto-> ".round($monto)."\n";
    //echo "llave-> ".$llave."\n";

    /********************************* GENERANDO CODIGO DE CONTROL ***********************************/
    $clsControl = new CodigoControl($numAut,$numFactura,$nitCli,$fTransaccion,round($monto),$llave);
    /*************************************************************************************************/
    $codigoControl = $clsControl->generar();
    //echo $codigoControl;
    /******************** ACTUALIZAR NUMERO Y AUMENTAR DE FACTURA ***************************/
    $valFactura=array(
	    "nro"=>$numFactura+1
	);	
	$admdosificacion->actualizar($valFactura,$idadmdosificacion);
	/***************************************************************************/
    $valores=array(
	     "idcliente"=>"'$idcliente'",
	     "idmiempresa"=>"'$idmiempresa'",
	     "iddosificacion"=>"'$idadmdosificacion'",
	     "factura"=>"'$numFactura'",
	     "fecha"=>"'$idfecha'",
	     "costoTotal"=>"'$monto'",
	     "control"=>"'$codigoControl'",
	     "razon"=>"'$idnombre'",
	     "nit"=>"'$idnit'",
	     "impresion"=>"'0'",
	     "estado"=>"'1'",
	     "hostname"=>"'0'"
	);	
	if($venta->insertar($valores))
	{
		$dventa=$venta->mostrarTodo("control='".$codigoControl."' and factura=".$numFactura);
    	$dventa=array_shift($dventa);
    	//echo "IDVENTA: ".$dventa['idventa'];
    	$datos = array(
			'estado' => '1', 
			'idventa' => $dventa['idventa']
		);
	}else{
		//echo "Algo no anda bien";
		$datos = array(
			'estado' => '0', 
			'idventa' => '0'
		);
	}
	echo json_encode($datos, JSON_FORCE_OBJECT);
?>