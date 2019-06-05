var tablaCategoria;
var Filter;
function init() {
    Iniciar_Componentes();
    Listar_Categoria();
    ListarGrupos();
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

     Filter=$("#PortadaCategoria").filer({
        limit: 1,
        maxSize: 1,
        fileMaxSize: 10,
        extensions: ["jpg", "png"],
        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Arrastre Imagenes Aqui</h3> <span style="display:inline-block; margin: 15px 0">o</span></div><a class="jFiler-input-choose-btn btn btn-primary waves-effect waves-light">Buscar Imagenes</a></div></div>',
        showThumbs: true,
        theme: "dragdropbox",
        templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                        <span class="jFiler-item-others">{{fi-size2}}</span>\
                                    </div>\
                                    {{fi-image}}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{{fi-progressBar}}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
            itemAppend: '<li class="jFiler-item">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                            <span class="jFiler-item-others">{{fi-size2}}</span>\
                                        </div>\
                                        {{fi-image}}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
        dragDrop: {
            dragEnter: null,
            dragLeave: null,
            drop: null,
        },
        /*uploadFile: {
            url: "/Mantenimiento/Categoria/AgregarFile",
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function () {
                console.log("Enviando!!!");
            },
            success: function (data, el) {
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function () {
                    $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Guardado</div>").hide().appendTo(parent).fadeIn("slow");
                });
            },
            error: function (el) {
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function () {
                    $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
                });
            },
            statusCode: null,
            onProgress: null,
            onComplete: null
        }, */
        files: null,
        addMore: false,
        clipBoardPaste: true,
        excludeName: null,
        beforeRender: null,
        afterRender: null,
        beforeShow: null,
        beforeSelect: function () {
            var nombre = $("#CategoriaTitulo").val();
            if (nombre.length == 0) {
                mensaje_warning("Ingrese Nombre de Categoria!");
                return false;
            } else {
                return true;
            }
        },
        onSelect: null,
        afterShow: null,
        onRemove: function (itemEl, file, id, listEl, boxEl, newInputEl, inputEl) {
            var file = file.name;
            $.post('/Mantenimiento/Categoria/EliminarFile', {
                file: file
            });
        },
        onEmpty: null,
        options: null,
        captions: {
            button: "Seleccionar archivos",
            feedback: "Selecciona archivos para subir",
            feedback2: "Los archivos fueron elegidos",
            drop: "Colocar archivo aquí para subir",
            removeConfirmation: "¿Estás seguro de que quieres eliminar este archivo?",
            errors: {
                filesLimit: "Solamente {{fi-limit}} archivos pueden ser cargados.",
                filesType: "Solo se permite subir imágenes.",
                filesSize: "{{fi-name}} ¡Es demasiado largo! Por favor suba el archivo a {{fi-maxSize}} MB.",
                filesSizeAll: "¡Los archivos que has elegido son demasiado grandes! Por favor suba archivos hasta {{fi-maxSize}} MB."
            }
        }
    });

}

function RegistroCategoria(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioCategoria")[0]);
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
        complete: function () {}
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
            debugger;

            var name2=data.imagenPortada.replace('Categoria/','');
            var ruta2="assets/images/Categoria/"+name;
            var size2="100";
            var type2="image/jpg";

            var obj = { name: name2,size:size2,type:type2,ruta:ruta2};
            var myJSON = JSON.stringify(obj);
            console.log(myJSON);

            var api = $.fileuploader.getInstance($("#CategoriaPortada"));
            api.append(myJSON);
            api.reset();
            //Filter[0].files.append(myJSON);
            //$("#PortadaCategoria").filer('append',myJSON);
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
    $("#idCategoria").val("");
}

function Cancelar() {
    LimpiarCategoria();
    $("#ModalCategoria").modal("hide");
}

init();
