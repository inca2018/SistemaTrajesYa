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
            'DescripcionPerfil' =>mb_convert_case(mb_strtolower($this->input->post('PerfilTitulo')),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('perfil', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Perfil: ".$this->input->post('PerfilTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdatePerfil()
    {
        $data= array(
            'DescripcionPerfil' => mb_convert_case(mb_strtolower($this->input->post('PerfilTitulo')),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idPerfil', $_POST['PerfilidPerfil']);
        $insert_data["Registro"] = $this->db->update('perfil', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Perfil: ".$this->input->post('PerfilTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

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

         /** Recuperar Datos para Historial **/
        $this->db->where('idPerfil', $_POST['idPerfil']);
        $row = $this->db->get('perfil');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Perfil: ".$query->DescripcionPerfil."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idPerfil', $_POST['idPerfil']);
        $delete_data["Delete"] = $this->db->delete('perfil');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;

    }
    public function EstadoPerfil($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idPerfil', $_POST['idPerfil']);
        $row = $this->db->get('perfil');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Perfil: ".$query->DescripcionPerfil."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Perfil: ".$query->DescripcionPerfil."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }

        $this->db->where('idPerfil', $_POST['idPerfil']);
        $insert_data["accion"] = $this->db->update('perfil', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MPerfil.php */
