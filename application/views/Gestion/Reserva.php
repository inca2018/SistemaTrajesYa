<?php

$this->load->view('Layout/Header');
$this->load->view('Layout/Nav');

?>
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">

                            <div class="page-header-breadcrumb">
                                <ul class="breadcrumb-title">
                                    <li class="breadcrumb-item">
                                        <a href="/Menu"> <i class="feather icon-home"></i> </a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestión</a> </li>
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Reserva">Reserva</a> </li>
                                </ul>
                            </div>

                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-c-yellow text-white">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <p class="m-b-5">Usuarios APP</p>
                                                    <h4 class="m-b-0">0</h4>
                                                </div>
                                                <div class="col col-auto text-right">
                                                    <i class="feather icon-user f-50 text-c-yellow"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-c-blue text-white">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <p class="m-b-5">Ventas del Dia</p>
                                                    <h4 class="m-b-0">$0.00</h4>
                                                </div>
                                                <div class="col col-auto text-right">
                                                    <i class="feather icon-shopping-cart f-50 text-c-blue"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-c-green text-white">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <p class="m-b-5">Reserva Nueva</p>
                                                    <h4 class="m-b-0">0</h4>
                                                </div>
                                                <div class="col col-auto text-right">
                                                    <i class="feather icon-book f-50 text-c-green"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-c-pink text-white">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <p class="m-b-5">Reserva Cerrada</p>
                                                    <h4 class="m-b-0">0</h4>
                                                </div>
                                                <div class="col col-auto text-right">
                                                    <i class="feather icon-book f-50 text-c-pink"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="card-block">
                            <h5 class="col-md-12 sub-title">Lista de Reservas</h5>
                        </div>-->
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="dt-responsive">
                                        <table class="table table-sm  w-100 table-hover" id="tablaReserva" style="font-size:11px">
                                            <thead class="thead-light text-center">
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Acciones</th>
                                                    <th>Fecha Reserva</th>
                                                    <th>Tipo</th>
                                                    <th>Solicitante</th>
                                                    <th>Monto Total</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Asignación</th>
                                                    <th>Usuario Asignado</th>
                                                    <th>Fecha de Reg.</th>
                                                </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <footer class="text-center">
            <span class="text-center text-muted">ColorLib - TrajesYa! 2019</span>
        </footer>
    </div>
</div>
<?php
   $this->load->view('Layout/Footer');
