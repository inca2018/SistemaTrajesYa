<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MPerfil extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroPerfil()
    {
        $data= array(
            'DescripcionPerfil' => $this->input->post('PerfilTitulo'),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('perfil', $data);
        $insert_data["errDB"]    = $this->db->error();
        return $insert_data;
    }
    public function UpdatePerfil()
    {
        $data= array(
            'DescripcionPerfil' => $this->input->post('PerfilTitulo'),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idPerfil', $_POST['PerfilidPerfil']);
        $insert_data["Registro"] = $this->db->update('perfil', $data);
        $insert_data["errDB"]    = $this->db->error();
        return $insert_data;
    }
    public function ListarPerfil()
    {

        $this->db->select('p.idPerfil,p.DescripcionPerfil as Titulo,p.permisos,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('perfil p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idPerfil', 'desc');
        return $this->db->get();
    }
    public function ObtenerPerfil()
    {
        $this->db->where('idPerfil', $_POST['idPerfil']);
        $query = $this->db->get('perfil');
        return $query->row();
    }
    public function EliminarPerfil()
    {
        $this->db->where('idPerfil', $_POST['idPerfil']);

        $delete_data["Delete"] = $this->db->delete('Perfil');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;

    }
    public function EstadoPerfil($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

        $this->db->where('idPerfil', $_POST['idPerfil']);
        $insert_data["accion"] = $this->db->update('perfil', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MPerfil.php */
