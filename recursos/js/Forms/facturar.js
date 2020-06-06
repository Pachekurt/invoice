$(document).ready(function() {
    $('#idcantidad').numeric();
    $('#idprecio').numeric(".");
}); 
$("body").toggleClass("mini-navbar");
/*****************  GENERAR VENTA ************************************/
$("#btnProcesar").click(function(){
    if (validarFormulario()) {    
        var str = $( "#idform" ).serialize();
        idfecha = $("#idfecha").val();
        montototal = $("#idpreciototales").val();
        str=str+"&idfecha="+idfecha+"&montototal="+montototal;
        //alert(idfecha);
        //alert(str);
        swal({
            title: "¿Estas Seguro?",
            text: "Estas Seguro de Activar la Empresa",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#09054c",
            confirmButtonText: "Si estoy Seguro",
            closeOnConfirm: false
        }, function () {      
            $.ajax({
                url: "guardarVenta.php",
                type: "POST",
                data: str
            }).done(function(respuesta){
                var response = JSON.parse(respuesta);
                console.log(response);                    
                if (response.estado == "1") {
                    //alert(response.idventa);
                    insertaProductos(response.idventa);
                    swal({
                        title: "IMPRIMIR ?",
                        text: "Si decide que no, puede imprimir la factura en el listado de facturas",
                        type: "success",
                        showCancelButton: true,
                        cancelButtonColor: "#fc9d20",
                        confirmButtonColor: "#09054c",
                        confirmButtonText: "Si, Imprimir",
                        cancelButtonText: "No, Seguir Facturando",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm) {
                           //  idven=ecUrl(response.idventa);
                            window.location="../impresion/computarizada/?lblcode="+response.idventa;
                        } else {
                            location.reload();
                        }
                    });
                }
                else{
                    msg("Error", "No se pudo registrar");
                }
            });
        });
        //swal.close();
    }
    else{
        msg("Error", "No se puede generar la factura en tales condiciones");
    }
});
/********************** inserta Productos a la tabla ventadetalle ***********/
function insertaProductos(idventa){
    //alert(idventa);
    var idproducto, nombre, costo, cantidad;
    $("#grilla tbody tr").each(function (index) 
    {
        $(this).children("td").each(function (index2) 
        {
            switch (index2) 
            {
                case 0: idproducto = $(this).text();
                break;
                case 1: nombre = $(this).text();
                break;
                case 2: cantidad = $(this).text();
                break;
                case 3: costo = $(this).text();
                break;
            }
        });
        insertProductos(idventa,idproducto,nombre,cantidad,costo);
    });
}
function insertProductos(idventa,idproducto,nombre,cantidad,costo){
  $.ajax({
      url: "guardarVentaDetalle.php",
      type: "POST",
      data: "idventa="+idventa+"&idproducto="+idproducto+"&nombre="+nombre+"&cantidad="+cantidad+"&costo="+costo,
      success: function(resp){
        console.log(resp);
        msg(resp,"MENSAJE");
      }
  });
}
/****************************************************************/
function validarFormulario(){
    return true;
}
/*********************************************************************/
function valPrecio(precio){
    precio=parseFloat(precio);
    precio=precio.toFixed(2);
    return precio;
}
var agregarProducto=function(){
    var idcodigo=$("#idproducto").val();
    var producto=$("#idnombreproducto").val();
    var cantidad=$("#idcantidad").val();
    var precio=$("#idprecio").val();      
    var total= parseFloat(precio)*parseFloat(cantidad); 
    total= valPrecio(total);
    cadena = "<tr>";
    cadena = cadena + "<td>" + idcodigo + "</td>";
    cadena = cadena + "<td>" + producto + "</td>";
    cadena = cadena + "<td>" + cantidad + "</td>";
    cadena = cadena + "<td>" + precio + "</td>";
    cadena = cadena + "<td>" + total + "</td>";
    cadena = cadena + "<td><a class='elimina btn btn-danger' href='javascript:fn_dar_eliminar();'><i class='fa fa-trash'></i></a></td>";
    $("#grilla tbody").append(cadena);
    calcularCostoTotal();
    fn_dar_eliminar();
}
function fn_dar_eliminar(){
    $("a.elimina").click(function(){        
        $(this).parents("tr").fadeOut("normal", function(){
          $(this).remove();
          calcularCostoTotal();
        });
    });
  };    
