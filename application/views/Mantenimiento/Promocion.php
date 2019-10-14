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
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Promocion">Promocion</a> </li>
                                </ul>
                            </div>

                        </div>

                        <div class="card-block">
                            <h5 class="col-md-12 sub-title">Lista de Promociones</h5>
                            <div class="row">
                                <div class="col-md-3 ml-md-auto col-sm-6 ml-sm-auto">
                                    <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoPromocion();">Nueva Promocion</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="dt-responsive">
                                        <table class="table table-sm  w-100 table-hover" id="tablaPromocion">
                                            <thead class="thead-light text-center">
                                                <tr>
                                                    <th>Titulo Portada</th>
                                                    <th>Link de Promoción</th>
                                                    <th>Imagen Portada</th>
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


                         <div class="card-block">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Productos con Promoción:</h6>
                                </div>
                                <div class="col-sm-6 offset-6 text-right">
                                    <!--<button title="Seleccionar Todo" type="button" class="btn btn-grd-success btn-sm btn-round waves-effect waves-light m-b-10" id='SeleccionarTodo' ><i class="fa fa-plus mr-1"></i>
                                    </button>-->
                                    <button title="Quitar Todo" type="button" class="btn btn-grd-danger btn-sm btn-round waves-effect waves-light m-b-10" id='QuitarTodo'><i class="fa fa-trash mr-1"></i>
                                    </button>
                                </div>

                                <div class="col-6 text-center">
                                    <h4 class="">Productos sin Promción</h4>
                                </div>
                                <div class="col-6 text-center">
                                    <h4 class="">Productos con Promoción</h4>
                                </div>
                                <div class="col-12">
                                    <select id='selectPromocion' class="buscadoPromocion" multiple='multiple'>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>
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
<div class="modal fade" id="ModalPromocion" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="FormularioPromocion" method="POST" autocomplete="off">
                    <input type="hidden" name="PromocionidPromocion" id="PromocionidPromocion">
                    <div class="form-group row">
                        <div class="col-sm-12 center_element">
                            <h4 id="tituloModalPromocion"></h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Titulo de Promoción:</label>
                            <input type="text" class="form-control validarPanel" name="PromocionTitulo" id="PromocionTitulo" value="" maxlength="150">
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Link de Promocion:</label>
                            <textarea name="PromocionLink" id="PromocionLink" class="form textarea form-control" rows="3" maxlength="200"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Portada:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="images">
                                <div class="pic">Agregar Imagen</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col col-md-6 text-left">
                            <input type="button" class="btn btn-grd-danger btn-round btn-sm"  value="CANCELAR" onclick="Cancelar();"> </div>
                        <div class="col col-md-6 text-right">
                            <input type="submit" value="GUARDAR" class="btn btn-grd-primary btn-round btn-sm"> </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDescuento" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-12 center_element">
                            <h4 id="">Ingrese Descuento de Promoción (%)</h4>
                            <hr>
                        </div>
                    </div>
                    <input type="hidden" id="DescuentoO" value="0 %">
                    <input type="hidden" id="idProductoProm" value="0">
                    <div class="row form-group">
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Descuento:</label>
                            <input type="text" class="form-control validarPanel" name="Descuento" id="Descuento" value="0 %"  onkeypress="return SoloNumerosModificado(event,2,this.id);" >
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col col-md-6 text-left">
                            <input type="button" class="btn btn-grd-danger btn-round btn-sm"  value="CANCELAR" onclick="CancelarDescuento();"> </div>
                        <div class="col col-md-6 text-right">
                            <input type="button" value="GUARDAR" class="btn btn-grd-primary btn-round btn-sm" onclick="GuardarDescuento()"> </div>
                    </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/Promocion.js"></script>