?>
<div class="modal fade" id="ModalDetalleReserva" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">

                <div class="card-block">
                    <div class="row form-group">
                        <h5 class="col-md-12 sub-title">Información General</h5>
                        <div class="col-sm-12 col-md-12">
                            <h4 id="ReservaCodigo" class="f-w-600"></h4>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Tipo de Reserva:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="TipoReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Fecha de Entrega:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="FechaEntregaReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Estado:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="EstadoReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Tipo de Pago:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="TipoPagoReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Tipo de Comprobante:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="TipoComprobanteReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Tipo de Tarjeta:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="TipoTarjetaReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row form-group">
                        <h5 class="col-md-12 sub-title">Información de Contacto</h5>
                        <div class="col-md-6">

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Fecha de Registro:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="FechaRegistroReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Solicitante:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="SolicitanteReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Contacto:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="ContactoReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Distrito de Entrega:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="DistritoReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Direccion de Entrega:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="DireccionReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Referencia de Entrega:</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="ReferenciaReserva" class="m-b-5 f-w-400"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row form-group">
                        <h5 class="col-md-12 sub-title">Importes de Reserva</h5>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Precio Base:</p>
                                </div>
                                <div class="col-md-6">
                                    <h5 id="ImporteBaseReserva" class="m-b-5 f-w-400"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Precio de Delivery:</p>
                                </div>
                                <div class="col-md-6">
                                    <h5 id="ImporteDeliveryReserva" class="m-b-5 f-w-400"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Precio de Descuento:</p>
                                </div>
                                <div class="col-md-6">
                                    <h5 id="ImporteDescuentoReserva" class="m-b-5 f-w-400"></h5>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Precio Total:</p>
                                </div>
                                <div class="col-md-6">
                                    <h4 id="ImporteTotalReserva" class="m-b-5 f-w-600"></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted m-b-5">Cantidad de Items:</p>
                                </div>
                                <div class="col-md-6">
                                    <h5 id="CantidadItemsReserva" class="m-b-5 f-w-400"></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-block">
                    <h5 class="col-md-12 sub-title">Información de Items</h5>
                    <div id="BotonNuevo" class="row" style="display:none !important;">
                        <div class="col-md-3 ml-md-auto col-sm-6 ml-sm-auto">
                            <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoProductoReservaDetalle();">Nuevo Producto</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div id="AreTabla" class="col-md-12">
                            <table class="table table-sm  w-100 table-hover nowrap table-responsive" id="tablaDetalleReserva" style="font-size:11px">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>N°</th>
                                        <th>Codigo</th>
                                        <th>Acciones</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Precio Unitario</th>
                                        <th>Precio Total</th>
                                          <th>Medida</th>
                                        <th>Grupo/Categoria/SubCategoria</th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                        <div id="idFormulario" class="col-md-4 bl" style="display:none !important;">
                            <div class="row">
                                <div class="col-md-12">
                                        <form id="FormularioReservaDetalle" method="POST" autocomplete="off">
                                            <input type="hidden" name="idReservaOculta" id="idReservaOculta">
                                            <input type="hidden" name="idReservaProductoItem" id="idReservaProductoItem">
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <h4 id="tituloModalDetalleItemProducto" class="center_element"></h4>
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="col-form-label">Categoria:</label>
                                                    <select class="form-control" id="ReservaDetalleCategoria" name="ReservaDetalleCategoria"> </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="col-form-label">SubCategoria:</label>
                                                    <select class="form-control" id="ReservaDetalleSubCategoria" name="ReservaDetalleSubCategoria">
                                                        <option value="0">--- SELECCIONE ---</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="col-form-label">Producto:</label>
                                                    <select class="form-control" id="ReservaDetalleProducto" name="ReservaDetalleProducto">
                                                        <option value="0">--- SELECCIONE ---</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="col-form-label">Medida:</label>
                                                    <select class="form-control" id="ReservaDetalleMedida" name="ReservaDetalleMedida">
                                                        <option value="0">--- SELECCIONE ---</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="col-form-label">Cantidad:</label>
                                                    <input type="text" class="form-control" name="ReservaDetalleCantidad" id="ReservaDetalleCantidad" onkeypress="return SoloNumerosModificado(event,2,this.id);">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col col-md-6 text-left">
                                                    <input type="button" class="btn btn-grd-danger btn-round btn-sm" value="CANCELAR" onclick="CancelarNuevo();"> </div>
                                                <div class="col col-md-6 text-right">
                                                    <input type="submit" value="GUARDAR" class="btn btn-grd-primary btn-round btn-sm">
                                                </div>
                                            </div>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!--<div class="row">
                    <div class="col col-md-12 text-left">
                        <input type="button" class="btn btn-grd-danger btn-round btn-sm" value="CERRAR" onclick="CerrarGeneral();">
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalAsignarReserva" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
     <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card-block">
                    <div class="row form-group">
                        <h5 class="col-md-12 sub-title">Asignación de Reserva</h5>
                        <input type="hidden" id="idReservaAsignacion" name="idReservaAsignacion">
                        <div class="col-sm-12 col-md-12">
                              <div class="row form-group">
                                    <div class="col-sm-12 col-md-12">
                                        <label class="col-form-label">Seleccionar Usuario:</label>
                                        <select class="form-control" id="AsignacionUsuarios" name="AsignacionUsuarios"> </select>
                                    </div>
                              </div>
                              <div class="row form-group">
                                     <div class="col-sm-12 col-md-12">
                                         <button  type="button" class="btn btn-grd-success btn-block" onclick="AsignarReservaAccion();">GUARDAR</button>
                                     </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo base_url();?>assets/js/Gestion/Reserva.js"></script>
