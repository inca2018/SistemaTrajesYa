var tablaArea;

function init() {
    Iniciar_Componentes();
    Listar_Area();

}

function Iniciar_Componentes() {
    $("#FormularioArea").on("submit", function (e) {
        RegistroArea(e);
    });
}
function RegistroArea(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioArea")[0]);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Area/InsertUpdateArea",
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
                $('#ModalArea').modal('hide');
                mensaje_success(Mensaje)
                LimpiarArea();
                tablaArea.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}
function Listar_Area() {
    tablaArea = $('#tablaArea').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "processing": true,
        "paging": false, // Paginacion en tabla
        "ordering": true, // Ordenamiento en columna de tabla
        "info": true, // Informacion de cabecera tabla
        "responsive": true, // Accion de responsive
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[0, "asc"]],
        "bDestroy": true,
        "columnDefs": [
            {
                "className": "text-center",
                "targets": [2]
            }
            , {
                "className": "text-left",
                "targets": [0]
            }, {
                "className": "text-right",
                "targets": []
            }, {
                "className": "text-wrap",
                "targets": [1]
            }
         , ],
        "ajax": { //Solicitud Ajax Servidor
            url: '/Mantenimiento/Area/ListarArea',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();


}
function NuevoArea() {
    $("#ModalArea").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalArea").modal("show");
    $("#tituloModalArea").empty();
    $("#tituloModalArea").append("Registro de Area");

}
function EditarArea(idArea) {
    $("#ModalArea").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalArea").modal("show");
    $("#tituloModalArea").empty();
    $("#tituloModalArea").append("Edición de Area");

    RecuperarArea(idArea);
}
function RecuperarArea(idArea) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Area/ObtenerArea", {
        "idArea": idArea
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#idArea").val(data.idArea);
        $("#AreaTitulo").val(data.Descripcion);

    });
}
function EliminarArea(idArea, Area) {
    swal({
        title: "Eliminar Area?",
        text: "Esta seguro de eliminar al Area: " + Area,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Area/EliminarArea", {
            'idArea': idArea
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaArea.ajax.reload();
            }
        });
    });
}
function HabilitarArea(idArea, Area) {
    swal({
        title: "Habilitar Area?",
        text: "Esta seguro de habilitar al Area: " + Area,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Area/HabilitarArea", {
            'idArea': idArea
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaArea.ajax.reload();
            }
        });
    });
}
function InabilitarArea(idArea, Area) {
    swal({
        title: "Inhabilitar Area?",
        text: "Esta seguro de inhabilitar al Area: " + Area,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Area/InhabilitarArea", {
            'idArea': idArea
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaArea.ajax.reload();
            }
        });
    });
}
function LimpiarArea() {
    $('#FormularioArea')[0].reset();
    $("#idArea").val("");
}
function Cancelar() {
    LimpiarArea();
    $("#ModalArea").modal("hide");
}
function SubAreas(idArea){
     $.redirect('/Mantenimiento/SubArea/', {
        'idArea': idArea
    });
}
init();
