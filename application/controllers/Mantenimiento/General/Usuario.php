<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/General/MUsuario');
        $this->load->model('Util/Crypto');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/General/Usuario');
    }
    public function BuscarEstado($reg)
    {
        if ($reg->estado_idEstado == '1' || $reg->estado_idEstado == 1) {
            return '<div class="badge badge-success">' . $reg->nombreEstado . '</div>';
        } elseif ($reg->estado_idEstado == '2' || $reg->estado_idEstado == 2) {
            return '<div class="badge badge-danger">' . $reg->nombreEstado . '</div>';
        } else {
            return '<div class="badge badge-primary">' . $reg->nombreEstado . '</div>';
        }
    } 
     public function BuscarPerfil($reg)
    {
        if ($reg->idPerfil == '1' || $reg->idPerfil == 1) {
            return '<div class="badge badge-primary">' . $reg->Perfil . '</div>';
        } elseif ($reg->idPerfil == '2' || $reg->idPerfil == 2) {
            return '<div class="badge badge-secondary">' . $reg->Perfil . '</div>';
        } else{
             return '<div class="badge badge-info">' . $reg->Perfil . '</div>';
        }   
    } 
    public function BuscarImagen($reg){
        if($reg->imagen==null){
            return 'Sin Imagen';
        }else{
           return  ' <a href="../../assets/images/'.$reg->imagen.'" data-lightbox="1" data-title="'.$reg->NombreUsuario.'">
                                                                            <img src="../../assets/images/'.$reg->imagen.'" class="img-fluid rounded img-30">
                                                                        </a>';
        }



    }     
      
    public function BuscarAccion($reg)
    {
        if ($reg->estado_idEstado == 1) {
            return '
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarUsuario(' . $reg->idUsuario . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarUsuario(' . $reg->idUsuario . ",'" . $reg->usuario . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarUsuario(' . $reg->idUsuario . ",'" . $reg->usuario . "'" . ')"><i class="fa fa-trash"></i></button>    
               '; 
        } elseif ($reg->estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarUsuario(' . $reg->idUsuario . ",'" . $reg->usuario . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarUsuario(' . $reg->idUsuario . ",'" . $reg->usuario . "'" . ')"><i class="fa fa-trash"></i></button> ';
        }
    }
    public function ListarUsuario()
    { 
        $rspta = $this->MUsuario->ListarUsuario();
        $data  = array();
        
        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => $reg->NombreUsuario,
                "1" => $this->BuscarImagen($reg),
                "2" => $reg->usuario,
                "3" => $this->BuscarPerfil($reg),
                "4" => $this->BuscarAccion($reg),
                "5" => $reg->Dni,
                "6" => $reg->Cargo,
                "7" => $reg->Correo,
                "8" => $reg->fechaRegistro,
                "9" => $reg->fechaUpdate, 
                "10" => $this->BuscarEstado($reg)
            );
        }
        
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
    }
    
     function check_default($post_string)
    {
      return $post_string == '0' ? FALSE : TRUE;
    } 
     function check_default2($post_string)
    {
      return $post_string == '0' ? FALSE : TRUE;
    } 
    public function InsertUpdateUsuario()
    {
 
         
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        
        $this->form_validation->set_rules('UsuarioNombre', 'Nombre de Usuario', 'trim|required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('UsuarioApellido', 'Apellido de Usuario', 'trim|required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('UsuarioDni', 'Dni de Usuario', 'trim|required|min_length[8]|max_length[8]');
        
        $this->form_validation->set_rules('UsuarioPerfil', 'Perfil de Usuario', 'required|callback_check_default'); 
        $this->form_validation->set_message('check_default', 'El campo Perfil es Obligatorio'); 
        
        $this->form_validation->set_rules('UsuarioArea', 'Area de Usuario', 'required|callback_check_default2'); 
        $this->form_validation->set_message('check_default2', 'El campo Area es Obligatorio');    
         
        $this->form_validation->set_rules('UsuarioUsuario', 'Usuario', 'trim|required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('UsuarioPass', 'Contraseña', 'trim|min_length[6]|max_length[20]');
 
    
        if ($this->form_validation->run() == true) {
            /* Registras usuario */
            if (empty($_POST['UsuarioidUsuario'])) {
                /* valida usuario */
                $usuarioConsulta = $this->Recurso->Validaciones('usuario', 'usuario', $_POST['UsuarioUsuario']);
                if ($usuarioConsulta > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Usuario:  "' . $_POST['UsuarioUsuario'] . '" , ya se encuentra registrado ';
                }
                
                
                
                $Documento = "";
                if ($_FILES["UsuarioImagen"]["name"] != '') {
                    $tipoFile = $_FILES['UsuarioImagen']['type'];
                    if ($tipoFile == "image/jpeg") {
                        $Documento = "Usuarios/".$_POST['UsuarioDni'].".jpg";
                    } else {
                        $Documento      = null;
                        $data["Error"] = true;
                        $data["Mensaje"] .= " Documento Adjunto no es un Archivo de Imagen válido.";
                    }
                } else {
                    $Documento = null;
                }
 
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $password = $this->Crypto->Encriptar($_POST['UsuarioPass']);
                                       
                    $registro = $this->MUsuario->RegistroUsuario($password,$Documento);

                    if($Documento!=null || $Documento!=''){
                        $Subida= $this->Recurso->upload_documento("UsuarioImagen","Usuarios",1,$_POST['UsuarioDni']);
                        }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Cliente Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar usuario */
                /* valida usuario */
                $usuarioConsulta = $this->Recurso->Validaciones('usuario', 'usuario', $_POST['UsuarioUsuario'], 'idUsuario', $_POST['UsuarioidUsuario']);
                if ($usuarioConsulta > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Usuario:' . $_POST['UsuarioUsuario'] . ' ya se encuentra registrado <br>';    
                }
             
                
                $Documento = "";
                if ($_FILES["UsuarioImagen"]["name"] != '') {
                    $tipoFile = $_FILES['UsuarioImagen']['type'];
                    if ($tipoFile == "image/jpeg") {
                        $Documento = "Usuarios/".$_POST['UsuarioDni'].".jpg";
                    } else {
                        $Documento      = null;
                        $data["Error"] = true;
                        $data["Mensaje"] .= " Documento Adjunto no es un Archivo de Imagen válido.";
                    }
                } else {
                    $Documento = null;
                }

                
                
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    if ($_POST['UsuarioPass'] != '') {
                        $password = $this->Crypto->Encriptar($_POST['UsuarioPass']);
                        
                    } else {
                        $password = '';
                    }
                    
                    $registro = $this->MUsuario->UpdateUsuario($password,$Documento); 


                        if($Documento!=null || $Documento!=''){
                        $Subida= $this->Recurso->upload_documento("UsuarioImagen","Usuarios",2,$_POST['UsuarioDni']);
                        }
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Usuario Modificado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            }
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'warning',
                'Mensaje' => validation_errors('<li>', '</li>')
            );
        }
        echo json_encode($data);
    }
    public function ObtenerUsuario()
    {
        $data = $this->MUsuario->ObtenerUsuario();
        echo json_encode($data);
    }
    public function EliminarUsuario()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

         $respta = $this->MUsuario->ObtenerUsuario();
            if($respta->imagen!=null){
               $linkEliminar='assets/images/'.$respta->imagen;
                if(file_exists($linkEliminar)){
                     unlink($linkEliminar);
                  }
            }

        $delete = $this->MUsuario->EliminarUsuario();

        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Usuario Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }
        
        echo json_encode($data);
    }
    public function HabilitarUsuario()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        
        $enable = $this->MUsuario->EstadoUsuario(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Usuario Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }
        
        echo json_encode($data);
    }
    public function InhabilitarUsuario()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        
        $disable = $this->MUsuario->EstadoUsuario(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Usuario Inhabilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Inhabilitar en base de datos Error:' . $disable["errDB"]["code"] . ':' . $disable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }
        
        echo json_encode($data);
    }
    public function ListarTipoPerfil(){
         echo '<option value="0"> --- Perfil --- </option>'; 
      		 $rspta = $this->MUsuario->ListarTipoPerfil();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idPerfil . '>' . $reg->DescripcionPerfil . '</option>';
            }    
    }
    public function ListarTipoArea(){
         echo '<option value="0"> --- Area --- </option>'; 
      		 $rspta = $this->MUsuario->ListarTipoArea();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idArea . '>' . $reg->DescripcionArea . '</option>';
            }     
    }
}
/* End of file MenuPhp.php */
