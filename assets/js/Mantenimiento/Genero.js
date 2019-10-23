var tablaGenero;

function init() {
    Iniciar_Componentes();
    Listar_Genero();

}

function Iniciar_Componentes() {
    $("#FormularioGenero").on("submit", function (e) {
        RegistroGenero(e);
    });
}
function RegistroGenero(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioGenero")[0]);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Genero/InsertUpdateGenero",
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
                $('#ModalGenero').modal('hide');
                mensaje_success(Mensaje)
                LimpiarGenero();
                tablaGenero.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}
function Listar_Genero() {
    tablaGenero = $('#tablaGenero').dataTable({
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
            url: '/Mantenimiento/Genero/ListarGenero',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
    //Aplicar ordenamiento y autonumeracion , index
    /*tablaGenero.on('order.dt search.dt', function () {
        tablaGenero.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();*/
}
function NuevoGenero() {
    $("#ModalGenero").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalGenero").modal("show");
    $("#tituloModalGenero").empty();
    $("#tituloModalGenero").append("Registro de Genero");

}
function EditarGenero(idGenero) {
    $("#ModalGenero").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalGenero").modal("show");
    $("#tituloModalGenero").empty();
    $("#tituloModalGenero").append("Edición de Genero");

    RecuperarGenero(idGenero);
}
function RecuperarGenero(idGenero) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Genero/ObtenerGenero", {
        "idGenero": idGenero
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#GeneroidGenero").val(data.idGenero);
        $("#GeneroTitulo").val(data.NombreGenero);
        $("#GeneroSimbolo").val(data.simbolo);

    });
}
function EliminarGenero(idGenero, Genero) {
    swal({
        title: "Eliminar Genero?",
        text: "Esta seguro de eliminar al Genero: " + Genero,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Genero/EliminarGenero", {
            'idGenero': idGenero
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaGenero.ajax.reload();
            }
        });
    });
}
function HabilitarGenero(idGenero, Genero) {
    swal({
        title: "Habilitar Genero?",
        text: "Esta seguro de habilitar al Genero: " + Genero,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Genero/HabilitarGenero", {
            'idGenero': idGenero
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaGenero.ajax.reload();
            }
        });
    });
}
function InabilitarGenero(idGenero, Genero) {
    swal({
        title: "Inhabilitar Genero?",
        text: "Esta seguro de inhabilitar al Genero: " + Genero,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Genero/InhabilitarGenero", {
            'idGenero': idGenero
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaGenero.ajax.reload();
            }
        });
    });
}
function LimpiarGenero() {
    $('#FormularioGenero')[0].reset();
    $("#GeneroidGenero").val("");
}
function Cancelar() {
    LimpiarGenero();
    $("#ModalGenero").modal("hide");
}

init();
