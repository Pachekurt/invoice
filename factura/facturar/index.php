<?php
  $ruta="../../";
  include_once($ruta."class/dominio.php");
  $dominio=new dominio;
  include_once($ruta."class/vejecutivo.php");
  $vejecutivo=new vejecutivo;
  include_once($ruta."class/cliente.php");
  $cliente=new cliente;
  include_once($ruta."class/usuario.php");
  $usuario=new usuario;   
  include_once($ruta."class/sede.php");
  $sede=new sede;      
  include_once($ruta."class/miempresa.php");
  $miempresa=new miempresa;    

  include_once($ruta."class/files.php");
  $files=new files;    

  include_once($ruta."class/admsucursal.php");
  $admsucursal=new admsucursal; 
  include_once($ruta."class/admdosificacion.php");
  $admdosificacion=new admdosificacion;  
  include_once($ruta."funciones/funciones.php");
  session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
      $hd_titulo="Facturacion";
      include_once($ruta."includes/head_basico.php");
      include_once($ruta."includes/head_tabla.php");
    ?>
</head>
<body>
    <?php
      include_once($ruta."head.php");
    ?>
    <div id="main">
      <div class="wrapper">
        <?php
          $idmenu=31;
          include_once($ruta."aside.php");
        ?>
        <section id="content">
          <!--breadcrumbs start-->
          <div id="breadcrumbs-wrapper">
            <div class="container">
              <div class="row">
                <div class="col s12 m12 l12">
                  <h5 class="breadcrumbs-title"><i class="fa fa-tag"></i><?php echo $hd_titulo; ?> 
                </div>
              </div>
            </div>
          </div>
          <div class="container">
            <div class="formcontent">
  <?php 

                        $dsede=$sede->mostrarUltimo("idsede=".$_SESSION["idsede"]);

                        $dsuc=$admsucursal->mostrarUltimo("idsede=".$_SESSION["idsede"]." and estado=1");
                        $idsucursal=$dsuc['idadmsucursal'];
                        $ddos=$admdosificacion->mostrarUltimo("idadmsucursal=".$idsucursal." and estado=1");
                        $iddosificacion=$ddos['idadmdosificacion'];
                        $nro=$ddos['nro'];

                       ?>

     <div class="row">        
              <div class="col s12 m12 l12">
                            <div class="col s12 m4 l4">
                                <?php

                                   $dempresa=$miempresa->mostrarTodo( " estado=1");
                                   $dempresa=array_shift($dempresa);  

                                 //  $dfoto=$files->mostrarTodo("id_publicacion=".$dempresa['idmiempresa']);
                                  // $dfoto=array_shift($dfoto);
                                    
                               //     $rutaFoto=$ruta."administracion/configuracion/miempresa/foto/server/php/".$dempresa['idmiempresa']."/".$dfoto['name'];
                                    $rutaFoto=$ruta."recursos/images/logo.png"
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
                                    echo $dsuc['direccion'];
                                ?>
                                <br>
                                Telefonos:
                                <?php
                                    echo $dsuc['telefonos'];
                                ?>
                                </CENTER>
                            </div>

                          

                            <div class="col s12 m4 l4">
                                <div class="col s12 m5 l5">Nro Autorizacion:</div>
                                <div class="col s12 m7 l7"><?php echo $ddos['autorizacion']; ?></div>
                                <div class="col s12 m5 l5">Factura:</div>
                                <div class="col s12 m7 l7"><?php echo $ddos['nro']; ?></div>
                                <div class="col s12 m5 l5">Fecha</div>
                                <div class="col s12 m7 l7"><input id="idfecha" name="idfecha" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" name=""></div>
                            </div> 
                             <div class="col s12 m4 l4" style="font-size: 23px; color: blue;"><center><b><?php echo $dsuc['actividad']; ?></b>
                            <br>
                                </center></div> 
              </div>
        </div>
              <div class="row">
                <div class="titulo">Datos de Factura</div>
                <div class="col s12 m12 l12">
                  <form class="col s12" id="idform" action="return false" onsubmit="return false" method="POST">
                    <input type="hidden" name="idcliente" id="idcliente" value="0">
                    
                    <div class="row">
                        
                      <div class="input-field col s8 m4 l4">
                        <input id="idnit"  placeholder="Ingrese el Nit" name="idnit" type="number" class="validate">
                        <label for="idnit">CI/NIT</label>
                      </div> 
                       <div class=" col s2 m1 l1">
                       

                        <button id="btnClientes" class="waves-effect waves-light btn modal-trigger darken-3 green light-blue" href="#modal1"><i class="fa fa-users"></i></button>
                     </div>
 

                      <div class="input-field col s10 m7 l7">
                        <input id="idnombre"  placeholder="Ingrese la Nombre o Razon Social..." name="idnombre" type="text" class="validate">
                        <label for="idnombre">RAZON SOCIAL</label>
                      </div>
                      


                         
                      <div class="input-field col s12 m4 l4">
                        <input type="hidden" name="idproducto" id="idproducto"  >
                        <input id="idnombreproducto"   value=""   name="idnombreproducto"  placeholder="Ingrese el concepto de facturacion" type="text" class="validate">
                        <label for="idnombreproducto">CONCEPTO</label>
                      </div>
                      <div class=" col s2 m1 l1"> 
                        <button id="btnProdcutos" class="waves-effect waves-light btn modal-trigger darken-3 green  light-blue" href="#modal2"><i class="fa fa-th-list"></i></button>
                     </div>
                          
                          <div class="input-field col s2 m1 l1">
                            <input id="idcantidad" style="text-align: center;"   readonly name="idcantidad" type="text" class="validate" value="1">
                          </div>

                      <div class="col s2 m1 l1">
                        
                        <button id="idresta" class="btn-floating  waves-effect waves-light darken-3 red"><i class="mdi-content-remove-circle"></i></button>

                        <button id="idsuma" class="btn-floating   waves-effect waves-light darken-3 green"><i class="mdi-content-add-circle"></i></button>
                      
                        </div>
                      <div class="input-field col s12 m3 l3">
                        <input id="idprecio" style="text-align: center;" placeholder="Ingrese el costo unitario" name="idprecio" step="any" type="number" class="validate">
                        <label for="idprecio">COSTO UNITARIO</label>
                      </div>


                    
                      <div class="input-field col s12 m2 l2">
                        <a   id="btnAgregar" class="btn waves-effect waves-light darken-3 green"><i class="fa fa-download"></i> AGREGAR  </a>
                      </div>
                    </div>
                  </form>
                </div>&nbsp;
