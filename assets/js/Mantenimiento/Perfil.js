var tablaPerfil;

function init() {
    Iniciar_Componentes();
    Listar_Perfil();

}

function Iniciar_Componentes() {
    $("#FormularioPerfil").on("submit", function (e) {
        RegistroPerfil(e);
    });
}
function RegistroPerfil(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioPerfil")[0]);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Perfil/InsertUpdatePerfil",
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
                $('#ModalPerfil').modal('hide');
                mensaje_success(Mensaje)
                LimpiarPerfil();
                tablaPerfil.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}
function Listar_Perfil() {
    tablaPerfil = $('#tablaPerfil').dataTable({
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
            url: '/Mantenimiento/Perfil/ListarPerfil',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
    //Aplicar ordenamiento y autonumeracion , index
    /*tablaPerfil.on('order.dt search.dt', function () {
        tablaPerfil.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();*/
}
function NuevoPerfil() {
    $("#ModalPerfil").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalPerfil").modal("show");
    $("#tituloModalPerfil").empty();
    $("#tituloModalPerfil").append("Registro de Perfil");

}
function EditarPerfil(idPerfil) {
    $("#ModalPerfil").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalPerfil").modal("show");
    $("#tituloModalPerfil").empty();
    $("#tituloModalPerfil").append("Edición de Perfil");

    RecuperarPerfil(idPerfil);
}
function RecuperarPerfil(idPerfil) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Perfil/ObtenerPerfil", {
        "idPerfil": idPerfil
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#PerfilidPerfil").val(data.idPerfil);
        $("#PerfilTitulo").val(data.DescripcionPerfil);

    });
}
function EliminarPerfil(idPerfil, Perfil) {
    swal({
        title: "Eliminar Perfil?",
        text: "Esta seguro de eliminar al Perfil: " + Perfil,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Perfil/EliminarPerfil", {
            'idPerfil': idPerfil
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaPerfil.ajax.reload();
            }
        });
    });
}
function HabilitarPerfil(idPerfil, Perfil) {
    swal({
        title: "Habilitar Perfil?",
        text: "Esta seguro de habilitar al Perfil: " + Perfil,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Perfil/HabilitarPerfil", {
            'idPerfil': idPerfil
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaPerfil.ajax.reload();
            }
        });
    });
}
function InabilitarPerfil(idPerfil, Perfil) {
    swal({
        title: "Inhabilitar Perfil?",
        text: "Esta seguro de inhabilitar al Perfil: " + Perfil,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Perfil/InhabilitarPerfil", {
            'idPerfil': idPerfil
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaPerfil.ajax.reload();
            }
        });
    });
}
function LimpiarPerfil() {
    $('#FormularioPerfil')[0].reset();
    $("#idPerfil").val("");
}
function Cancelar() {
    LimpiarPerfil();
    $("#ModalPerfil").modal("hide");
}

init();
