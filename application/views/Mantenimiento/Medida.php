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
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Medida">Medidas</a> </li>
                                </ul>
                            </div>

                        </div>

                        <div class="card-block">
                           <h5 class="col-md-12 sub-title">Lista de Medidas</h5>
                            <div class="row">
                                <div class="col-md-3 ml-md-auto col-sm-6 ml-sm-auto">
                                    <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoMedida();">Nuevo Medida</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                   <div class="dt-responsive">
                                    <table class="table table-sm  w-100 table-hover" id="tablaMedida">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>Titulo de Medida</th>
                                                <th>Simbolo de Medida</th>
                                                <th>Acciones</th>
                                                <th>Fecha de Reg.</th>
                                                <th>Ultima Act.</th>
                                                <th>Estado</th>
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
<div class="modal fade" id="ModalMedida" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="FormularioMedida" method="POST" autocomplete="off">
                    <input type="hidden" name="MedidaidMedida" id="MedidaidMedida">
                    <div class="form-group row">
                        <div class="col-sm-12 center_element">
                            <h4 id="tituloModalMedida"></h4>
                            <hr>
                        </div>
                    </div>
                     <div class="row form-group">
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Titulo del Medida:</label>
                            <input type="text" class="form-control validarPanel" name="MedidaTitulo" id="MedidaTitulo" value=""  maxlength="60">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-3 col-md-3">
                            <label class="col-form-label">Simbolo del Medida:</label>
                            <input type="text" class="form-control validarPanel" name="MedidaSimbolo" id="MedidaSimbolo" value=""  maxlength="3">
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/Medida.js"></script>
