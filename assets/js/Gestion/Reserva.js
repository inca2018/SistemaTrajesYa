var tablaReserva;
var tablaDetalleReserva;

var selectCategoria = $("#ReservaDetalleCategoria");
var selectSubCategoria = $("#ReservaDetalleSubCategoria");
var selectProducto = $("#ReservaDetalleProducto");
var selectMedida = $("#ReservaDetalleMedida");
var campoCantidad = $("#ReservaDetalleCantidad");
var selectUsuarios = $("#AsignacionUsuarios");

var botonNuevo = $("#BotonNuevo");
var Observaciones = $("#ReservaObservaciones");
var botonObservaciones = $("#botonObservaciones");

var AreaObservacion = $("#AreaObservacion");
var AreaBotonObservacion = $("#AreaBotonObservacion");

var selectLocales = $("#ReservaDetalleLocal");

function init() {
    Iniciar_Componentes();
    ListarReservas();
    RecuperarIndicadores();
}

function RecuperarIndicadores(){
     $.post("/Gestion/Reserva/RecuperarIndicadores", function (data, status) {
        data = JSON.parse(data);
        console.log(data);
         $("#Indicador1").empty();
         $("#Indicador2").empty();
         $("#Indicador3").empty();
         $("#Indicador4").empty();

         $("#Indicador1").html(data[0].totalUsuariosRegistrados);
         $("#Indicador2").html("S/ "+Formato_Moneda(data[0].totalMontoAlquilerReservas,2));
         $("#Indicador3").html(data[0].totalNumReservasNuevas);
         $("#Indicador4").html(data[0].totalNumReservasCerradas);

    });
}

function Iniciar_Componentes() {
    ListarCategorias();
    ListarLocalesDisponibles();

    $("#FormularioReservaDetalle").on("submit", function (e) {
        RegistroProductoReserva(e);
    });
}

function RegistroProductoReserva(event) {

    event.preventDefault();
    var idReservaRecuperado = $("#idReservaOculta").val();
    var formData = new FormData($("#FormularioReservaDetalle")[0]);

    console.log(formData);
    $.ajax({
        url: "/Gestion/Reserva/InsertUpdateProductoReserva",
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
                CancelarNuevo();
                mensaje_success(Mensaje)
                DetalleReserva(idReservaRecuperado, 2, null);
            }
        },
        error: function (e) {
            console.log(e.responseText);
        },
        complete: function () {}
    });
}

function ListarLocalesDisponibles() {
    $.post("/Gestion/Reserva/ListarLocalesDisponibles", function (ts) {
        selectLocales.empty();
        selectLocales.append(ts);
    });
}

function ListarCategorias() {
    $.post("/Mantenimiento/Producto/ListarCategoria", function (ts) {
        selectCategoria.empty();
        selectCategoria.append(ts);
    });
    selectCategoria.change(function () {
        var idCategoria = $(this).val();
        if (idCategoria == 0 || idCategoria == '0') {
            selectSubCategoria.empty();
            selectSubCategoria.append("<option>-- SELECCIONE ---</option>");
            selectProducto.empty();
            selectProducto.append("<option>-- SELECCIONE ---</option>");
            selectMedida.empty();
            selectMedida.append("<option>-- SELECCIONE ---</option>");
        } else {
            selectProducto.empty();
            selectProducto.append("<option>-- SELECCIONE ---</option>");
            selectMedida.empty();
            selectMedida.append("<option>-- SELECCIONE ---</option>");
        }

        ListarSubCategoria(idCategoria);
    });
}


function ListarSubCategoria(idCategoria) {
    $.post("/Mantenimiento/Producto/ListarSubCategoria", {
        idCategoria: idCategoria
    }, function (ts) {
        selectSubCategoria.empty();
        selectSubCategoria.append(ts);
    });
    selectSubCategoria.change(function () {
        var idSubCategoria = $(this).val();
        if (idSubCategoria == 0 || idSubCategoria == '0') {
            selectProducto.empty();
            selectProducto.append("<option>-- SELECCIONE ---</option>");
            selectMedida.empty();
            selectMedida.append("<option>-- SELECCIONE ---</option>");
        } else {
            selectMedida.empty();
            selectMedida.append("<option>-- SELECCIONE ---</option>");
        }
        ListarProductosSelect(idSubCategoria);
    });
}