<div class="titulo"></div>
     <div class="container">
        <div class="section">

          <div class="col s12 m12 l12">

              <table id="grilla" class="table">
                    <thead>
                        <tr>
                            <th>Cod</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Subtotal</th>
                            <th>Quitar</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
          </div> 
           <div class="row">
                    <div class="col s2 offset-s7">TOTAL</div>
                    <div class="col s1 m1 l1"><input type="text" style="text-align: center;" id="idpreciototales" name="idpreciototales" readonly class=" " value="0" name=""> 
                   
                    </div>

                    <div class="col s2 m2 l2"> <button id="btnProcesar" type="button" class="btn  waves-effect waves-light darken-3 red"><i class="fa fa-save"></i> FACTURAR</button></div>
            </div>
            
        </div>
      </div>
              </div>
            </div>
            </div>
          </div>
        </section>
      </div>
    </div>




    <div id="modal1" class="modal">
   <!-- <div id="modal1" class="modal bottom-sheet">-->
      <div class="modal-content">
         <div style="font-size: 20px;">LISTADO DE CLIENTES</div>
          
             <table id="tablaClientes" class="display" cellspacing="0" width="100%">
             
              <thead>
                  <tr>
                      <th>Nit</th>
                      <th>Nombre</th>
                      <th>SELECCIONAR</th>
                  </tr>
              </thead>
              <tbody>                         
              </tbody>
          </table>  
      </div>
     
    </div>

   <div id="modal2" class="modal">
   <!-- <div id="modal1" class="modal bottom-sheet">-->
      <div class="modal-content">
         <div style="font-size: 20px;">LISTADO DE SERVICIOS Y PRODUCTOS</div>
 
             <table id="tablajson" class="display" cellspacing="0" width="100%">
               
              <thead>
                  <tr> 
                      <th>Nombre</th>
                      <th>Detalle</th>
                      <th>Precio</th>
                      <th>SELECCIONAR</th>
                    </tr>
                   
              </thead>
              <tbody>                         
              </tbody>
          </table>  
      </div>
     
    </div>

    <div id="idresultado"></div>
    <?php
      include_once($ruta."includes/script_basico.php");
      include_once($ruta."includes/script_tabla.php");
    ?>
   
      <script src="<?php echo $ruta; ?>recursos/js/Forms/facturar.js"></script>
      <script type="text/javascript">
            $(document).ready(function() {
            $('#tablajson').DataTable({
              dom: 'Bfrtip',
              
            });
          });
      </script>
</body>

</html>