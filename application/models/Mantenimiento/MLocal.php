<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MLocal extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroLocal($Documento)
    {
        $data= array(
            'NombreLocal' =>mb_convert_case(mb_strtolower($this->input->post('LocalTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Direccion' => $this->input->post('LocalDireccion'),
            'Encargado' =>mb_convert_case(mb_strtolower($this->input->post('LocalEncargado')), MB_CASE_TITLE, "UTF-8"),
            'HorarioAtencion' => $this->input->post('LocalHorarioAtencion'),
            'imagenPortada' =>$Documento,
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('local', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Local: ".$this->input->post('LocalTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateLocal($Documento)
    {
        $data= array(
            'NombreLocal' =>mb_convert_case(mb_strtolower($this->input->post('LocalTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Direccion' => $this->input->post('LocalDireccion'),
            'Encargado' =>mb_convert_case(mb_strtolower($this->input->post('LocalEncargado')), MB_CASE_TITLE, "UTF-8"),
            'HorarioAtencion' => $this->input->post('LocalHorarioAtencion'),
            'imagenPortada' =>$Documento,
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idLocal', $_POST['LocalidLocal']);
        $insert_data["Registro"] = $this->db->update('local', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Local: ".$this->input->post('LocalTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarLocal()
    {
        $this->db->select('l.idLocal,l.NombreLocal as Titulo,l.Direccion,l.Encargado,l.HorarioAtencion,l.imagenPortada as imagen,DATE_FORMAT(l.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(l.fechaUpdate,"%d/%m/%Y") as fechaUpdate,l.Estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('local l');
        $this->db->join('estado e', 'e.idEstado=l.Estado_idEstado');
        $this->db->order_by('l.idLocal', 'desc');
        return $this->db->get();
    }
    public function ObtenerLocal()
    {
        $this->db->where('idLocal', $_POST['idLocal']);
        $query = $this->db->get('local');
        return $query->row();
    }
    public function EliminarLocal()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idLocal', $_POST['idLocal']);
        $row = $this->db->get('local');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Local: ".$query->NombreLocal."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idLocal', $_POST['idLocal']);
        $delete_data["Delete"] = $this->db->delete('local');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }
    public function EstadoLocal($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );
         /** Recuperar Datos para Historial **/
        $this->db->where('idLocal', $_POST['idLocal']);
        $row = $this->db->get('local');
        $query=$row->row();
        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Local: ".$query->NombreLocal."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Local: ".$query->NombreLocal."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }
        $this->db->where('idLocal', $_POST['idLocal']);
        $insert_data["accion"] = $this->db->update('local', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MLocal.php */