function ListarProductosSelect(idSubCategoria) {
    $.post("/Mantenimiento/Producto/ListarProductosSelect", {
        idSubCategoria: idSubCategoria
    }, function (ts) {
        selectProducto.empty();
        selectProducto.append(ts);
    });
    selectProducto.change(function () {
        var idProducto = $(this).val();
        if (idProducto == 0 || idProducto == '0') {
            selectMedida.empty();
            selectMedida.append("<option>-- SELECCIONE ---</option>");
        }
        ListarMedidasSelect(idProducto);
    });
}

function ListarMedidasSelect(idProducto) {
    $.post("/Mantenimiento/Producto/ListarMedidasSelect", {
        idProducto: idProducto
    }, function (ts) {
        selectMedida.empty();
        selectMedida.append(ts);
    });
}

function ListarReservas() {
    tablaReserva = $('#tablaReserva').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "processing": true,
        "paging": true, // Paginacion en tabla
        "ordering": true, // Ordenamiento en columna de tabla
        "info": true, // Informacion de cabecera tabla
        "responsive": true, // Accion de responsive
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        // "order": [[3, "desc"]],
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
            url: '/Gestion/Reserva/ListarReserva',
            type: "POST",
            dataType: "JSON",
            error: function (e) {
                console.log(e.responseText);
            },
            complete: function (r) {
                console.log("Completo lista Reservas!");
            },

        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
}

function MostrarOpciones(Mostrar) {

    if (Mostrar == 1) {
        botonNuevo.show();
        AreaObservacion.show();
        AreaBotonObservacion.show();
    } else {
        botonNuevo.hide();
        AreaObservacion.hide();
        AreaBotonObservacion.hide();
    }
}

function GuardarObservaciones() {
    debugger;
    var idLocal = selectLocales.val();
    var idReserva = $("#idReservaBase").val();
    var Obs = Observaciones.val();
    if (idLocal == 0) {
            mensaje_warning("Debe Seleccionar Local de Atención para continuar.");
    } else {
        $.post("/Gestion/Reserva/ActualizarObservaciones", {
            'idReserva': idReserva,
            'Observaciones': Obs,
            'idLocal':idLocal
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                mensaje_warning(data.Mensaje);
            } else {
                mensaje_success(data.Mensaje);
                tablaReserva.ajax.reload();
            }
        });
    }
}

function DetalleReserva(idReserva, tipo, Mostrar, idUsuarioAsignado) {

    MostrarOpciones(Mostrar);
    //solicitud de recuperar Proveedor
    $.post("/Gestion/Reserva/ObtenerReserva", {
        "idReserva": idReserva
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#idReservaBase").val(idReserva);
        $("#ReservaCodigo").empty();
        $("#TipoReserva").empty();
        $("#FechaEntregaReserva").empty();
        $("#EstadoReserva").empty();
        $("#TipoPagoReserva").empty();
        $("#TipoComprobanteReserva").empty();
        $("#TipoTarjetaReserva").empty();
        $("#SolicitanteReserva").empty();
        $("#FechaRegistroReserva").empty();
        $("#ContactoReserva").empty();
        $("#DistritoReserva").empty();
        $("#DireccionReserva").empty();
        $("#ReferenciaReserva").empty();
        $("#ImporteBaseReserva").empty();
        $("#ImporteDeliveryReserva").empty();
        $("#ImporteDescuentoReserva").empty();
        $("#ImporteTotalReserva").empty();
        $("#CantidadItemsReserva").empty();

        $("#ReservaCodigo").append(data.CodigoReserva.toUpperCase());
        $("#TipoReserva").append(TipoReserva(data));
        $("#FechaEntregaReserva").append(data.fechaReserva + " Hora: " + data.horaReserva + " " + data.tiempo.toUpperCase());
        $("#EstadoReserva").append(BuscarEstado(data));
        $("#TipoPagoReserva").append(data.TipoPago.toUpperCase());
        $("#TipoComprobanteReserva").append(data.NombreComprobante.toUpperCase());
        $("#TipoTarjetaReserva").append(data.TarjetaReserva.toUpperCase());
        $("#SolicitanteReserva").append(data.UsuarioReserva.toUpperCase());
        $("#FechaRegistroReserva").append(data.fechaRegistro.toUpperCase());
        $("#ContactoReserva").append(data.TelefonoContacto.toUpperCase());
        $("#DistritoReserva").append(data.DistritoReserva.toUpperCase());
        $("#DireccionReserva").append(data.UbicacionDireccion.toUpperCase());
        $("#ReferenciaReserva").append(data.UbicacionReferencia.toUpperCase());
        $("#ImporteBaseReserva").append(VerificarBase(data));
        $("#ImporteDeliveryReserva").append(VerificarDelivery(data));
        $("#ImporteDescuentoReserva").append(VerificarDescuento(data));
        $("#ImporteTotalReserva").append(VerificarTotal(data));
        $("#CantidadItemsReserva").append(data.ItemsReserva);
        Observaciones.val(data.Observaciones);
        $("#idReservaOculta").val(idReserva);
        $("#idReservaProductoItem").val("");

        if (tipo == 1) {
            ListarItemsReserva(idReserva, data.TipoReserva, data.Estado_idEstado, idUsuarioAsignado);
        } else {
            tablaDetalleReserva.ajax.reload();
        }

    });
}

