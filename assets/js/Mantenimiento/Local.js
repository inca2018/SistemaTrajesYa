var tablaLocal;
var Filter;
var Cantidad = 1

function init() {
    uploadImage();
    Iniciar_Componentes();
    Listar_Local();
}

function uploadImage() {
    //Recuperar el Agregador
    var button = $('.images .pic')
    //Crear input
    var uploader = $('<input type="file" accept="image/*" />')
    //Recuperar Imagenes adjuntadas
    var images = $('.images')
    button.on('click', function () {
        var images = $('.images .img')
        if (images.length >= Cantidad) {
            mensaje_warning("Solo puede Seleccionar " + Cantidad + " Imagen(es).")
        } else {
            uploader.click()
        }

    })
    uploader.on('change', function () {
        var reader = new FileReader()
        reader.onload = function (event) {
            images.prepend('<div class="img" style="background-image: url(\'' + event.target.result + '\');" rel="' + event.target.result + '"><span>remove</span></div>')
        }
        reader.readAsDataURL(uploader[0].files[0])
    })
    images.on('click', '.img', function () {
        $(this).remove()
    })
}


function Iniciar_Componentes() {

    $("#FormularioLocal").on("submit", function (e) {
        RegistroLocal(e);
    });

}


function RegistroLocal(event) {
    event.preventDefault();

    var imagenes = RecuperarImagenes();
    var imagenesBr = imagenes.join("|");
    var formData = new FormData($("#FormularioLocal")[0]);
    formData.append("Imagenes", imagenesBr);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Local/InsertUpdateLocal",
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
                $('#ModalLocal').modal('hide');
                mensaje_success(Mensaje)
                LimpiarLocal();
                tablaLocal.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {

        }
    });
}

function Listar_Local() {
    tablaLocal = $('#tablaLocal').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "processing": true,
        "paging": true, // Paginacion en tabla
        "ordering": true, // Ordenamiento en columna de tabla
        "info": true, // Informacion de cabecera tabla
        "responsive": true, // Accion de responsive
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[3, "asc"]],
        "bDestroy": true,
        "columnDefs": [
            {
                "className": "text-center",
                "targets": [1, 2]
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
            url: '/Mantenimiento/Local/ListarLocal',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function NuevoLocal() {
    $("#ModalLocal").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalLocal").modal("show");
    $("#tituloModalLocal").empty();
    $("#tituloModalLocal").append("Registro de Local");

}

function EditarLocal(idLocal) {
    $("#ModalLocal").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalLocal").modal("show");
    $("#tituloModalLocal").empty();
    $("#tituloModalLocal").append("Edición de Local");

    RecuperarLocal(idLocal);
}

function RecuperarLocal(idLocal) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Local/ObtenerLocal", {
        "idLocal": idLocal
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#LocalidLocal").val(data.idLocal);
        $("#LocalTitulo").val(data.NombreLocal);
        $("#LocalDireccion").val(data.Direccion);
        $("#LocalEncargado").val(data.Encargado);
        $("#LocalHorarioAtencion").val(data.HorarioAtencion);
        $("#LocalFijo").val(data.TelefonoFijo);
        $("#LocalCelular").val(data.TelefonoCelular);

            if (data.imagenPortada != null && data.imagenPortada!="") {
                //Recuperando 1 Imagen
                var images = $('.images');
                images.prepend('<div class="img" style="background-image: url(\'' + data.imagenPortada + '\');" rel="' + data.imagenPortada + '"><span>remove</span></div>');
            }

    });
}

function EliminarLocal(idLocal, Local) {
    swal({
        title: "Eliminar Local?",
        text: "Esta seguro de eliminar al Local: " + Local,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Local/EliminarLocal", {
            'idLocal': idLocal
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaLocal.ajax.reload();
            }
        });
    });
}

function HabilitarLocal(idLocal, Local) {
    swal({
        title: "Habilitar Local?",
        text: "Esta seguro de habilitar al Local: " + Local,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Local/HabilitarLocal", {
            'idLocal': idLocal
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaLocal.ajax.reload();
            }
        });
    });
}

function InabilitarLocal(idLocal, Local) {
    swal({
        title: "Inhabilitar Local?",
        text: "Esta seguro de inhabilitar al Local: " + Local,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Local/InhabilitarLocal", {
            'idLocal': idLocal
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaLocal.ajax.reload();
            }
        });
    });
}

function LimpiarLocal() {
    $('#FormularioLocal')[0].reset();
    $("#LocalidLocal").val("");
    $('#Archivos').empty();
    resetUpload();
}

function Cancelar() {
    LimpiarLocal();
    $("#ModalLocal").modal("hide");
    resetUpload();
}

function resetUpload() {
    var images = $('.images .img')
    for (var i = 0; i < images.length; i++) {
        $(images)[i].remove()
    }
}

function RecuperarImagenes() {

    var images = $('.images .img');
    var imageArr = [];

    for (var i = 0; i < images.length; i++) {
        imageArr.push($(images[i]).attr('rel'));
    }

    return imageArr;
}



init();
