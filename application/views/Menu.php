<?php 

$this->load->view('Layout/Header'); 
$this->load->view('Layout/Nav'); 

?>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body text-center">

                    <div class="row" id="PanelUsuario">
                        <div class="col-sm-12">
                            <div class="row">
                                <!-- visitors  start -->
                                <div class="col-sm-3">
                                    <div class="card bg-c-blue text-white widget-visitor-card">
                                        <div class="card-block-small text-center">
                                            <h2>5,678</h2>
                                            <h6>Daily visitor</h6>
                                            <i class="feather icon-file-text"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card bg-c-green text-white widget-visitor-card">
                                        <div class="card-block-small text-center">
                                            <h2>5,678</h2>
                                            <h6>Daily visitor</h6>
                                            <i class="feather icon-file-text"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card bg-simple-c-orenge text-white widget-visitor-card">
                                        <div class="card-block-small text-center">
                                            <h2>5,678</h2>
                                            <h6>Last month visitor</h6>
                                            <i class="feather icon-award"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card bg-c-pink text-white widget-visitor-card">
                                        <div class="card-block-small text-center">
                                            <h2>1,658</h2>
                                            <h6>Daily user</h6>
                                            <i class="feather icon-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                     <h4 class="d-line text-center">Bienvenido al Sistema de Registro de Horas.</h4> 
                                </div>                             
                                <hr>
                                <div class="col-sm-12">
                                    <i class="icofont icofont-double-right text-info"></i> Registro de Actividades diarias.
                                </div>
                                <div class="col-sm-12">
                                    <i class="icofont icofont-double-right text-info"></i> Registro de Tareas.
                                </div>
                                <div class="col-sm-12">
                                    <i class="icofont icofont-double-right text-info"></i> Asignación de Tareas y empleados.
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Menu.js"></script>