function TipoReserva(data) {

    if (data.TipoReserva == 1 || data.TipoReserva == '1') {
        return '<div class="badge badge-danger">URGENTE</div>';
    } else {
        return '<div class="badge badge-info">REGULAR</div>';
    }

}

function BuscarEstado(data) {

    if (data.Estado_idEstado == '3' || data.Estado_idEstado == 3) {
        return '<div class="badge badge-success">' + data.DescripcionEstado.toUpperCase() + '</div>';
    } else if (data.Estado_idEstado == '4' || data.Estado_idEstado == 4) {
        return '<div class="badge badge-info">' + data.DescripcionEstado.toUpperCase() + '</div>';
    } else if (data.Estado_idEstado == '6' || data.Estado_idEstado == 6) {
        return '<div class="badge badge-primary">' + data.DescripcionEstado.toUpperCase() + '</div>';
    } else if (data.Estado_idEstado == '7' || data.Estado_idEstado == 7) {
        return '<div class="badge badge-danger">' + data.DescripcionEstado.toUpperCase() + '</div>';
    }

}

function VerificarBase(data) {
    if (data.TipoReserva == 1 || data.TipoReserva == '1') {
        return 'S/ ' + Formato_Moneda(parseFloat(data.totalAlquiler), 2);
    } else {
        return 'S/ ' + Formato_Moneda(parseFloat(data.totalVenta), 2);
    }
}

function VerificarDelivery(data) {
    return 'S/ ' + Formato_Moneda(parseFloat(data.Delivery), 2);
}

function VerificarDescuento(data) {
    return 'S/ ' + Formato_Moneda(parseFloat(data.totalDescuento), 2);
}

function VerificarTotal(data) {
    var alquiler = parseFloat(data.totalAlquiler);
    var venta = parseFloat(data.totalVenta);
    var delivery = parseFloat(data.Delivery);
    var decuento = parseFloat(data.totalDescuento);
    var total = 0;
    if (data.TipoReserva == 1 || data.TipoReserva == '1') {
        total = (alquiler + delivery) - decuento;
        return "S/ " + Formato_Moneda(total, 2);
    } else {
        total = (venta + delivery) - decuento;
        return "S/ " + Formato_Moneda(total, 2);
    }
}

