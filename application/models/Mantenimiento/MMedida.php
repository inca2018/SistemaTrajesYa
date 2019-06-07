<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MMedida extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroMedida()
    {
        $data= array(
            'NombreMedida' =>mb_convert_case(mb_strtolower($this->input->post('MedidaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('medida', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Medida: ".$this->input->post('MedidaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();


        return $insert_data;
    }
    public function UpdateMedida()
    {
        $data= array(
            'NombreMedida' => $this->mb_convert_case(mb_strtolower($this->input->post('MedidaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idMedida', $_POST['MedidaidMedida']);
        $insert_data["Registro"] = $this->db->update('medida', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Medida: ".$this->input->post('MedidaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarMedida()
    {

        $this->db->select('m.idMedida,m.NombreMedida as Titulo,DATE_FORMAT(m.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(m.fechaUpdate,"%d/%m/%Y") as fechaUpdate,m.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('medida m');
        $this->db->join('estado e', 'e.idEstado=m.estado_idEstado');
        $this->db->order_by('m.idMedida', 'desc');
        return $this->db->get();
    }
    public function ObtenerMedida()
    {
        $this->db->where('idMedida', $_POST['idMedida']);
        $query = $this->db->get('medida');
        return $query->row();
    }
    public function EliminarMedida()
    {
         /** Recuperar Datos para Historial **/
        $this->db->where('idMedida', $_POST['idMedida']);
        $row = $this->db->get('medida');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Medida: ".$query->NombreMedida."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idMedida', $_POST['idMedida']);
        $delete_data["Delete"] = $this->db->delete('medida');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;

    }
    public function EstadoMedida($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idMedida', $_POST['idMedida']);
        $row = $this->db->get('medida');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Medida: ".$query->NombreMedida."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Medida: ".$query->NombreMedida."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }



        $this->db->where('idMedida', $_POST['idMedida']);
        $insert_data["accion"] = $this->db->update('medida', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MMedida.php */
