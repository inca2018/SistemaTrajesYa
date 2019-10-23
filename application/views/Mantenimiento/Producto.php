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
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Producto">Productos</a> </li>
                                </ul>
                            </div>

                        </div>

                        <div class="card-block">
                           <h5 class="col-md-12 sub-title">Lista de Productos</h5>
                            <div class="row">
                                <div class="col-md-3 ml-md-auto col-sm-6 ml-sm-auto">
                                    <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoProducto();">Nuevo Producto</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                   <div class="dt-responsive">
                                    <table class="table table-sm  w-100 table-hover" id="tablaProducto">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>Codigo de Producto</th>
                                                <th>Titulo de Producto</th>
                                                <th>Verificado Por</th>
                                                <th>Portada</th>
                                                <th>Categoria/SubCategoria</th>
                                                <th>Origen</th>
                                                 <th>Descripción</th>
                                                <th>Acción</th>
                                                <th>Galeria</th>
                                                <th>Tarifa</th>
                                                <th>Medidas</th>
                                                <th>Generos</th>
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
<div class="modal fade" id="ModalProducto" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="FormularioProducto" method="POST" autocomplete="off">
                    <input type="hidden" name="ProductoidProducto" id="ProductoidProducto">
                    <div class="form-group row">
                        <div class="col-sm-12 center_element">
                            <h4 id="tituloModalProducto"></h4>
                            <hr>
                        </div>
                    </div>
                     <div class="row form-group">
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Titulo del Producto:</label>
                            <input type="text" class="form-control validarPanel" name="ProductoTitulo" id="ProductoTitulo" value=""  maxlength="60">
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Descripción de Categoria:</label>
                            <textarea name="ProductoDescripcion" id="ProductoDescripcion" class="form textarea form-control" rows="3" ></textarea>
                        </div>

                         <div class="col-sm-9 col-md-9">
                            <label class="col-form-label">Verificado Por:</label>
                            <input type="text" class="form-control validarPanel" name="ProductoVerificado" id="ProductoVerificado" value=""  maxlength="150">
                        </div>
                          <div class="col-sm-3 col-md-3 mt-4">
                             <div class="checkbox-color checkbox-warning mt-3">
                                <input id="ProductoTendencia" type="checkbox">
                                <label for="ProductoTendencia">
                                    Tendencia
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">Categoria:</label>
                            <select class="form-control" id="ProductoCategoria" name="ProductoCategoria"> </select>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">SubCategoria:</label>
                            <select class="form-control" id="ProductoSubCategoria" name="ProductoSubCategoria">
                            <option value="0">--- SELECCIONE ---</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group" id="AreaUbicacion" style="display:none;">
                        <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Departamento:</label>
                            <select class="form-control" id="ProductoDepartamento" name="ProductoDepartamento">
                             <option value="">--- SELECCIONE ---</option></select>
                        </div>
                         <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Provincia:</label>
                            <select class="form-control" id="ProductoProvincia" name="ProductoProvincia">
                             <option value="">--- SELECCIONE ---</option></select>
                        </div>
                         <div class="col-sm-4 col-md-4">
                            <label class="col-form-label">Distrito:</label>
                            <select class="form-control" id="ProductoDistrito" name="ProductoDistrito">
                             <option value="">--- SELECCIONE ---</option> </select>
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
                            <input type="button" class="btn btn-grd-danger btn-round btn-sm" value="CANCELAR" onclick="Cancelar();"> </div>
                        <div class="col col-md-6 text-right">
                            <input type="submit" value="GUARDAR" class="btn btn-grd-primary btn-round btn-sm"> </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/Producto.js"></script>