function calcularCostoTotal(){
    var costoTotal=0;
    $("#grilla tbody tr").each(function (index) 
    {
        var campo5;
        $(this).children("td").each(function (index2) 
        {
            switch (index2) 
            {
                case 4: campo5 = $(this).text();
                costoTotal=parseFloat(costoTotal)+parseFloat(campo5);

                break;
            }
        });
    });
    costoTotal= valPrecio(costoTotal);
    $("#idpreciototales").val(costoTotal);
}
var listarProductos=function(){
    $("#tablajson").dataTable().fnDestroy();
    var table=$("#tablajson").dataTable({
        "ajax":{
            "method":"POST",
            "url":"mostrarProductos.php"
        },
        "columns":[
            {"data":"nombre"},
            {"data":"detalle"},
            {"data":"precio"},
            {"defaultContent":"<button type='button' class='btn waves-effect waves-light ideditar'><i class='fa fa-check-square'></i></button>"}
        ]
    });
    obtenerDatosProducto("#tablajson tbody",table);
}
var listarClientes=function(){
    $("#tablaClientes").dataTable().fnDestroy();
    var table=$("#tablaClientes").dataTable({
        "ajax":{
            "method":"POST",
            "url":"mostrarClientes.php"
        },
        "columns":[
            {"data":"nit"},
            {"data":"nombre"},
            {"defaultContent":"<button type='button' class='btn waves-effect waves-light darken-3 green ideditarCli'><i class='fa fa-check-square'></i></button>"}
        ]
    });
    obtenerDatosCliente("#tablaClientes tbody",table);
}
var obtenerDatosProducto=function(tbody,table){
    $(tbody).on("click","button.ideditar",function(){
        var data=table.api().row( $(this).parents("tr") ).data();        
        $("#idproducto").val(data.idproducto);
        $("#idnombreproducto").val(data.nombre);
        $("#idprecio").val(data.precio);
        $('#modal2').closeModal();
        $("#idcantidad").val("1");
        $("#idcantidad").focus();
        console.log(data);
    });
}
var obtenerDatosCliente=function(tbody,table){
    $(tbody).on("click","button.ideditarCli",function(){
        var data=table.api().row( $(this).parents("tr") ).data();
        $("#idcliente").val(data.idcliente);
        $("#idnit").val(data.nit);
        $("#idnombre").val(data.nombre);
        $('#modal1').closeModal();
        $("#idnombreproducto").focus();
        console.log(data);
    });
}
$("#idnit" ).blur(function() { 
    nit = $("#idnit").val();
    $.ajax({
        url: "buscaCliente.php",
        type: "POST",
        data: "idnit="+nit
    }).done(function(respuesta){
        var response = JSON.parse(respuesta);
        if (response.estado === "1") {
            $("#idcliente").val(response.idcliente);
            $("#idnombre").val(response.nombre);
            $("#idnombreproducto").focus();
        }
        else{
            $("#idnombre").val("");
            $("#idnombre").focus();
        }
    });
});
//LLENAMOS DATA TABLE CON CONTENIDO DESDE BASE DEDATOS
$("#idresta").click(function(){
    cantidad=parseFloat($("#idcantidad").val())-1;
    if (cantidad<=0) {
        msg("Error", "EL monto no puede ser menor a 1");
    }else{   
        $("#idcantidad").val(cantidad);
    }
});
$("#idsuma").click(function(){
    $("#idcantidad").val(parseFloat($("#idcantidad").val())+1);
});

$("#btnProdcutos").click(function(){
    listarProductos();
});
$("#btnClientes").click(function(){
    listarClientes();
});
$("#btnAgregar").click(function(){
    if ($("#idnombreproducto").val()==""|| $("#idprecio").val()=="") {
        msg("Error", "No hay datos para agregar");
    }else{
        agregarProducto();
        limpiarProducto();
    }
});
var limpiarProducto=function(){
    $("#idproducto").val("0");
    $("#idnombreproducto").val("");
    $("#idprecio").val("");
    $("#idcantidad").val("1");
} 
function validar(){
retorno=true;
//valida nombre del producto
dato=$("#idnombre").val();
if (dato==" " || dato=="") {
  $("#labnombre").attr("style","color:red");
  retorno=false;
}
else{
  $("#labnombre").attr("style","color:green");
}        
return retorno;
}
 

  function msg(mensaje, titulo){
        Materialize.toast('<span>'+mensaje+'. '+titulo+'</span>', 3500);
      }