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
                            <div class="col sm-3">

                            </div>
                            <div class="page-header-breadcrumb">
                                <ul class="breadcrumb-title">
                                    <li class="breadcrumb-item">
                                        <a href="/Menu"> <i class="feather icon-home"></i> </a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Mantenimiento</a> </li>
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Delivery">Tarifa de Delivery</a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-block">
                            <h5 class="col-md-12 sub-title">Tarifas de Delivery</h5>
                            <div class="row">
                                <div class="col-md-4 ml-md-auto col-sm-6 ml-sm-auto">
                                    <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoDelivery();">Nueva Tarifa de Delivery</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="dt-responsive">
                                        <table class="table table-sm  w-100 table-hover" id="tablaDelivery">
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
        <footer class="text-center">
            <span class="text-center text-muted">ColorLib - TrajesYa! 2019</span>
        </footer>
    </div>
</div>
<?php
   $this->load->view('Layout/Footer');
?>
<div class="modal fade" id="ModalDelivery" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="FormularioDelivery" method="POST" autocomplete="off">
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
                            <input type="text" class="form-control validarPanel" name="DeliveryPrecio" id="DeliveryPrecio" value="S/. 0.00"  onkeypress="return SoloNumerosModificado(event,7,this.id);" >
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/Delivery.js"></script>
