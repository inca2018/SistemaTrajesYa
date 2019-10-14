var tablaTipoTarjeta;

function init() {
    Iniciar_Componentes();
    Listar_TipoTarjeta();

}

function Iniciar_Componentes() {
    $("#FormularioTipoTarjeta").on("submit", function (e) {
        RegistroTipoTarjeta(e);
    });
}
function RegistroTipoTarjeta(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioTipoTarjeta")[0]);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/TipoTarjeta/InsertUpdateTipoTarjeta",
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
                $('#ModalTipoTarjeta').modal('hide');
                mensaje_success(Mensaje)
                LimpiarTipoTarjeta();
                tablaTipoTarjeta.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}
function Listar_TipoTarjeta() {
    tablaTipoTarjeta = $('#tablaTipoTarjeta').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "processing": true,
        "paging": true, // Paginacion en tabla
        "ordering": true, // Ordenamiento en columna de tabla
        "info": true, // Informacion de cabecera tabla
        "responsive": true, // Accion de responsive
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[1, "asc"]],
        "bDestroy": true,
        "columnDefs": [
            {
                "className": "text-center",
                "targets": [1,2]
            }
            , {
                "className": "text-left",
                "targets": [0]
            }, {
                "className": "text-right",
                "targets": []
            }, {
                "className": "text-wrap",
                "targets": [3]
            }
         , ],
        "ajax": { //Solicitud Ajax Servidor
            url: '/Mantenimiento/TipoTarjeta/ListarTipoTarjeta',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
    //Aplicar ordenamiento y autonumeracion , index
    /*tablaTipoTarjeta.on('order.dt search.dt', function () {
        tablaTipoTarjeta.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();*/
}
function NuevoTipoTarjeta() {
     LimpiarTipoTarjeta();
    $("#ModalTipoTarjeta").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalTipoTarjeta").modal("show");
    $("#tituloModalTipoTarjeta").empty();
    $("#tituloModalTipoTarjeta").append("Registro de TipoTarjeta");

}
function EditarTipoTarjeta(idTipoTarjeta) {
     LimpiarTipoTarjeta();
    $("#ModalTipoTarjeta").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalTipoTarjeta").modal("show");
    $("#tituloModalTipoTarjeta").empty();
    $("#tituloModalTipoTarjeta").append("Edición de TipoTarjeta");

    RecuperarTipoTarjeta(idTipoTarjeta);
}
function RecuperarTipoTarjeta(idTipoTarjeta) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/TipoTarjeta/ObtenerTipoTarjeta", {
        "idTipoTarjeta": idTipoTarjeta
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#TipoTarjetaidTipoTarjeta").val(data.idTipoTarjeta);
        $("#TipoTarjetaTitulo").val(data.NombreTarjeta);

    });
}
function EliminarTipoTarjeta(idTipoTarjeta, TipoTarjeta) {
    swal({
        title: "Eliminar Tipo de Tarjeta?",
        text: "Esta seguro de eliminar al Tipo de Tarjeta: " + TipoTarjeta,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/TipoTarjeta/EliminarTipoTarjeta", {
            'idTipoTarjeta': idTipoTarjeta
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaTipoTarjeta.ajax.reload();
            }
        });
    });
}
function HabilitarTipoTarjeta(idTipoTarjeta, TipoTarjeta) {
    swal({
        title: "Habilitar TipoTarjeta?",
        text: "Esta seguro de habilitar al TipoTarjeta: " + TipoTarjeta,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/TipoTarjeta/HabilitarTipoTarjeta", {
            'idTipoTarjeta': idTipoTarjeta
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaTipoTarjeta.ajax.reload();
            }
        });
    });
}
function InabilitarTipoTarjeta(idTipoTarjeta, TipoTarjeta) {
    swal({
        title: "Inhabilitar TipoTarjeta?",
        text: "Esta seguro de inhabilitar al TipoTarjeta: " + TipoTarjeta,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/TipoTarjeta/InhabilitarTipoTarjeta", {
            'idTipoTarjeta': idTipoTarjeta
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaTipoTarjeta.ajax.reload();
            }
        });
    });
}
function LimpiarTipoTarjeta() {
    $('#FormularioTipoTarjeta')[0].reset();
    $("#TipoTarjetaidTipoTarjeta").val("");
}
function Cancelar() {
    LimpiarTipoTarjeta();
    $("#ModalTipoTarjeta").modal("hide");
}

init();
