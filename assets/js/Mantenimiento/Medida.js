var tablaMedida;

function init() {
    Iniciar_Componentes();
    Listar_Medida();

}

function Iniciar_Componentes() {
    $("#FormularioMedida").on("submit", function (e) {
        RegistroMedida(e);
    });
}
function RegistroMedida(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioMedida")[0]);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Medida/InsertUpdateMedida",
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
                $('#ModalMedida').modal('hide');
                mensaje_success(Mensaje)
                LimpiarMedida();
                tablaMedida.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}
function Listar_Medida() {
    tablaMedida = $('#tablaMedida').dataTable({
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
            url: '/Mantenimiento/Medida/ListarMedida',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
    //Aplicar ordenamiento y autonumeracion , index
    /*tablaMedida.on('order.dt search.dt', function () {
        tablaMedida.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();*/
}
function NuevoMedida() {
    $("#ModalMedida").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalMedida").modal("show");
    $("#tituloModalMedida").empty();
    $("#tituloModalMedida").append("Registro de Medida");

}
function EditarMedida(idMedida) {
    $("#ModalMedida").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalMedida").modal("show");
    $("#tituloModalMedida").empty();
    $("#tituloModalMedida").append("Edición de Medida");

    RecuperarMedida(idMedida);
}
function RecuperarMedida(idMedida) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Medida/ObtenerMedida", {
        "idMedida": idMedida
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#MedidaidMedida").val(data.idMedida);
        $("#MedidaTitulo").val(data.NombreMedida);

    });
}
function EliminarMedida(idMedida, Medida) {
    swal({
        title: "Eliminar Medida?",
        text: "Esta seguro de eliminar al Medida: " + Medida,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Medida/EliminarMedida", {
            'idMedida': idMedida
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaMedida.ajax.reload();
            }
        });
    });
}
function HabilitarMedida(idMedida, Medida) {
    swal({
        title: "Habilitar Medida?",
        text: "Esta seguro de habilitar al Medida: " + Medida,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Medida/HabilitarMedida", {
            'idMedida': idMedida
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaMedida.ajax.reload();
            }
        });
    });
}
function InabilitarMedida(idMedida, Medida) {
    swal({
        title: "Inhabilitar Medida?",
        text: "Esta seguro de inhabilitar al Medida: " + Medida,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Medida/InhabilitarMedida", {
            'idMedida': idMedida
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaMedida.ajax.reload();
            }
        });
    });
}
function LimpiarMedida() {
    $('#FormularioMedida')[0].reset();
    $("#idMedida").val("");
}
function Cancelar() {
    LimpiarMedida();
    $("#ModalMedida").modal("hide");
}

init();
