var tablaTarifa;

function init() {
    var idProducto = $("#idProducto").val();
    RecuperarProducto(idProducto);

    IniciarCampos();


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




     $("#FormularioTarifa").on("submit", function (e) {
        RegistroTarifa(e);
    });


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



init();
