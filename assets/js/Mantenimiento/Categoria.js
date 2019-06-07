var tablaCategoria;
var Filter;
var Cantidad = 1

function init() {
    uploadImage();
    Iniciar_Componentes();
    Listar_Categoria();
    ListarGrupos();
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
    $.post("/Mantenimiento/Categoria/ListarGrupo", function (ts) {
        $("#CategoriaGrupo").empty();
        $("#CategoriaGrupo").append(ts);
    });
}

function Iniciar_Componentes() {

    $("#FormularioCategoria").on("submit", function (e) {
        RegistroCategoria(e);
    });

    $("#PortadaCategoria").change(function () {
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

function RegistroCategoria(event) {
    event.preventDefault();

    var imagenes = RecuperarImagenes();
    var imagenesBr = imagenes.join("|");
    var formData = new FormData($("#FormularioCategoria")[0]);
    formData.append("Imagenes", imagenesBr);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Categoria/InsertUpdateCategoria",
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
                $('#ModalCategoria').modal('hide');
                mensaje_success(Mensaje)
                LimpiarCategoria();
                tablaCategoria.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {

        }
    });
}

function Listar_Categoria() {
    tablaCategoria = $('#tablaCategoria').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "processing": true,
        "paging": true, // Paginacion en tabla
        "ordering": true, // Ordenamiento en columna de tabla
        "info": true, // Informacion de cabecera tabla
        "responsive": true, // Accion de responsive
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[4, "asc"]],
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
            url: '/Mantenimiento/Categoria/ListarCategoria',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function NuevoCategoria() {
    $("#ModalCategoria").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalCategoria").modal("show");
    $("#tituloModalCategoria").empty();
    $("#tituloModalCategoria").append("Registro de Categoria");

}

function EditarCategoria(idCategoria) {
    $("#ModalCategoria").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalCategoria").modal("show");
    $("#tituloModalCategoria").empty();
    $("#tituloModalCategoria").append("Edición de Categoria");

    RecuperarCategoria(idCategoria);
}

function RecuperarCategoria(idCategoria) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Categoria/ObtenerCategoria", {
        "idCategoria": idCategoria
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#CategoriaidCategoria").val(data.idCategoria);
        $("#CategoriaTitulo").val(data.NombreCategoria);
        $("#CategoriaDescripcion").val(data.Descripcion);
        $.post("/Mantenimiento/Categoria/ListarGrupo", function (ts) {
            $("#CategoriaGrupo").empty();
            $("#CategoriaGrupo").append(ts);
            $("#CategoriaGrupo").val(data.Grupo_idGrupo);

            if (data.imagenPortada != null) {
                //Recuperando 1 Imagen
                var images = $('.images');
                images.prepend('<div class="img" style="background-image: url(\'' + data.imagenPortada + '\');" rel="' + data.imagenPortada + '"><span>remove</span></div>');
            }


        });
    });
}

function EliminarCategoria(idCategoria, Categoria) {
    swal({
        title: "Eliminar Categoria?",
        text: "Esta seguro de eliminar al Categoria: " + Categoria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Categoria/EliminarCategoria", {
            'idCategoria': idCategoria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaCategoria.ajax.reload();
            }
        });
    });
}

function HabilitarCategoria(idCategoria, Categoria) {
    swal({
        title: "Habilitar Categoria?",
        text: "Esta seguro de habilitar al Categoria: " + Categoria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Categoria/HabilitarCategoria", {
            'idCategoria': idCategoria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaCategoria.ajax.reload();
            }
        });
    });
}

function InabilitarCategoria(idCategoria, Categoria) {
    swal({
        title: "Inhabilitar Categoria?",
        text: "Esta seguro de inhabilitar al Categoria: " + Categoria,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Categoria/InhabilitarCategoria", {
            'idCategoria': idCategoria
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaCategoria.ajax.reload();
            }
        });
    });
}

function LimpiarCategoria() {
    $('#FormularioCategoria')[0].reset();
    $("#CategoriaidCategoria").val("");
    $('#Archivos').empty();
    resetUpload();
}

function Cancelar() {
    LimpiarCategoria();
    $("#ModalCategoria").modal("hide");
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
