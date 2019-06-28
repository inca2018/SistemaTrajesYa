var tablaTarifa;

function init() {
    var idProducto = $("#idProducto").val();
    RecuperarProducto(idProducto);
    //ListarTarifas(idProducto);
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
        , "order": [[0, "asc"]]
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
            , dataType: "JSON"
            , error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español
    , }).DataTable();
}

function RecuperarProducto(idProducto) {
    debugger;
    $.post("/Mantenimiento/Producto/ObtenerProducto", {
        "idProducto": idProducto
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#NameProducto").empty();
        $("#NameProducto").html(data.NombreProducto);
    });
}
init();
