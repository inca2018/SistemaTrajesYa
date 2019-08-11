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
                                     <li class="breadcrumb-item"><a href="/Mantenimiento/Producto">Producto</a> </li>
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


<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/Tarifa.js"></script>
