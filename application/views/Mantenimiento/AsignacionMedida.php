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
                            <input type="hidden" id="idProducto" value="<?php echo $_POST["idProducto"]; ?>">
                            <div class="page-header-breadcrumb">
                                <ul class="breadcrumb-title">
                                    <li class="breadcrumb-item">
                                        <a href="/Menu"> <i class="feather icon-home"></i> </a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestión</a> </li>
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/Producto">Productos</a> </li>
                                    <li class="breadcrumb-item"><a href="#">Asignación de Medidas</a> </li>
                                </ul>
                            </div>

                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Información del Producto:</h6>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <p class="m-b-10 f-w-600 ">Nombre del Producto:</p>
                                            <h6 class="text-muted f-w-400 " id="DetalleTitulo"></h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Asignación de Medidas Disponibles:</h6>
                                </div>
                                <div class="col-sm-6 offset-6 text-right">
                                    <button title="Seleccionar Todo" type="button" class="btn btn-grd-success btn-sm btn-round waves-effect waves-light m-b-10" id='SeleccionarTodo'><i class="fa fa-plus mr-1"></i>
                                    </button>
                                    <button title="Quitar Todo" type="button" class="btn btn-grd-danger btn-sm btn-round waves-effect waves-light m-b-10" id='QuitarTodo'><i class="fa fa-trash mr-1"></i>
                                    </button>
                                </div>

                                <div class="col-6 text-center">
                                    <h4 class="">Medidas no Asignadas</h4>
                                </div>
                                <div class="col-6 text-center">
                                    <h4 class="">Medidas Asignadas</h4>
                                </div>
                                <div class="col-12">
                                    <select id='selectMedidas' class="buscadorMedida" multiple='multiple'>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
   $this->load->view('Layout/Footer');
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/AsignacionMedida.js"></script>
