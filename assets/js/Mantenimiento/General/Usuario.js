var tablaUsuario;

function init() {
    Iniciar_Componentes();
    Listar_Usuario();
    ListarTipoPerfil();
    ListarTipoArea();
}

function ListarTipoPerfil() {
    $.post("/Mantenimiento/General/Usuario/ListarTipoPerfil", function (ts) {
        $("#UsuarioPerfil").empty();
        $("#UsuarioPerfil").append(ts);
    });
}

function ListarTipoArea() {
    $.post("/Mantenimiento/General/Usuario/ListarTipoArea", function (ts) {
        $("#UsuarioArea").empty();
        $("#UsuarioArea").append(ts);
    });
}

function Iniciar_Componentes() {
    $("#FormularioUsuario").on("submit", function (e) {
        RegistroUsuario(e);
    });

    $("#UsuarioImagen").change(function () {
        readURL(this);
    });
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function RegistroUsuario(event) {
    event.preventDefault();
    var formData = new FormData($("#FormularioUsuario")[0]);
    console.log(formData);
    $.ajax({
        url: "/Mantenimiento/General/Usuario/InsertUpdateUsuario",
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
                $('#ModalUsuario').modal('hide');
                mensaje_success(Mensaje)
                LimpiarUsuario();
                tablaUsuario.ajax.reload();
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}

function Listar_Usuario() {
    tablaUsuario = $('#tablaUsuario').dataTable({
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
                "targets": [1, 2, 3, 4, 5]
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
            url: '/Mantenimiento/General/Usuario/ListarUsuario',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            }
        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function NuevoUsuario() {
    $("#ModalUsuario").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalUsuario").modal("show");
    $("#tituloModalUsuario").empty();
    $("#tituloModalUsuario").append("Registro de Usuario");
    $("#UsuarioPass").addClass("validarPanel");

     $("#imagePreview").removeAttr("style");
    $("#imagePreview").attr("style","background-image: url('/assets/images/usuario_default.svg'); width: 100; height: 100;");
}

function EditarUsuario(idUsuario) {
    $("#ModalUsuario").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#ModalUsuario").modal("show");
    $("#tituloModalUsuario").empty();
    $("#tituloModalUsuario").append("Edición de Usuario");
    $("#UsuarioPass").removeClass("validarPanel");
    RecuperarUsuario(idUsuario);
}

function RecuperarUsuario(idUsuario) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/General/Usuario/ObtenerUsuario", {
        "idUsuario": idUsuario
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);

        if(data.imagen==null){
            $("#imagePreview").removeAttr("style");
            $("#imagePreview").attr("style","background-image: url('/assets/images/usuario_default.svg'); width: 100; height: 100;");
        }else{
             $("#imagePreview").removeAttr("style");
            $("#imagePreview").attr("style","background-image: url('/assets/images/"+data.imagen+"'); width: 100; height: 100;");
        }

        $("#idUsuario").val(data.idUsuario);
        $("#UsuarioNombre").val(data.NombreUsuario);
        $("#UsuarioApellido").val(data.ApellidosUsuario);
        $("#UsuarioDni").val(data.Dni);
        $("#UsuarioCargo").val(data.Cargo);
        $("#UsuarioPerfil").val(data.perfil_idPerfil);
        $("#UsuarioArea").val(data.area_idArea);
        $("#UsuarioUsuario").val(data.usuario);
        $("#UsuarioPass").val("");
        $("#UsuarioCorreo").val(data.Correo);


    });
}

function EliminarUsuario(idUsuario, Usuario) {
    swal({
        title: "Eliminar Usuario?",
        text: "Esta seguro de eliminar al Usuario: " + Usuario,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/General/Usuario/EliminarUsuario", {
            'idUsuario': idUsuario
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");
                tablaUsuario.ajax.reload();
            }
        });
    });
}

function HabilitarUsuario(idUsuario, Usuario) {
    swal({
        title: "Habilitar Usuario?",
        text: "Esta seguro de habilitar al Usuario: " + Usuario,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Habilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/General/Usuario/HabilitarUsuario", {
            'idUsuario': idUsuario
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Habilitado!", data.Mensaje, "success");
                tablaUsuario.ajax.reload();
            }
        });
    });
}

function InabilitarUsuario(idUsuario, Usuario) {
    swal({
        title: "Inhabilitar Usuario?",
        text: "Esta seguro de inhabilitar al Usuario: " + Usuario,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Inhabilitar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Mantenimiento/General/Usuario/InhabilitarUsuario", {
            'idUsuario': idUsuario
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Inhabilitado!", data.Mensaje, "success");
                tablaUsuario.ajax.reload();
            }
        });
    });
}

function LimpiarUsuario() {
    $('#FormularioUsuario')[0].reset();
    $("#idUsuario").val("");
}

function Cancelar() {
    LimpiarUsuario();
    $("#ModalUsuario").modal("hide");
}
init();
