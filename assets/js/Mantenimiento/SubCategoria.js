var tablaSubCategoria;
var Filter;
var Cantidad = 1

function init() {
    var idCategoria = $("#idCategoria").val();
    uploadImage();
    Iniciar_Componentes();
    Listar_SubCategoria(idCategoria);
    RecuperarCategoria(idCategoria);
}

function RecuperarCategoria(idCategoria) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Categoria/ObtenerCategoria", {
        "idCategoria": idCategoria
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#NameCategoria").empty();
        $("#NameCategoria").html(data.NombreCategoria);
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

function ListarGrupos() {
    $.post("/Mantenimiento/SubCategoria/ListarGrupo", function (ts) {
        $("#SubCategoriaGrupo").empty();
        $("#SubCategoriaGrupo").append(ts);
    });
}

function Iniciar_Componentes() {

    $("#FormularioSubCategoria").on("submit", function (e) {
        RegistroSubCategoria(e);
    });

    $("#PortadaSubCategoria").change(function () {
        readURL(this);
    });


}

function readURL(input) {
    if (input.files.length == 1) {
        for (var i = 0; i < input.files.length; i++) {
            var reader = new FileReader();


            reader.onloadend = (function (file) {
                return function (e) {
                    var nombre = file.name.replace(" ", "-");
                    var item =
                        '<div class="row align-items-center" id="' + nombre + '">' +
                        '<div class="col-sm-4 col-md-4 text-center" style="height:120px;">' +
                        '<img class="imageUpload"  style="background-image: url(' + e.target.result + ');">' +
                        '</div>' +
                        '<div class="col-sm-4 col-md-4 text-center">' +
                        '<label class="Medida"><h5>' + file.name + '</h5></label>' +
                        '</div>' +
                        '<div class="col-sm-4 col-md-4 text-center">' +
                        '<label for="" class="btn btn-danger btn-sm btn-round" onclick="EliminarImage(\'' + nombre + '\')"><i class="fa fa-trash"></i>' + '</label>' +
                        '</div></div><br>';
                    if ($('#Archivos').children().length > 0) {
                        $('#Archivos').children().last().after(item);
                    } else {
                        $('#Archivos').html(item);
                    }
                };
            })(input.files[i]);
            reader.readAsDataURL(input.files[i]);
        }
    } else {
        mensaje_warning("Debe Seleccionar solo 1 Imagen de Portada");
    }

}

function RegistroSubCategoria(event) {
    event.preventDefault();
    var idCategoria = $("#idCategoria").val();
    var imagenes = RecuperarImagenes();
    var imagenesBr = imagenes.join("|");
    var formData = new FormData($("#FormularioSubCategoria")[0]);
    formData.append("Imagenes", imagenesBr);
    formData.append("idCategoria", idCategoria);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/SubCategoria/InsertUpdateSubCategoria",
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
            } else {debugger;
                tablaSubCategoria.ajax.reload();
                $('#ModalSubCategoria').modal('hide');
                mensaje_success(Mensaje)
                LimpiarSubCategoria();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {

        }
    });
}

function Listar_SubCategoria(idCategoria) {
    console.log(idCategoria);
    tablaSubCategoria = $('#tablaSubCategoria').dataTable({
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
            url: '/Mantenimiento/SubCategoria/ListarSubCategoria',
            type: "POST",
            dataType: "JSON",
            data: {
                idCategoria: idCategoria
            },
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function NuevoSubCategoria() {
    $("#ModalSubCategoria").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalSubCategoria").modal("show");
    $("#tituloModalSubCategoria").empty();
    $("#tituloModalSubCategoria").append("Registro de SubCategoria");

}

function EditarSubCategoria(idSubCategoria) {
    $("#ModalSubCategoria").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalSubCategoria").modal("show");
    $("#tituloModalSubCategoria").empty();
    $("#tituloModalSubCategoria").append("Edición de SubCategoria");

    RecuperarSubCategoria(idSubCategoria);
}

function RecuperarSubCategoria(idSubCategoria) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/SubCategoria/ObtenerSubCategoria", {
        "idSubCategoria": idSubCategoria
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#SubCategoriaidSubCategoria").val(data.idSubCategoria);
        $("#SubCategoriaTitulo").val(data.NombreSubCategoria);
        $("#SubCategoriaDescripcion").val(data.Descripcion);

        if (data.imagenPortada != null) {
            //Recuperando 1 Imagen
            var images = $('.images');
            images.prepend('<div class="img" style="background-image: url(\'' + data.imagenPortada + '\');" rel="' + data.imagenPortada + '"><span>remove</span></div>');
        }


    });
}

function EliminarSubCategoria(idSubCategoria, SubCategoria) {
    swal({
        title: "Eliminar SubCategoria?",
        text: "Esta seguro de eliminar al SubCategoria: " + SubCategoria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/SubCategoria/EliminarSubCategoria", {
            'idSubCategoria': idSubCategoria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaSubCategoria.ajax.reload();
            }
        });
    });
}

function HabilitarSubCategoria(idSubCategoria, SubCategoria) {
    swal({
        title: "Habilitar SubCategoria?",
        text: "Esta seguro de habilitar al SubCategoria: " + SubCategoria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/SubCategoria/HabilitarSubCategoria", {
            'idSubCategoria': idSubCategoria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaSubCategoria.ajax.reload();
            }
        });
    });
}

function InabilitarSubCategoria(idSubCategoria, SubCategoria) {
    swal({
        title: "Inhabilitar SubCategoria?",
        text: "Esta seguro de inhabilitar al SubCategoria: " + SubCategoria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/SubCategoria/InhabilitarSubCategoria", {
            'idSubCategoria': idSubCategoria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaSubCategoria.ajax.reload();
            }
        });
    });
}

function LimpiarSubCategoria() {
    $('#FormularioSubCategoria')[0].reset();
    $("#SubCategoriaidSubCategoria").val("");
    $('#Archivos').empty();
    resetUpload();
}

function Cancelar() {
    LimpiarSubCategoria();
    $("#ModalSubCategoria").modal("hide");
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
