<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MTipoTarjeta extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroTipoTarjeta()
    {
        $data= array(
            'NombreTarjeta' => mb_convert_case(mb_strtolower($this->input->post('TipoTarjetaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('tipo_tarjeta', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo TipoTarjeta: ".$this->input->post('TipoTarjetaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateTipoTarjeta()
    {
        $data= array(
            'NombreTarjeta' =>mb_convert_case(mb_strtolower($this->input->post('TipoTarjetaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idTipoTarjeta', $_POST['TipoTarjetaidTipoTarjeta']);
        $insert_data["Registro"] = $this->db->update('tipo_tarjeta', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  TipoTarjeta: ".$this->input->post('TipoTarjetaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarTipoTarjeta()
    {

        $this->db->select('p.idTipoTarjeta,p.NombreTarjeta as Titulo,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('tipo_tarjeta p');
        $this->db->join('estado e', 'e.idEstado=p.Estado_idEstado');
        $this->db->order_by('p.idTipoTarjeta', 'desc');
        return $this->db->get();
    }
    public function ObtenerTipoTarjeta()
    {
        $this->db->where('idTipoTarjeta', $_POST['idTipoTarjeta']);
        $query = $this->db->get('tipo_tarjeta');
        return $query->row();
    }
    public function EliminarTipoTarjeta()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idTipoTarjeta', $_POST['idTipoTarjeta']);
        $row = $this->db->get('tipo_tarjeta');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  TipoTarjeta: ".$query->NombreTarjeta."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idTipoTarjeta', $_POST['idTipoTarjeta']);
        $delete_data["Delete"] = $this->db->delete('tipo_tarjeta');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;

    }
    public function EstadoTipoTarjeta($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idTipoTarjeta', $_POST['idTipoTarjeta']);
        $row = $this->db->get('tipo_tarjeta');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  TipoTarjeta: ".$query->NombreTarjeta."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  TipoTarjeta: ".$query->NombreTarjeta."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }

        $this->db->where('idTipoTarjeta', $_POST['idTipoTarjeta']);
        $insert_data["accion"] = $this->db->update('tipo_tarjeta', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MTipoTarjeta.php */
