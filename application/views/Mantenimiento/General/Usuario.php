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
                            <div class="page-header-breadcrumb">
                                <ul class="breadcrumb-title">
                                    <li class="breadcrumb-item">
                                        <a href="/Menu"> <i class="feather icon-home"></i> </a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Gestión</a> </li>
                                    <li class="breadcrumb-item"><a href="/Mantenimiento/General/Usuario">Usuarios</a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-block">
                            <h5 class="sub-title">Lista de Usuarios</h5>
                            <div class="row">
                                <div class="col-md-3 ml-md-auto col-sm-6 ml-sm-auto">
                                    <button type="button" class="btn btn-grd-success btn-block btn-sm btn-round" onclick="NuevoUsuario();">Nuevo Usuario</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="dt-responsive">
                                        <table class="table table-sm  w-100 table-hover" id="tablaUsuario">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Nombre de Usuario</th>
                                                    <th>Foto</th>
                                                    <th>Usuario</th>
                                                    <th>Perfil</th>
                                                    <th>Acciones</th>
                                                    <th>Dni</th>
                                                    <th>Cargo</th>
                                                    <th>Correo</th>
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
    </div>
</div>
<?php
   $this->load->view('Layout/Footer'); 
?>
<div class="modal fade" id="ModalUsuario" role="dialog" aria-labelledby="myModalLabelLarge" aria-hidden="true" style="z-index:10001 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="FormularioUsuario" method="POST" autocomplete="off">
                    <input type="hidden" id="UsuarioidUsuario" name="UsuarioidUsuario" value="">
                    <div class="form-group row">
                        <div class="col-sm-12 center_element">
                            <h4 id="tituloModalUsuario"></h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12 text-center">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="UsuarioImagen" name="UsuarioImagen" accept=".png, .jpg, .jpeg" />
                                    <label for="UsuarioImagen"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('/assets/images/usuario_default.svg'); width: 100; height: 100;">
                                    </div> 
                                </div>
                            </div> 
                        </div> 
                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">Nombres:</label>
                            <input type="text" class="form-control" name="UsuarioNombre" id="UsuarioNombre"  maxlength="50" >
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">Apellidos:</label>
                            <input type="text" class="form-control" name="UsuarioApellido" id="UsuarioApellido"  maxlength="100"> 
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label class="col-form-label">Dni:</label>
                            <input type="text" class="form-control" name="UsuarioDni" id="UsuarioDni" onkeypress="return SoloNumerosModificado(event,8,this.id);">
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label class="col-form-label">Cargo:</label>
                            <input type="text" class="form-control" name="UsuarioCargo" id="UsuarioCargo" maxlength="150" >
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label class="col-form-label">Perfil:</label>
                            <select class="form-control" id="UsuarioPerfil" name="UsuarioPerfil"> </select>
                        </div>  
                        <div class="col-sm-3 col-md-3">
                            <label class="col-form-label">Area:</label>
                            <select class="form-control" id="UsuarioArea" name="UsuarioArea"> </select>   
                        </div>     

                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">Usuario:</label>
                            <input type="text" class="form-control" name="UsuarioUsuario" id="UsuarioUsuario"  maxlength="20" >
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">Contraseña:</label>
                            <input type="text" class="form-control" name="UsuarioPass" id="UsuarioPass" maxlength="20" >
                        </div> 
                        <div class="col-sm-6 col-md-6">
                            <label class="col-form-label">Correo:</label>
                            <input type="email" class="form-control" name="UsuarioCorreo" id="UsuarioCorreo" maxlength="150" >
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col col-md-6   text-left">
                            <input type="button" class="btn btn-grd-danger btn-sm btn-round" value="CANCELAR" onclick="Cancelar();"> </div>
                        <div class="col col-md-6 text-right">
                            <input type="submit" value="GUARDAR" class="btn btn-grd-primary btn-sm btn-round"> </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Mantenimiento/General/Usuario.js"></script>
