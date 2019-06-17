var tablaProducto;
var Filter;
var Cantidad = 1

function init() {
    uploadImage();
    Iniciar_Componentes();
    Listar_Producto();
    ListarCategoria();
    ListarDepartamento();
}

function Iniciar_Componentes() {
    $("#FormularioProducto").on("submit", function (e) {
        RegistroProducto(e);
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

function ListarCategoria() {
    $.post("/Mantenimiento/Producto/ListarCategoria", function (ts) {
        $("#ProductoCategoria").empty();
        $("#ProductoCategoria").append(ts);
    });
    $("#ProductoCategoria").change(function () {
        var grupo = $('option:selected', this).data('grupo');
        var idCategoria = $(this).val();

        ListarSubCategoria(idCategoria);

        if (grupo == 1) {
            $("#AreaUbicacion").show();
        } else {
            $("#AreaUbicacion").hide();
        }
    });
}

function ListarSubCategoria(idCategoria) {
    $.post("/Mantenimiento/Producto/ListarSubCategoria", {
        idCategoria: idCategoria
    }, function (ts) {
        $("#ProductoSubCategoria").empty();
        $("#ProductoSubCategoria").append(ts);
    });
}

function ListarDepartamento() {
    $.post("/Mantenimiento/Producto/ListarDepartamento", function (ts) {
        $("#ProductoDepartamento").empty();
        $("#ProductoDepartamento").append(ts);
    });
    $("#ProductoDepartamento").change(function () {
        var idDepartamento = $(this).val();
        ListarProvincia(idDepartamento);
        $("#ProductoDistrito").empty();
        $("#ProductoDistrito").html("<option value='0'>--- SELECCIONE ---</option>")
    });
}

function ListarProvincia(idDepartamento) {
    $.post("/Mantenimiento/Producto/ListarProvincia", {
        idDepartamento: idDepartamento
    }, function (ts) {
        $("#ProductoProvincia").empty();
        $("#ProductoProvincia").append(ts);
    });
    $("#ProductoProvincia").change(function () {
        var idProvincia = $(this).val();
        ListarDistrito(idProvincia);
    });
}

function ListarDistrito(idProvincia) {
    $.post("/Mantenimiento/Producto/ListarDistrito", {
        idProvincia: idProvincia
    }, function (ts) {
        $("#ProductoDistrito").empty();
        $("#ProductoDistrito").append(ts);
    });
}

function RegistroProducto(event) {
    event.preventDefault();
    var imagenes = RecuperarImagenes();
    var imagenesBr = imagenes.join("|");
    var formData = new FormData($("#FormularioProducto")[0]);
    formData.append("Imagenes", imagenesBr);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/Producto/InsertUpdateProducto",
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
                $('#ModalProducto').modal('hide');
                mensaje_success(Mensaje)
                LimpiarProducto();
                tablaProducto.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}

function Listar_Producto() {
    tablaProducto = $('#tablaProducto').dataTable({
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
            url: '/Mantenimiento/Producto/ListarProducto',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
    //Aplicar ordenamiento y autonumeracion , index
    /*tablaProducto.on('order.dt search.dt', function () {
        tablaProducto.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();*/
}

function NuevoProducto() {
    resetUpload();
    $("#AreaUbicacion").hide();
    $("#ModalProducto").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalProducto").modal("show");
    $("#tituloModalProducto").empty();
    $("#tituloModalProducto").append("Registro de Producto");

}

function EditarProducto(idProducto) {
    $("#AreaUbicacion").hide();
    $("#ModalProducto").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalProducto").modal("show");
    $("#tituloModalProducto").empty();
    $("#tituloModalProducto").append("Edición de Producto");

    RecuperarProducto(idProducto);
}

function RecuperarProducto(idProducto) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Producto/ObtenerProducto", {
        "idProducto": idProducto
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#ProductoidProducto").val(data.idProducto);
        $("#ProductoTitulo").val(data.NombreProducto);
        $("#ProductoDescripcion").val(data.DescripcionProducto);
        if (data.imagenPortada != null && data.imagenPortada!="") {
            //Recuperando 1 Imagen
            var images = $('.images');
            images.prepend('<div class="img" style="background-image: url(\'' + data.imagenPortada + '\');" rel="' + data.imagenPortada + '"><span>remove</span></div>');
        }

        $.post("/Mantenimiento/Producto/ListarCategoria", function (ts) {
            $("#ProductoCategoria").empty();
            $("#ProductoCategoria").append(ts);
            $("#ProductoCategoria").val(data.Categoria_idCategoria);

            var grupo = $('option:selected', $("#ProductoCategoria")).data('grupo');
            if (grupo == 1) {
                $("#AreaUbicacion").show();
            } else {
                $("#AreaUbicacion").hide();
            }

            $.post("/Mantenimiento/Producto/ListarSubCategoria", {
                idCategoria: data.Categoria_idCategoria
            }, function (ts) {
                $("#ProductoSubCategoria").empty();
                $("#ProductoSubCategoria").append(ts);
                $("#ProductoSubCategoria").val(data.SubCategoria_idSubCategoria);

                $.post("/Mantenimiento/Producto/ListarDepartamento", function (ts) {
                    $("#ProductoDepartamento").empty();
                    $("#ProductoDepartamento").append(ts);
                    $("#ProductoDepartamento").val(data.Departamento_idDepartamento);
                    $.post("/Mantenimiento/Producto/ListarProvincia", {
                        idDepartamento: data.Departamento_idDepartamento
                    }, function (ts) {
                        $("#ProductoProvincia").empty();
                        $("#ProductoProvincia").append(ts);
                        $("#ProductoProvincia").val(data.Provincia_idProvincia);
                        $.post("/Mantenimiento/Producto/ListarDistrito", {
                            idProvincia: data.Provincia_idProvincia
                        }, function (ts) {
                            $("#ProductoDistrito").empty();
                            $("#ProductoDistrito").append(ts);
                            $("#ProductoDistrito").val(data.Distrito_idDistrito);
                        });
                    });
                });
            });
        });

    });
}

function EliminarProducto(idProducto, Producto) {
    swal({
        title: "Eliminar Producto?",
        text: "Esta seguro de eliminar al Producto: " + Producto,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Producto/EliminarProducto", {
            'idProducto': idProducto
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaProducto.ajax.reload();
            }
        });
    });
}

function HabilitarProducto(idProducto, Producto) {
    swal({
        title: "Habilitar Producto?",
        text: "Esta seguro de habilitar al Producto: " + Producto,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Producto/HabilitarProducto", {
            'idProducto': idProducto
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaProducto.ajax.reload();
            }
        });
    });
}

function InabilitarProducto(idProducto, Producto) {
    swal({
        title: "Inhabilitar Producto?",
        text: "Esta seguro de inhabilitar al Producto: " + Producto,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/Producto/InhabilitarProducto", {
            'idProducto': idProducto
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaProducto.ajax.reload();
            }
        });
    });
}

function LimpiarProducto() {
    $('#FormularioProducto')[0].reset();
    $("#idProducto").val("");
    resetUpload();
}

function Cancelar() {
    LimpiarProducto();
    $("#ModalProducto").modal("hide");
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

function Galeria(idProducto){
     $.redirect('/Mantenimiento/Galeria/', {
        'idProducto': idProducto
    });
}

init();
