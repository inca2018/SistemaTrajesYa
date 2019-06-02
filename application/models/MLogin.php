<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora=date("Y-m-d H:i:s");

$GLOBALS = array(
   'FechaAhora' => $FechaAhora
);
 
class MLogin extends CI_Model {
   protected $glob;

   public function __construct()
   {
      parent::__construct();
      global $GLOBALS;
      $this->glob =& $GLOBALS;
   }    
  
   public function validaUsuario()
   {
  
      $where = array(
        'u.usuario' => $this->input->post('L_User')
      ); 
      $this->db->select('u.idUsuario,u.usuario,u.password,CONCAT(u.NombreUsuario," ",u.ApellidosUsuario) as usuarioNombres,u.Dni,u.imagen,u.Cargo,u.Correo,DATE_FORMAT(u.fechaRegistro,"%d/%m/%Y") as fechaRegistro,u.imagen,p.idPerfil,p.DescripcionPerfil as perfilUsuario,a.idArea,a.DescripcionArea as areaUsuario,e.idEstado,e.DescripcionEstado as estadoUsuario');
      $this->db->from('usuario u'); 
      $this->db->join('perfil p','p.idPerfil=u.perfil_idPerfil');
      $this->db->join('area a','a.idArea=u.area_idArea'); 
      $this->db->join('estado e','e.idEstado=u.estado_idEstado'); 
      $this->db->where($where);
      $query=$this->db->get();    
      return $query->row();   
   }
    
   public function CerrarSession($idLogin)
   {
      $data=array(
         "L_FechaSession"=>$this->glob['FechaAhora']
      );
      $where=array(
         "idLogin"=>$idLogin
      );
      $this->db->where($where); 
      $Update_data["Update"] = $this->db->update('login', $data); 
      $Update_data["errDB"]=$this->db->error();
      return $Update_data;
   }
   
}

/* End of file MUser.php */
