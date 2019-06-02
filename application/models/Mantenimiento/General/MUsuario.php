<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MUsuario extends CI_Model
{ 
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }   
    public function RegistroUsuario($password,$ruta_image)
    {
   
        $data                    = array(
            'usuario' => $this->input->post('UsuarioUsuario'),
            'password' => $password,
            'NombreUsuario' => ucwords($this->input->post('UsuarioNombre')),
            'ApellidosUsuario' => ucwords($this->input->post('UsuarioApellido')),
            'Dni' => $this->input->post('UsuarioDni'),
            'Cargo' => $this->input->post('UsuarioCargo'),
            'Correo' => $this->input->post('UsuarioCorreo'), 
            'fechaRegistro' => $this->glob['FechaAhora'],
            'fechaUpdate' => $this->glob['FechaAhora'],
            'imagen' => $ruta_image,
            'perfil_idPerfil' => $this->input->post('UsuarioPerfil'), 
            'area_idArea' => $this->input->post('UsuarioArea'),   
            'estado_idEstado' => 1 
        ); 
        $insert_data["Registro"] = $this->db->insert('usuario', $data);
        $insert_data["errDB"]    = $this->db->error();
        return $insert_data; 
         
    }
    public function UpdateUsuario($password,$ruta_image)
    {

        if ($password == '') {
            if($ruta_image== ''){
                $data                    = array(
                'usuario' => $this->input->post('UsuarioUsuario'),
                'NombreUsuario' => ucwords($this->input->post('UsuarioNombre')),
                'ApellidosUsuario' => ucwords($this->input->post('UsuarioApellido')),
                'Dni' => $this->input->post('UsuarioDni'),
                'Cargo' => $this->input->post('UsuarioCargo'),
                'Correo' => $this->input->post('UsuarioCorreo'),
                'fechaUpdate' => $this->glob['FechaAhora'],
                'perfil_idPerfil' => $this->input->post('UsuarioPerfil'),
                'area_idArea' => $this->input->post('UsuarioArea')
                );
            }else{
                 $data                    = array(
                'usuario' => $this->input->post('UsuarioUsuario'),
                'NombreUsuario' => ucwords($this->input->post('UsuarioNombre')),
                'ApellidosUsuario' => ucwords($this->input->post('UsuarioApellido')),
                'Dni' => $this->input->post('UsuarioDni'),
                'Cargo' => $this->input->post('UsuarioCargo'),
                'Correo' => $this->input->post('UsuarioCorreo'),
                'fechaUpdate' => $this->glob['FechaAhora'],
                'imagen' => $ruta_image,
                'perfil_idPerfil' => $this->input->post('UsuarioPerfil'),
                'area_idArea' => $this->input->post('UsuarioArea')
                );
            }

        } else {
            if($ruta_image== ''){
                 $data                    = array(
                'usuario' => $this->input->post('UsuarioUsuario'),
                'password' => $password,
                'NombreUsuario' => ucwords($this->input->post('UsuarioNombre')),
                'ApellidosUsuario' => ucwords($this->input->post('UsuarioApellido')),
                'Dni' => $this->input->post('UsuarioDni'),
                'Cargo' => $this->input->post('UsuarioCargo'),
                'Correo' => $this->input->post('UsuarioCorreo'),
                'fechaUpdate' => $this->glob['FechaAhora'],
                'perfil_idPerfil' => $this->input->post('UsuarioPerfil'),
                'area_idArea' => $this->input->post('UsuarioArea')
                );
            }else{
                 $data                    = array(
                'usuario' => $this->input->post('UsuarioUsuario'),
                'password' => $password,
                'NombreUsuario' => ucwords($this->input->post('UsuarioNombre')),
                'ApellidosUsuario' => ucwords($this->input->post('UsuarioApellido')),
                'Dni' => $this->input->post('UsuarioDni'),
                'Cargo' => $this->input->post('UsuarioCargo'),
                'Correo' => $this->input->post('UsuarioCorreo'),
                'fechaUpdate' => $this->glob['FechaAhora'],
                'imagen' => $ruta_image,
                'perfil_idPerfil' => $this->input->post('UsuarioPerfil'),
                'area_idArea' => $this->input->post('UsuarioArea')
                );
            }

        }
        $this->db->where('idUsuario', $_POST['idUsuario']);
        $insert_data["Registro"] = $this->db->update('usuario', $data);
        $insert_data["errDB"]    = $this->db->error();
        return $insert_data;
    }  
    public function ListarUsuario()
    {
        $this->db->select('u.idUsuario,CONCAT(u.NombreUsuario," ",u.ApellidosUsuario) as NombreUsuario,u.usuario,u.Dni,u.Cargo,u.Correo,u.imagen,u.estado_idEstado,DATE_FORMAT(u.fechaUpdate,"%d/%m/%Y %h:%m %p") as fechaUpdate,DATE_FORMAT(u.fechaRegistro,"%d/%m/%Y %h:%m %p") as fechaRegistro,e.DescripcionEstado as nombreEstado,e.idEstado,p.idPerfil,p.DescripcionPerfil as Perfil');
        $this->db->from('usuario u');     
        $this->db->join('estado e', 'e.idEstado=u.estado_idEstado');
        $this->db->join('perfil p', 'p.idPerfil=u.perfil_idPerfil');
        $this->db->order_by('u.idUsuario', 'desc'); 
        
        return $this->db->get();   
    } 
    public function ObtenerUsuario()
    {
        $this->db->where('idUsuario', $_POST['idUsuario']);
        $query = $this->db->get('usuario');
        return $query->row();
    }
    public function EliminarUsuario()
    {
        $this->db->where('idUsuario', $_POST['idUsuario']);
        
        $delete_data["Delete"] = $this->db->delete('usuario');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;
        
    }   
    public function EstadoUsuario($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );
        
        $this->db->where('idUsuario', $_POST['idUsuario']);
        $insert_data["accion"] = $this->db->update('usuario', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }
    
    public function ListarTipoPerfil()
    { 
          $query=$this->db->select("*");
          $this->db->from('perfil');
          $this->db->where('estado_idEstado','1');
          return $this->db->get();          
    }
    public function ListarTipoArea()
    { 
          $query=$this->db->select("*");
          $this->db->from('area');
          $this->db->where('estado_idEstado','1');
          return $this->db->get();          
    }
}
/* End of file MUsuario.php */
