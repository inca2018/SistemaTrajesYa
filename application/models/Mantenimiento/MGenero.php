<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MGenero extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroGenero()
    {
        $data= array(
            'NombreGenero' =>mb_convert_case(mb_strtolower($this->input->post('GeneroTitulo')), MB_CASE_TITLE, "UTF-8"),
            'simbolo' =>mb_strtoupper($this->input->post('GeneroSimbolo')),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('genero', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Genero: ".$this->input->post('GeneroTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();


        return $insert_data;
    }
    public function UpdateGenero()
    {
        $data= array(
            'NombreGenero' => mb_convert_case(mb_strtolower($this->input->post('GeneroTitulo')), MB_CASE_TITLE, "UTF-8"),
            'simbolo' =>mb_strtoupper($this->input->post('GeneroSimbolo')),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idGenero', $_POST['GeneroidGenero']);
        $insert_data["Registro"] = $this->db->update('genero', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Genero: ".$this->input->post('GeneroTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarGenero()
    {

        $this->db->select('m.idGenero,m.simbolo,m.NombreGenero as Titulo,DATE_FORMAT(m.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(m.fechaUpdate,"%d/%m/%Y") as fechaUpdate,m.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('genero m');
        $this->db->join('estado e', 'e.idEstado=m.estado_idEstado');
        $this->db->order_by('m.idGenero', 'desc');
        return $this->db->get();
    }
    public function ObtenerGenero()
    {
        $this->db->where('idGenero', $_POST['idGenero']);
        $query = $this->db->get('genero');
        return $query->row();
    }
    public function EliminarGenero()
    {
         /** Recuperar Datos para Historial **/
        $this->db->where('idGenero', $_POST['idGenero']);
        $row = $this->db->get('genero');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Genero: ".$query->NombreGenero."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idGenero', $_POST['idGenero']);
        $delete_data["Delete"] = $this->db->delete('genero');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;

    }
    public function EstadoGenero($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idGenero', $_POST['idGenero']);
        $row = $this->db->get('genero');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Genero: ".$query->NombreGenero."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Genero: ".$query->NombreGenero."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }



        $this->db->where('idGenero', $_POST['idGenero']);
        $insert_data["accion"] = $this->db->update('genero', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MGenero.php */
