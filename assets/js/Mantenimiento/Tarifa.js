var tablaTarifa;

function init() {
    var idProducto = $("#idProducto").val();
    RecuperarProducto(idProducto);
    ListarTarifas(idProducto);
    IniciarCampos();

    ListarDepartamento();
}

function IniciarCampos() {

    $("#TarifaPrecioBaseV").change(function () {
        var ingreso_bonificacion = $("#TarifaPrecioBaseV").val();
        if (ingreso_bonificacion != '') {
            $("#TarifaPrecioBaseO").val(parseFloat(ingreso_bonificacion));
            $("#TarifaPrecioBaseV").val("S/. " + Formato_Moneda(parseFloat(ingreso_bonificacion), 2));
        }
        else {
            $("#TarifaPrecioBaseO").val(0);
            $("#TarifaPrecioBaseV").val("S/. 0.00");
        }
    });

    $("#TarifaPrecioBaseV").click(function () {
        $("#TarifaPrecioBaseV").val($("#TarifaPrecioBaseO").val());
    });

    $("#TarifaPrecioBaseV").blur(function () {
        $("#TarifaPrecioBaseV").val("S/. " + Formato_Moneda($("#TarifaPrecioBaseO").val(), 2));
    });



     $("#TarifaPrecioVentaV").change(function () {
        var ingreso_bonificacion = $("#TarifaPrecioVentaV").val();
        if (ingreso_bonificacion != '') {
            $("#TarifaPrecioVentaO").val(parseFloat(ingreso_bonificacion));
            $("#TarifaPrecioVentaV").val("S/. " + Formato_Moneda(parseFloat(ingreso_bonificacion), 2));
        }
        else {
            $("#TarifaPrecioVentaO").val(0);
            $("#TarifaPrecioVentaV").val("S/. 0.00");
        }
    });

    $("#TarifaPrecioVentaV").click(function () {
        $("#TarifaPrecioVentaV").val($("#TarifaPrecioVentaO").val());
    });

    $("#TarifaPrecioVentaV").blur(function () {
        $("#TarifaPrecioVentaV").val("S/. " + Formato_Moneda($("#TarifaPrecioVentaO").val(), 2));
    });



      $("#DeliveryPrecio").change(function () {
        var ingreso_bonificacion = $("#DeliveryPrecio").val();
        if (ingreso_bonificacion != '') {
            $("#DeliveryPrecioO").val(parseFloat(ingreso_bonificacion));
            $("#DeliveryPrecio").val("S/. " + Formato_Moneda(parseFloat(ingreso_bonificacion), 2));
        }
        else {
            $("#DeliveryPrecioO").val(0);
            $("#DeliveryPrecio").val("S/. 0.00");
        }
    });

    $("#DeliveryPrecio").click(function () {
        $("#DeliveryPrecio").val($("#DeliveryPrecioO").val());
    });

    $("#DeliveryPrecio").blur(function () {
        $("#DeliveryPrecio").val("S/. " + Formato_Moneda($("#DeliveryPrecioO").val(), 2));
    });


     $("#FormularioTarifa").on("submit", function (e) {
        RegistroTarifa(e);
    });
    $("#FormularioTarifaDelivery").on("submit", function (e) {
        RegistroDelivery(e);
    });

}

