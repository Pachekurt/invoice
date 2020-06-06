<?php 
$ruta="../../";
$folder="";
include_once($ruta."class/dominio.php");
$dominio=new dominio;
include_once($ruta."class/venta.php");
$venta=new venta;
include_once($ruta."class/producto.php");
$producto=new producto;
include_once($ruta."class/ventadetalle.php");
$ventadetalle=new ventadetalle;
include_once($ruta."class/cliente.php");
$cliente=new cliente;
include_once($ruta."class/admdosificacion.php");
$admdosificacion=new admdosificacion;
include_once($ruta."class/miempresa.php");
$miempresa=new miempresa;
include_once($ruta."class/files.php");
$files=new files;
include_once($ruta."funciones/funciones.php");
/******************    SEGURIDAD *************/
session_start();
 
/********************************************/
/********************** SEGURIDAD GET **********************/
extract($_GET);
$valor=dcUrl($lblcode);
 
$dventa=$venta->mostrar($valor);
$dventa=array_shift($dventa);
$dcliente=$cliente->mostrar($dventa['idcliente']);
$dcliente=array_shift($dcliente);
$dempresa=$miempresa->mostrar($dventa['idmiempresa']);
$dempresa=array_shift($dempresa);

$ddepartamento=$dominio->mostrar($dempresa['iddepartamento']);
$ddepartamento=array_shift($ddepartamento);

$dfactura=$admdosificacion->mostrar($dventa['iddosificacion']);
$dfactura=array_shift($dfactura);
setlocale(LC_ALL, 'es_ES').': ';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $ruta; ?>imagenes/favicon.ico" />
    <title>DATOS DE EMPRESA | FACTURA +</title>

    <link href="<?php echo $ruta; ?>recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- Toastr style -->
    <link href="<?php echo $ruta; ?>recursos/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="<?php echo $ruta; ?>recursos/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/css/animate.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/css/style.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="<?php echo $ruta; ?>recursos/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">


    <link href="<?php echo $ruta; ?>recursos/css/template.css" rel="stylesheet">

</head>

