var tablaPublicidad;
var Filter;
var Cantidad = 1

function init() {
    uploadImage();
    Iniciar_Componentes();
    Listar_Publicidad();

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

    $("#FormularioPublicidad").on("submit", function (e) {
        RegistroPublicidad(e);
    });

}


function RegistroPublicidad(event) {
    event.preventDefault();

    var imagenes = RecuperarImagenes();
    var imagenesBr = imagenes.join("|");
    var formData = new FormData($("#FormularioPublicidad")[0]);
    formData.append("Imagenes", imagenesBr);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Publicidad/InsertUpdatePublicidad",
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
                $('#ModalPublicidad').modal('hide');
                mensaje_success(Mensaje)
                LimpiarPublicidad();
                tablaPublicidad.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {

        }
    });
}

function Listar_Publicidad() {
    tablaPublicidad = $('#tablaPublicidad').dataTable({
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
            url: '/Mantenimiento/Publicidad/ListarPublicidad',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function NuevoPublicidad() {
    $("#ModalPublicidad").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalPublicidad").modal("show");
    $("#tituloModalPublicidad").empty();
    $("#tituloModalPublicidad").append("Registro de Publicidad");

}

function EditarPublicidad(idPublicidad) {
    $("#ModalPublicidad").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalPublicidad").modal("show");
    $("#tituloModalPublicidad").empty();
    $("#tituloModalPublicidad").append("Edición de Publicidad");

    RecuperarPublicidad(idPublicidad);
}

function RecuperarPublicidad(idPublicidad) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Publicidad/ObtenerPublicidad", {
        "idPublicidad": idPublicidad
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#PublicidadidPublicidad").val(data.idPublicidad);
        $("#PublicidadTitulo").val(data.NombrePublicidad);
        $("#PublicidadLink").val(data.linkPublicidad);

        if (data.imagenPublicidad != null && data.imagenPublicidad != "") {
            //Recuperando 1 Imagen
            var images = $('.images');
            images.prepend('<div class="img" style="background-image: url(\'' + data.imagenPublicidad + '\');" rel="' + data.imagenPublicidad + '"><span>remove</span></div>');
        }

    });
}

function EliminarPublicidad(idPublicidad, Publicidad) {
    swal({
        title: "Eliminar Publicidad?",
        text: "Esta seguro de eliminar al Publicidad: " + Publicidad,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Publicidad/EliminarPublicidad", {
            'idPublicidad': idPublicidad
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaPublicidad.ajax.reload();
            }
        });
    });
}

function HabilitarPublicidad(idPublicidad, Publicidad) {
    swal({
        title: "Habilitar Publicidad?",
        text: "Esta seguro de habilitar al Publicidad: " + Publicidad,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Publicidad/HabilitarPublicidad", {
            'idPublicidad': idPublicidad
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaPublicidad.ajax.reload();
            }
        });
    });
}

function InabilitarPublicidad(idPublicidad, Publicidad) {
    swal({
        title: "Inhabilitar Publicidad?",
        text: "Esta seguro de inhabilitar al Publicidad: " + Publicidad,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Publicidad/InhabilitarPublicidad", {
            'idPublicidad': idPublicidad
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaPublicidad.ajax.reload();
            }
        });
    });
}

function LimpiarPublicidad() {
    $('#FormularioPublicidad')[0].reset();
    $("#PublicidadidPublicidad").val("");
    $('#Archivos').empty();
    resetUpload();
}

function Cancelar() {
    LimpiarPublicidad();
    $("#ModalPublicidad").modal("hide");
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
