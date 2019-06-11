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
                                <div class="col-sm-12 text-center">
                                     <h4 class="d-line text-center">Bienvenido al Sistema de Administración de TrajesYa!.</h4>
                                </div>                             
                                <hr>
                                <div class="col-sm-12">
                                    <i class="icofont icofont-double-right text-info"></i> Registro de Mantenimientos.
                                </div>
                                <div class="col-sm-12">
                                    <i class="icofont icofont-double-right text-info"></i> Registro de Usuarios.
                                </div>
                                <div class="col-sm-12">
                                    <i class="icofont icofont-double-right text-info"></i> Información de Pedidos.
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
