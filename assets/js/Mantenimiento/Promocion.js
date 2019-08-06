var tablaPromocion;
var Filter;
var Cantidad = 1

function init() {
    uploadImage();
    Iniciar_Componentes();
    Listar_Promocion();

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

    $("#FormularioPromocion").on("submit", function (e) {
        RegistroPromocion(e);
    });

}


function RegistroPromocion(event) {
    event.preventDefault();

    var imagenes = RecuperarImagenes();
    var imagenesBr = imagenes.join("|");
    var formData = new FormData($("#FormularioPromocion")[0]);
    formData.append("Imagenes", imagenesBr);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Promocion/InsertUpdatePromocion",
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
                $('#ModalPromocion').modal('hide');
                mensaje_success(Mensaje)
                LimpiarPromocion();
                tablaPromocion.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {

        }
    });
}

function Listar_Promocion() {
    tablaPromocion = $('#tablaPromocion').dataTable({
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
            url: '/Mantenimiento/Promocion/ListarPromocion',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function NuevoPromocion() {
    $("#ModalPromocion").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalPromocion").modal("show");
    $("#tituloModalPromocion").empty();
    $("#tituloModalPromocion").append("Registro de Promocion");

}

function EditarPromocion(idPromocion) {
    $("#ModalPromocion").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalPromocion").modal("show");
    $("#tituloModalPromocion").empty();
    $("#tituloModalPromocion").append("Edición de Promocion");

    RecuperarPromocion(idPromocion);
}

function RecuperarPromocion(idPromocion) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Promocion/ObtenerPromocion", {
        "idPromocion": idPromocion
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#PromocionidPromocion").val(data.idPromocion);
        $("#PromocionTitulo").val(data.NombrePromocion);
        $("#PromocionLink").val(data.linkPromocion);

        if (data.imagenPromocion != null && data.imagenPromocion != "") {
            //Recuperando 1 Imagen
            var images = $('.images');
            images.prepend('<div class="img" style="background-image: url(\'' + data.imagenPromocion + '\');" rel="' + data.imagenPromocion + '"><span>remove</span></div>');
        }

    });
}

function EliminarPromocion(idPromocion, Promocion) {
    swal({
        title: "Eliminar Promocion?",
        text: "Esta seguro de eliminar al Promocion: " + Promocion,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Promocion/EliminarPromocion", {
            'idPromocion': idPromocion
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaPromocion.ajax.reload();
            }
        });
    });
}

function HabilitarPromocion(idPromocion, Promocion) {
    swal({
        title: "Habilitar Promocion?",
        text: "Esta seguro de habilitar al Promocion: " + Promocion,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Promocion/HabilitarPromocion", {
            'idPromocion': idPromocion
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaPromocion.ajax.reload();
            }
        });
    });
}

function InabilitarPromocion(idPromocion, Promocion) {
    swal({
        title: "Inhabilitar Promocion?",
        text: "Esta seguro de inhabilitar al Promocion: " + Promocion,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Promocion/InhabilitarPromocion", {
            'idPromocion': idPromocion
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaPromocion.ajax.reload();
            }
        });
    });
}

function LimpiarPromocion() {
    $('#FormularioPromocion')[0].reset();
    $("#PromocionidPromocion").val("");
    $('#Archivos').empty();
    resetUpload();
}

function Cancelar() {
    LimpiarPromocion();
    $("#ModalPromocion").modal("hide");
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


function SubPromocions(idPromocion) {
    $.redirect('/Mantenimiento/SubPromocion/', {
        'idPromocion': idPromocion
    });
}

init();