function ListarTarifas(idProducto) {
    tablaTarifa = $('#tablaTarifa').dataTable({
        "aProcessing": true
        , "aServerSide": true
        , "processing": true
        , "paging": false, // Paginacion en tabla
        "ordering": true, // Ordenamiento en columna de tabla
        "info": true, // Informacion de cabecera tabla
        "responsive": true, // Accion de responsive
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        , "order": [[2, "asc"]]
        , "bDestroy": true
        , "columnDefs": [
            {
                "className": "text-center"
                , "targets": [2]
            }
            , {
                "className": "text-left"
                , "targets": [0]
            }, {
                "className": "text-right"
                , "targets": []
            }, {
                "className": "text-wrap"
                , "targets": [1]
            }
         , ]
        , "ajax": { //Solicitud Ajax Servidor
            url: '/Mantenimiento/Tarifa/ListarTarifa'
            , type: "POST"
            ,data:{idProducto:idProducto}
            , dataType: "JSON"
            , error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español
    , }).DataTable();
}

function RecuperarProducto(idProducto) {

    $.post("/Mantenimiento/Producto/ObtenerProductoTarifa", {
        "idProducto": idProducto
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#NameProducto").empty();
        $("#NameProducto").html(data.NombreProducto);

        if(data.precioAlquiler==null){
            $("#TarifaPrecioBaseO").val(0.00);
            $("#TarifaPrecioBaseV").val("S/. 0,00");
        }else{
            $("#TarifaPrecioBaseO").val(data.precioAlquiler);
            $("#TarifaPrecioBaseV").val("S/."+data.precioAlquiler);
        }
        if(data.precioVenta==null){
           $("#TarifaPrecioVentaO").val(0.00);
            $("#TarifaPrecioVentaV").val("S/. 0,00");
        }else{
           $("#TarifaPrecioVentaO").val(data.precioVenta);
            $("#TarifaPrecioVentaV").val("S/."+data.precioVenta);
        }


    });
}

function RegistroTarifa(event) {
    var idProducto=$("#idProducto").val();
    var tarifaAlquiler=$("#TarifaPrecioBaseO").val();
    var tarifaVenta=$("#TarifaPrecioVentaO").val();


    event.preventDefault();
    var formData = new FormData($("#FormularioTarifa")[0]);
    formData.append("idProducto",idProducto);
    formData.append("tarifaAlquiler",tarifaAlquiler);
    formData.append("tarifaVenta",tarifaVenta);

    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Tarifa/InsertUpdateTarifa",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data, status) {
            data = JSON.parse(data);
            console.log(data);
            var Mensaje = data.Mensaje;
            var Error = data.Error;
            if (Error) {
                mensaje_warning(Mensaje);
            } else {
                mensaje_success(Mensaje);
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}
function RegistroDelivery(event) {
    var idProducto=$("#idProducto").val();
    var tarifaDelivery=$("#DeliveryPrecioO").val();
    event.preventDefault();
    var formData = new FormData($("#FormularioTarifaDelivery")[0]);
    formData.append("idProducto",idProducto);
    formData.append("tarifaDelivery",tarifaDelivery);

    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Tarifa/InsertUpdateDelivery",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data, status) {
            data = JSON.parse(data);
            console.log(data);
            var Mensaje = data.Mensaje;
            var Error = data.Error;
            if (Error) {
                mensaje_warning(Mensaje);
            } else {
                mensaje_success(Mensaje);
                LimpiarDelivery();
                $("#ModalDelivery").modal("hide");
                tablaTarifa.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}

/*_____________Listado Ubigeo________________*/


function ListarDepartamento() {
    $.post("/Mantenimiento/Producto/ListarDepartamento", function (ts) {
        $("#DeliveryDepartamento").empty();
        $("#DeliveryDepartamento").append(ts);
    });
    $("#DeliveryDepartamento").change(function () {
        var idDepartamento = $(this).val();
        ListarProvincia(idDepartamento);
        $("#DeliveryDistrito").empty();
        $("#DeliveryDistrito").html("<option value='0'>--- SELECCIONE ---</option>")
    });
}

function ListarProvincia(idDepartamento) {
    $.post("/Mantenimiento/Producto/ListarProvincia", {
        idDepartamento: idDepartamento
    }, function (ts) {
        $("#DeliveryProvincia").empty();
        $("#DeliveryProvincia").append(ts);
    });
    $("#DeliveryProvincia").change(function () {
        var idProvincia = $(this).val();
        ListarDistrito(idProvincia);
    });
}

function ListarDistrito(idProvincia) {
    $.post("/Mantenimiento/Producto/ListarDistrito", {
        idProvincia: idProvincia
    }, function (ts) {
        $("#DeliveryDistrito").empty();
        $("#DeliveryDistrito").append(ts);
    });
}


function NuevoDelivery() {
    $("#ModalDelivery").modal({
        backdrop: 'static',
        keyboard: false
    });
    LimpiarDelivery();
    $("#ModalDelivery").modal("show");
    $("#tituloModalDelivery").empty();
    $("#tituloModalDelivery").append("Registro de Delivery");

}
function LimpiarDelivery() {
    $('#FormularioTarifaDelivery')[0].reset();
    $("#idDelivery").val("");
}
function Cancelar() {
    LimpiarDelivery();
    $("#ModalDelivery").modal("hide");
}

function EliminarDelivery(idDelivery, ubicacion) {
    swal({
        title: "Eliminar Delivery?",
        text: "Esta seguro de eliminar al Delivery: " + ubicacion,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Tarifa/EliminarDelivery", {
            'idDelivery': idDelivery,
            'Descripcion':ubicacion,
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaTarifa.ajax.reload();
            }
        });
    });
}

init();
