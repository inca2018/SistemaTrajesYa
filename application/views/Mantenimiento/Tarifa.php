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
                        <input type="hidden" id="idProducto" value="<?php echo $_POST["idProducto"]; ?>">
                        <input type="hidden" id="TarifaPrecioBaseO" name="TarifaPrecioBaseO" value="0">
                        <input type="hidden" id="TarifaPrecioVentaO" name="TarifaPrecioVentaO" value="0">

                        <div class="card-block">
                            <div class="col sm-3"> </div>
                            <div class="page-header-breadcrumb">
                                <ul class="breadcrumb-title">
                                    <li class="breadcrumb-item">
                                        <a href="/Menu"> <i class="feather icon-home"></i> </a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Mantenimiento</a> </li>
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Tarifa">Tarifa de Producto</a> </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-block">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Producto:</h6>
                                    <h5 class="text-primary" id="NameProducto"></h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-12 col-xl-12">

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs md-tabs " role="tablist">
                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home7" role="tab"><i class="icofont icofont-cur-dollar mr-2"></i>Tarifa General</a>
                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile7" role="tab"><i class="icofont icofont-vehicle-delivery-van  mr-2"></i>Tarifa de Delivery</a>
                                            <div class="slide"></div>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content card-block">

                                        <div class="tab-pane active" id="home7" role="tabpanel">
                                            <form id="FormularioTarifa" method="POST" autocomplete="off">
                                                <div class="row form-group align-items-center">

                                                    <div class="col-sm-4 col-md-4">
                                                        <label class="col-form-label">Precio de Alquiler Base:</label>
                                                        <input type="text" class="form-control validarPanel" name="TarifaPrecioBaseV" id="TarifaPrecioBaseV" value="" maxlength="7" onkeypress="return SoloNumerosModificado(event,7,this.id);">
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <label class="col-form-label">Precio de Venta Base:</label>
                                                        <input type="text" class="form-control validarPanel" name="TarifaPrecioVentaV" id="TarifaPrecioVentaV" value="" maxlength="7" onkeypress="return SoloNumerosModificado(event,7,this.id);">
                                                    </div>
                                                    <div class="col-md-3 col-sm-3 mt-4">
                                                        <button type="submit" class="btn btn-grd-success btn-block btn-sm btn-round">Guardar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane" id="profile7" role="tabpanel">
                                            <div class="card-block">
                                                <div class="row">
                                                    <div class="col-md-3 ml-md-auto col-sm-6 ml-sm-auto">
                                                        <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoDelivery();">Nuevo Tarifa de Delivery</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-block">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="dt-responsive">
                                                            <table class="table table-sm  w-100 table-hover" id="tablaTarifa">
                                                                <thead class="thead-light text-center">
                                                                    <tr>
                                                                        <th>Ubicación</th>
                                                                        <th>Tarifa</th>
                                                                        <th>Fecha de Reg.</th>
                                                                        <th>Ultima Act.</th>
                                                                        <th>Accion</th>

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="text-center"> <span class="text-center text-muted">ColorLib - TrajesYa! 2019</span> </footer>
    </div>
</div>
<?php
   $this->load->view('Layout/Footer');
?>
<div class="modal fade" id="ModalDelivery" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="FormularioTarifaDelivery" method="POST" autocomplete="off">
                    <input type="hidden" name="Delivery_idDelivery" id="Delivery_idDelivery">
                    <input type="hidden" name="DeliveryPrecioO" id="DeliveryPrecioO" value="0.00">
                    <div class="form-group row">
                        <div class="col-sm-12 center_element">
                            <h4 id="tituloModalDelivery"></h4>
                            <hr>
                        </div>
                    </div>
                   <div class="row form-group">
                        <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Precio de Delivery:</label>
                            <input type="text" class="form-control validarPanel" name="DeliveryPrecio" id="DeliveryPrecio" value=""  onkeypress="return SoloNumerosModificado(event,7,this.id);">
                        </div>

                    </div>
                      <div class="row form-group">
                        <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Departamento:</label>
                            <select class="form-control" id="DeliveryDepartamento" name="DeliveryDepartamento" required>
                             <option value="">--- SELECCIONE ---</option></select>
                        </div>
                         <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Provincia:</label>
                            <select class="form-control" id="DeliveryProvincia" name="DeliveryProvincia" required>
                             <option value="">--- SELECCIONE ---</option></select>
                        </div>
                         <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Distrito:</label>
                            <select class="form-control" id="DeliveryDistrito" name="DeliveryDistrito" required>
                             <option value="">--- SELECCIONE ---</option> </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-6 text-left">
                            <input type="button" class="btn btn-grd-danger btn-round btn-sm" value="CANCELAR" onclick="Cancelar();"> </div>
                        <div class="col col-md-6 text-right">
                            <input type="submit" value="GUARDAR" class="btn btn-grd-primary btn-round btn-sm"> </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/Tarifa.js"></script>
