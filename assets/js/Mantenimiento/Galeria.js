var tablaGaleria;
var Filter;
var Cantidad = 1

function init() {
    var idProducto = $("#idProducto").val();
    RecuperarProducto(idProducto);
    uploadImage();
    Iniciar_Componentes();
    Listar_Galeria(idProducto);
}

function RecuperarProducto(idProducto) {
    $.post("/Mantenimiento/Producto/ObtenerProducto", {
        "idProducto": idProducto
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
          $("#NameProducto").empty();
          $("#NameProducto").append(data.NombreProducto);
    });
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

    $("#FormularioGaleria").on("submit", function (e) {
        RegistroGaleria(e);
    });

}

function RegistroGaleria(event) {
    event.preventDefault();

    var images = $('.images .img')
    if (images.length == 0) {
        mensaje_warning("¡Debe Seleccionar una imagen!")
    } else {
        var idProducto = $("#idProducto").val();

        var imagenes = RecuperarImagenes();
        var imagenesBr = imagenes.join("|");
        var formData = new FormData($("#FormularioGaleria")[0]);
        formData.append("Imagenes", imagenesBr);
        formData.append("idProducto", idProducto);
        console.log(formData);
        $.ajax({
            url: "/Mantenimiento/Galeria/InsertUpdateGaleria",
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
                    $('#ModalGaleria').modal('hide');
                    mensaje_success(Mensaje)
                    LimpiarGaleria();
                    tablaGaleria.ajax.reload();
                }
            },
            error: function (e) {
                console.log(e.responseText);
            },
            complete: function () {

            }
        });
    }


}

function Listar_Galeria(idProducto) {
    tablaGaleria = $('#tablaGaleria').dataTable({
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
            url: '/Mantenimiento/Galeria/ListarGaleria',
            type: "POST",
            dataType: "JSON",
            data: {
                idProducto: idProducto
            },
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function NuevoGaleria() {
    $("#ModalGaleria").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalGaleria").modal("show");
    $("#tituloModalGaleria").empty();
    $("#tituloModalGaleria").append("Registro de Imagen");

}

function EditarGaleria(idGaleria) {
    $("#ModalGaleria").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalGaleria").modal("show");
    $("#tituloModalGaleria").empty();
    $("#tituloModalGaleria").append("Edición de Imagen");

    RecuperarGaleria(idGaleria);
}

function RecuperarGaleria(idGaleria) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Galeria/ObtenerGaleria", {
        "idGaleria": idGaleria
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#GaleriaidGaleria").val(data.idGaleria);
        $("#GaleriaTitulo").val(data.NombreGaleria);

            if (data.imagenPortada != null && data.imagenPortada != "") {
                //Recuperando 1 Imagen
                var images = $('.images');
                images.prepend('<div class="img" style="background-image: url(\'' + data.imagenPortada + '\');" rel="' + data.imagenPortada + '"><span>remove</span></div>');
            }

    });
}

function EliminarGaleria(idGaleria, Galeria) {
    swal({
        title: "Eliminar Galeria?",
        text: "Esta seguro de eliminar al Galeria: " + Galeria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Galeria/EliminarGaleria", {
            'idGaleria': idGaleria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaGaleria.ajax.reload();
            }
        });
    });
}

function HabilitarGaleria(idGaleria, Galeria) {
    swal({
        title: "Habilitar Galeria?",
        text: "Esta seguro de habilitar al Galeria: " + Galeria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Galeria/HabilitarGaleria", {
            'idGaleria': idGaleria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaGaleria.ajax.reload();
            }
        });
    });
}

function InabilitarGaleria(idGaleria, Galeria) {
    swal({
        title: "Inhabilitar Galeria?",
        text: "Esta seguro de inhabilitar al Galeria: " + Galeria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Galeria/InhabilitarGaleria", {
            'idGaleria': idGaleria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaGaleria.ajax.reload();
            }
        });
    });
}

function LimpiarGaleria() {
    $('#FormularioGaleria')[0].reset();
    $("#GaleriaidGaleria").val("");
    $('#Archivos').empty();
    resetUpload();
}

function Cancelar() {
    LimpiarGaleria();
    $("#ModalGaleria").modal("hide");
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


function SubGalerias(idGaleria) {
    $.redirect('/Mantenimiento/SubGaleria/', {
        'idGaleria': idGaleria
    });
}

init();
