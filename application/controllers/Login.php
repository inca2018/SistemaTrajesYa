<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
 
     public function __construct()
    {
        parent::__construct();
        $this->load->model('MLogin');
        $this->load->model('Util/Descrypto');
    }
 
   public function index()
   {
      $this->load->view('Login');
   } 
    
   public function Logeo()
   {

      $this->form_validation->set_rules('L_User', 'Usuario', 'trim|required|min_length[3]|max_length[20]');
      $this->form_validation->set_rules('L_Password', 'Contraseña', 'trim|required|min_length[5]|max_length[60]');
     
        if($this->form_validation->run() == TRUE){

            $obtenerUsuario=$this->MLogin->validaUsuario();

            if($obtenerUsuario!=null){ 
                //Pass Recuperado
                $L_Password = $this->input->post('L_Password');
                //Pas Desecryptado
                $passwordDesencriptado=$this->Descrypto->Desencriptar($obtenerUsuario->password);

                if($passwordDesencriptado===$L_Password){
                    // grabar usuario
                    $user_data = array(
                    'idLogin' => $obtenerUsuario->idUsuario, 
                    'idUsuario' => $obtenerUsuario->idUsuario,
                    'usuario' => $obtenerUsuario->usuario,
                    'NombreUsuario'=>$obtenerUsuario->usuarioNombres,
                    'DniUsuario'=>$obtenerUsuario->Dni,
                    'CargoUsuario'=>$obtenerUsuario->Cargo,
                    'Correo'=>$obtenerUsuario->Correo,
                    'idPerfil' => $obtenerUsuario->idPerfil,
                    'Perfil' => $obtenerUsuario->perfilUsuario,
                    'idArea' => $obtenerUsuario->idArea,    
                    'Area' => $obtenerUsuario->areaUsuario,
                    'idEstado' => $obtenerUsuario->idEstado,    
                    'Estado' => $obtenerUsuario->estadoUsuario,
                    'FechaRegistro' => $obtenerUsuario->fechaRegistro,          
                    'logged_in' => true  
                    );            
                    $this->session->set_userdata($user_data);
                    redirect('Menu');
                }else{
                    $data = array(
                    'errors' => 'Contraseña invalido.'
                    );
                    $this->session->set_flashdata($data);
                    redirect('Login');
                } 
                
            }else{
                    $data = array(
                    'errors' => 'No se encontro Usuario.'
                    );
                    $this->session->set_flashdata($data);
                    redirect('Login'); 
            }
            
        }else{
            $data = array(
            'errors' => validation_errors()
            );
            $this->session->set_flashdata($data);
            redirect('Login');  
        }
    
   }

}

/* End of file Controllername.php */