function ListarItemsReserva(idReserva, tipo, EstadoReserva, idUsuarioAsignado) {

    tablaDetalleReserva = $('#tablaDetalleReserva').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "processing": true,
        "paging": true, // Paginacion en tabla
        "ordering": true, // Ordenamiento en columna de tabla
        "info": true, // Informacion de cabecera tabla
        "responsive": true, // Accion de responsive
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        // "order": [[3, "desc"]],
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
            url: '/Gestion/Reserva/ListarReservaDetalle',
            type: "POST",
            dataType: "JSON",
            data: {
                idReserva: idReserva,
                TipoReserva: tipo,
                EstadoReserva: EstadoReserva,
                idUsuarioAsignado: idUsuarioAsignado
            },
            error: function (e) {
                console.log(e.responseText);
            },
            complete: function (r) {
                console.log("Completo lista Items!");
                $("#ModalDetalleReserva").modal("show");
            },

        }, // cambiar el lenguaje de datatable
        oLanguage: español,
    }).DataTable();
    //Aplicar ordenamiento y autonumeracion , index
    tablaDetalleReserva.on('order.dt search.dt', function () {
        tablaDetalleReserva.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

}

function AsignarReserva(idReserva) {
    $("#idReservaAsignacion").val(idReserva);
    $("#ModalAsignarReserva").modal("show");
    ListarUsuarios();
}

function ListarUsuarios() {
    $.post("/Gestion/Reserva/ListarUsuarioOpe", function (ts) {
        selectUsuarios.empty();
        selectUsuarios.append(ts);
    });
}

function AsignarReservaAccion() {
    var idReserva = $("#idReservaAsignacion").val();
    var idUsuario = selectUsuarios.val();

    if (idUsuario == 0 || idUsuario == "0") {
        mensaje_warning("Selecciones Usuario para Asignación.");
    } else {
        swal({
            title: "Asignación de Usuario",
            text: "¿Esta seguro de Asignar Usuario a la Reserva?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Asignar",
            closeOnConfirm: false
        }, function () {
            $.post("/Gestion/Reserva/AsignarReservaAccion", {
                'idReserva': idReserva,
                'idUsuario': idUsuario
            }, function (data, e) {
                data = JSON.parse(data);
                if (data.Error) {
                    swal("Error", data.Mensaje, "error");
                } else {
                    swal("Asignado!", data.Mensaje, "success");
                    $("#ModalAsignarReserva").modal("hide");
                    tablaReserva.ajax.reload();
                }
            });
        });

    }

}

function AnularReserva(idReserva) {
    $("#ModalAnulacionReserva").modal("show");
    $("#idReservaAnulacion").val(idReserva);
    $("#DetalleAnulacion").val("");

    $("#AreaFechaAnulacion").hide()
    $("#AreaBotonAnulacion").show()
    $("#DetalleAnulacion").removeAttr("disabled");
}

function AnularReservaAccion() {
    var DetalleAnulacion = $("#DetalleAnulacion").val();
    var idReserva = $("#idReservaAnulacion").val();
    if (DetalleAnulacion.length == 0) {
        mensaje_warning("Ingrese Motivo de Anulación");
    } else {
        swal({
            title: "Anulación de Reserva",
            text: "Esta seguro de Anular la Reserva",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Anular!",
            closeOnConfirm: false
        }, function () {
            $.post("/Gestion/Reserva/AnularProductoReserva", {
                'idReserva': idReserva,
                'DetalleAnulacion': DetalleAnulacion
            }, function (data, e) {
                data = JSON.parse(data);
                if (data.Error) {
                    swal("Error", data.Mensaje, "error");
                } else {
                    swal("Anulado!", data.Mensaje, "success");
                    $("#ModalAnulacionReserva").modal("hide");
                    tablaReserva.ajax.reload();
                }
            });
        });
    }

}

function CerrarReserva(idReserva) {
    $("#ModalCierreReserva").modal("show");
    $("#idReservaCierre").val(idReserva);
    $("#DetalleCierre").val("");

    $("#AreaFechaCierre").hide()
    $("#AreaBotonCierre").show()
    $("#DetalleCierre").removeAttr("disabled");
}

function CierreReservaAccion() {
    var DetalleCierre = $("#DetalleCierre").val();
    var idReserva = $("#idReservaCierre").val();
    if (DetalleCierre.length == 0) {
        mensaje_warning("Ingrese Motivo de Cierre");
    } else {
        swal({
            title: "Cierre de Reserva",
            text: "Esta seguro de Cerrar la Reserva",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Cerrar!",
            closeOnConfirm: false
        }, function () {
            $.post("/Gestion/Reserva/CerrarProductoReserva", {
                'idReserva': idReserva,
                'DetalleCierre': DetalleCierre
            }, function (data, e) {
                data = JSON.parse(data);
                if (data.Error) {
                    swal("Error", data.Mensaje, "error");
                } else {
                    swal("Cerrado!", data.Mensaje, "success");
                    $("#ModalCierreReserva").modal("hide");
                    tablaReserva.ajax.reload();
                }
            });
        });
    }

}

function DetalleAnulacion(idReserva) {
    $("#ModalAnulacionReserva").modal("show");

    $.post("/Gestion/Reserva/RecuperarDatosAnulacion", {
        "idReserva": idReserva
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#AreaFechaAnulacion").show()
        $("#AreaBotonAnulacion").hide()
        $("#DetalleAnulacion").attr("disabled", true);
        $("#FechaAnulacionReserva").empty();
        $("#FechaAnulacionReserva").append(data.fechaAnulacion);
        $("#DetalleAnulacion").val(data.DetalleAnulacion);

    });
}

function DetalleCierre(idReserva) {
    $("#ModalCierreReserva").modal("show");

    $.post("/Gestion/Reserva/RecuperarDatosCierre", {
        "idReserva": idReserva
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#AreaFechaCierre").show()
        $("#AreaBotonCierre").hide()
        $("#DetalleCierre").attr("disabled", true);

        $("#FechaCierreReserva").empty();
        $("#FechaCierreReserva").html(data.fechaCierre);
        $("#DetalleCierre").val(data.DetalleCierre);

    });
}

function CerrarGeneral() {
    $("#ModalDetalleReserva").modal("hide");
}

function CancelarNuevo() {
    $('#FormularioReservaDetalle')[0].reset();
    $("#idFormulario").hide();
    $("#AreTabla").removeClass("col-md-8");
    $("#AreTabla").addClass("col-md-12");
    $("#idReservaProductoItem").val("");
}

function NuevoProductoReservaDetalle() {
    $("#idFormulario").show();
    $("#AreTabla").removeClass("col-md-12");
    $("#AreTabla").addClass("col-md-8");
    $("#tituloModalDetalleItemProducto").empty();
    $("#tituloModalDetalleItemProducto").html("Agregar Item:");
}

function EditarItem(idRerservaItem) {

    $("#idReservaProductoItem").val(idRerservaItem);
    $("#idFormulario").show();
    $("#AreTabla").removeClass("col-md-12");
    $("#AreTabla").addClass("col-md-8");
    $("#tituloModalDetalleItemProducto").empty();
    $("#tituloModalDetalleItemProducto").html("Editar Item:");
    RecuperarInformacionItem(idRerservaItem);
}

function RecuperarInformacionItem(idRerservaItem) {
    //solicitud de recuperar Proveedor
    $.post("/Gestion/Reserva/ObtenerReservaItem", {
        "idRerservaItem": idRerservaItem
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);

        $("#idReservaProductoItem").val(idRerservaItem);
        $.post("/Mantenimiento/Producto/ListarCategoria", function (ts) {
            selectCategoria.empty();
            selectCategoria.append(ts);
            selectCategoria.val(data.idCategoria);
            $.post("/Mantenimiento/Producto/ListarSubCategoria", {
                idCategoria: data.idCategoria
            }, function (ts) {
                selectSubCategoria.empty();
                selectSubCategoria.append(ts);
                selectSubCategoria.val(data.idSubCategoria);
                $.post("/Mantenimiento/Producto/ListarProductosSelect", {
                    idSubCategoria: data.idSubCategoria
                }, function (ts) {
                    selectProducto.empty();
                    selectProducto.append(ts);
                    selectProducto.val(data.idProducto);
                    $.post("/Mantenimiento/Producto/ListarMedidasSelect", {
                        idProducto: data.idProducto
                    }, function (ts) {
                        selectMedida.empty();
                        selectMedida.append(ts);
                        selectMedida.val(data.idMedida);
                        campoCantidad.val(data.Cantidad);
                    });
                });
            });
        });


    });
}

function EliminarItem(idReservaItem) {
    swal({
        title: "Eliminar Producto de Reserva?",
        text: "Esta seguro de eliminar al Producto de Reserva",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        closeOnConfirm: false
    }, function () {
        $.post("/Gestion/Reserva/EliminarProductoReserva", {
            'idReservaItem': idReservaItem
        }, function (data, e) {
            data = JSON.parse(data);
            if (data.Error) {
                swal("Error", data.Mensaje, "error");
            } else {
                swal("Eliminado!", data.Mensaje, "success");

                var idReservaRecuperado = $("#idReservaOculta").val();
                DetalleReserva(idReservaRecuperado, 2, null);
            }
        });
    });
}

init();
