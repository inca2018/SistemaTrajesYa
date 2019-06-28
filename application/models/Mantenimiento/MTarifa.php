<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MTarifa extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroTarifa()
    {
        $data= array(
            'Descripcion' => mb_convert_case(mb_strtolower($this->input->post('TarifaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('Tarifa', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Tarifa: ".$this->input->post('TarifaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateTarifa()
    {
        $data= array(
            'Descripcion' =>mb_convert_case(mb_strtolower($this->input->post('TarifaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idTarifa', $_POST['TarifaidTarifa']);
        $insert_data["Registro"] = $this->db->update('Tarifa', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Tarifa: ".$this->input->post('TarifaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarTarifa()
    {

        $this->db->select('p.idTarifa,p.Descripcion as Titulo,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('Tarifa p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idTarifa', 'desc');
        return $this->db->get();
    }
    public function ObtenerTarifa()
    {
        $this->db->where('idTarifa', $_POST['idTarifa']);
        $query = $this->db->get('Tarifa');
        return $query->row();
    }
    public function EliminarTarifa()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idTarifa', $_POST['idTarifa']);
        $row = $this->db->get('Tarifa');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Tarifa: ".$query->Descripcion."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idTarifa', $_POST['idTarifa']);
        $delete_data["Delete"] = $this->db->delete('Tarifa');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;

    }
    public function EstadoTarifa($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idTarifa', $_POST['idTarifa']);
        $row = $this->db->get('Tarifa');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Tarifa: ".$query->Descripcion."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Tarifa: ".$query->Descripcion."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }

        $this->db->where('idTarifa', $_POST['idTarifa']);
        $insert_data["accion"] = $this->db->update('Tarifa', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MTarifa.php */