<body>
    <div id="wrapper">
      <?php
        $tab=1000;
        include_once("../../aside.php");        
      ?>
      <div id="page-wrapper" class="gray-bg dashbard-1">
        <?php
          include_once("../../head.php");
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                      <h5><div class="tituloForm">VISTA PREVIA</div></h5> 
                      &nbsp; &nbsp; &nbsp; 
                      <a href="../" class="btn btn-success btn-outline btn-lg"><i class="fa fa-mail-reply"></i> Listar Facturas</a>
                      <?php
                        if ($dempresa['tipoempresa']==1) {
                          ?>
                            <a href="<?php echo $ruta ?>impresion/servicio/?lblcode=<?php echo $lblcode ?>" target="_blank" class="btn btn-primary btn-outline btn-lg"><i class="fa fa-print"></i> Imprimir Pdf</a>
                          <?php
                        }elseif ($dempresa['tipoempresa']==2) {
                          ?>
                            <a href="<?php echo $ruta ?>impresion/producto/?lblcode=<?php echo $lblcode ?>" target="_blank" class="btn btn-primary btn-outline btn-lg"><i class="fa fa-print"></i> Imprimir Pdf</a>
                          <?php
                        }
                      ?>
                      <a href="" class="btn btn-warning btn-outline btn-lg"><i class="fa fa-send-o"></i>  Enviar por Correo</a>

                      <div class="ibox-tools">
                          <a class="collapse-link">
                              <i class="fa fa-chevron-up"></i>
                          </a>
                      </div>
                    </div>
                    <div class="ibox-content">
                        <div class=" col-sm-12">
                          <div class=" col-sm-4">
                              <?php
                                  $dfoto=$files->mostrarTodo("id_publicacion=".$dempresa['idmiempresa']);
                                  $dfoto=array_shift($dfoto);
                                  //echo $dfoto['name'];
                                  $rutaFoto=$ruta."administracion/configuracion/miempresa/foto/server/php/".$dempresa['idmiempresa']."/".$dfoto['name'];
                                  //echo $rutaFoto;
                              ?>
                              <center>
                                  <img height="40" src="<?php echo $rutaFoto ?>">
                              </center>                                    
                              <CENTER><b>                                    
                              <?php
                                  echo $dempresa['nombre'];
                              ?></b>
                              <br>
                              CASA MATRIZ:
                              <?php
                                  echo $dempresa['direccion'];
                              ?>
                              <br>
                              Telefonos:
                              <?php
                                  echo $dempresa['telefono'];
                              ?>
                              </CENTER>
                          </div>
                          <div class="col-sm-4"><center><h3><b>FACTURAsss</b></h3></center></div>
                          <div class="col-sm-4">
                            <div class="col-sm-12" style="border: solid 1px #7a7a7a; border-radius: 3px;">                              
                              <div class="col-sm-12"><b>Nro Autorizacion:</b> <?php echo $dfactura['numero']; ?></div>
                              <div class="col-sm-12"><b>Factura:</b> <?php echo $dventa['factura']; ?></div>
                              <div class="col-sm-12"><b>Fecha:</b> <?php echo $dventa['fecha']; ?></div>
                            </div>
                            <div class="col-sm-12">
                              <b>Actividad:</b> <?php echo $dempresa['actividad'];?>
                            </div>
                          </div>
                        </div>
                        <div class=" col-sm-12">
                          <br>
                        </div>
                        <div class=" col-sm-12">
                          <div class=" col-sm-8">
                            <b>Lugar y Fecha: </b> <?php echo $ddepartamento['nombre']; ?>, <?php echo obtenerFechaLetra($dventa['fecha']);?>
                          </div>
                          <div class=" col-sm-4">
                            <b>NIT/CI:</b> <?php echo $dcliente['nit']; ?>
                          </div>
                        </div>
                        <div class=" col-sm-12">
                          <b>Señor(es):</b> <?php echo $dcliente['nombre']; ?>
                        </div>
                        <table id="grilla" class="table">
                          <thead>
                            <tr>
                              <th>Cantidad</th>
                              <th>Detalle</th>
                              <th>Precio Unit.</th>
                              <th>Subtotal</th>
                            </tr>
                          </thead>
                          <?php
                            foreach($ventadetalle->mostrarTodo("idventa=".$valor) as $f){
                              $dproducto=$producto->mostrar($f['idproducto']);
                              $dproducto=array_shift($dproducto);
                              $subtotal=$f['cantidad']*$f['precio'];;
                            ?>
                              <tr>
                                <td><?php echo $f['cantidad']; ?></td>
                                <td><?php echo $dproducto['nombre']; ?></td>
                                <td><?php echo number_format($f['precio'], 2, '.', ','); ?></td>
                                <td><?php echo number_format($subtotal, 2, '.', ','); ?></td>

                              </tr>
                            <?php
                            }                            
                          ?>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <tr>
                              <td></td>
                              <td><b>Son: <?php echo num2letras($dventa['costoTotal'])." BOLIVIANOS"; ?></b></td>
                              <td><h2>TOTAL GENERAL Bs.</h2></td>
                              <td><h2><?php echo number_format($dventa['costoTotal'], 2, '.', ','); ?></h2></td>
                            </tr>
                          </tfoot>
                        </table>
                        <div class=" col-sm-12">
                          <div class=" col-sm-8">
                            Codigo de Control:<?php echo $dventa['control'] ?><br>
                            Fecha Límite de Emisión: 
                            <?php 
                              $date = new DateTime($dfactura['fechaLimite']);
                              echo $date->format('d/m/Y');
                            ?>
                          </div>
                          <div class=" col-sm-4">
                          <img style="width: 50%" src="<?php echo $ruta.'imagenes/qr.jpg' ?>">
                          </div>
                        </div>.
                    </div>
                </div>
            </div>
        </div>
        <div id="idresultado"></div>
        <?php
            $tab=0;
            include_once("../../footer.php");
        ?>
      </div>
    </div>
     <script src="<?php echo $ruta; ?>recursos/js/jquery-2.1.1.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/bootstrap.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/jquery.numeric.js"></script>
    <!-- Peity -->
    <script src="<?php echo $ruta; ?>recursos/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/demo/peity-demo.js"></script>
    <!-- Custom and plugin javascript -->
    <script src="<?php echo $ruta; ?>recursos/js/inspinia.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/pace/pace.min.js"></script>
    <!-- jQuery UI -->
    <script src="<?php echo $ruta; ?>recursos/js/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/gritter/jquery.gritter.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/toastr/toastr.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/switchery/switchery.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Data Tables -->
    <script src="<?php echo $ruta; ?>recursos/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
    <script src="<?php echo $ruta; ?>recursos/js/Forms/facturar.js"></script>
</body>
</html>
