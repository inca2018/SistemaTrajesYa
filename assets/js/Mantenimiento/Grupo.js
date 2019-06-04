var tablaGrupo;

function init() {
    Iniciar_Componentes();
    Listar_Grupo();

}

function Iniciar_Componentes() {
    $("#FormularioGrupo").on("submit", function (e) {
        RegistroGrupo(e);
    });
}
function RegistroGrupo(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioGrupo")[0]);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Grupo/InsertUpdateGrupo",
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
                $('#ModalGrupo').modal('hide');
                mensaje_success(Mensaje)
                LimpiarGrupo();
                tablaGrupo.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}
function Listar_Grupo() {
    tablaGrupo = $('#tablaGrupo').dataTable({
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
            url: '/Mantenimiento/Grupo/ListarGrupo',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
    //Aplicar ordenamiento y autonumeracion , index
    /*tablaGrupo.on('order.dt search.dt', function () {
        tablaGrupo.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();*/
}
function NuevoGrupo() {
    $("#ModalGrupo").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalGrupo").modal("show");
    $("#tituloModalGrupo").empty();
    $("#tituloModalGrupo").append("Registro de Grupo");

}
function EditarGrupo(idGrupo) {
    $("#ModalGrupo").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalGrupo").modal("show");
    $("#tituloModalGrupo").empty();
    $("#tituloModalGrupo").append("Edición de Grupo");

    RecuperarGrupo(idGrupo);
}
function RecuperarGrupo(idGrupo) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Grupo/ObtenerGrupo", {
        "idGrupo": idGrupo
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#GrupoidGrupo").val(data.idGrupo);
        $("#GrupoTitulo").val(data.Descripcion);

    });
}
function EliminarGrupo(idGrupo, Grupo) {
    swal({
        title: "Eliminar Grupo?",
        text: "Esta seguro de eliminar al Grupo: " + Grupo,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Grupo/EliminarGrupo", {
            'idGrupo': idGrupo
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaGrupo.ajax.reload();
            }
        });
    });
}
function HabilitarGrupo(idGrupo, Grupo) {
    swal({
        title: "Habilitar Grupo?",
        text: "Esta seguro de habilitar al Grupo: " + Grupo,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Grupo/HabilitarGrupo", {
            'idGrupo': idGrupo
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaGrupo.ajax.reload();
            }
        });
    });
}
function InabilitarGrupo(idGrupo, Grupo) {
    swal({
        title: "Inhabilitar Grupo?",
        text: "Esta seguro de inhabilitar al Grupo: " + Grupo,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Grupo/InhabilitarGrupo", {
            'idGrupo': idGrupo
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaGrupo.ajax.reload();
            }
        });
    });
}
function LimpiarGrupo() {
    $('#FormularioGrupo')[0].reset();
    $("#idGrupo").val("");
}
function Cancelar() {
    LimpiarGrupo();
    $("#ModalGrupo").modal("hide");
}

init();
