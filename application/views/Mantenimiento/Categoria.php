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
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Categoria">Categoria</a> </li>
                                </ul>
                            </div>

                        </div>

                        <div class="card-block">
                            <h5 class="col-md-12 sub-title">Lista de Categorias</h5>
                            <div class="row">
                                <div class="col-md-3 ml-md-auto col-sm-6 ml-sm-auto">
                                    <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoCategoria();">Nuevo Categoria</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="dt-responsive">
                                        <table class="table table-sm  w-100 table-hover" id="tablaCategoria">
                                            <thead class="thead-light text-center">
                                                <tr>
                                                    <th>Titulo de Categoria</th>
                                                    <th>Imagen Portada</th>
                                                    <th>Grupo</th>
                                                    <th>Descripción</th>
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
            <span class="text-center text-muted">ColorLib - FuturoBlanquiazul 2019</span>
        </footer>
    </div>
</div>
<?php
   $this->load->view('Layout/Footer');
?>
<div class="modal fade" id="ModalCategoria" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="FormularioCategoria" method="POST" autocomplete="off">
                    <input type="hidden" name="CategoriaidCategoria" id="CategoriaidCategoria">
                    <div class="form-group row">
                        <div class="col-sm-12 center_element">
                            <h4 id="tituloModalCategoria"></h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Titulo de Categoria:</label>
                            <input type="text" class="form-control validarPanel" name="CategoriaTitulo" id="CategoriaTitulo" value="" maxlength="60">
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">Grupo de Categoria:</label>
                            <select class="form-control" id="CategoriaGrupo" name="CategoriaGrupo"> </select>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <label class="col-form-label">Descripción de Categoria:</label>
                            <textarea name="CategoriaDescripcion" id="CategoriaDescripcion" class="form textarea form-control" rows="3" maxlength="200"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4 col-md-4 AreaArchivos mb-3 mt-3">
                            <label class="col-form-label">Portada: (Resolucion Permitida 250px x 250px)</label>
                            <input class="form-control uploadFile" type="file" name="files[]" id="PortadaCategoria" multiple="multiple">
                            <label for="PortadaCategoria" class="form-control btn btn-success btn-round btn-sm">Buscar Imagenes</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-12"  id="Archivos">

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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/Categoria.js"></script>
