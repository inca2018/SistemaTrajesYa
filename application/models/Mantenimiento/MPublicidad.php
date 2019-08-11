<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MPublicidad extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroPublicidad($Documento)
    {
        $data= array(
            'NombrePublicidad' =>mb_convert_case(mb_strtolower($this->input->post('PublicidadTitulo')), MB_CASE_TITLE, "UTF-8"),
            'linkPublicidad' => $this->input->post('PublicidadLink'),
            'imagenPublicidad' =>$Documento,
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('publicidad', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Publicidad: ".$this->input->post('PublicidadTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdatePublicidad($Documento)
    {
        $data= array(
            'NombrePublicidad' =>mb_convert_case(mb_strtolower($this->input->post('PublicidadTitulo')), MB_CASE_TITLE, "UTF-8"),
            'linkPublicidad' => $this->input->post('PublicidadLink'),
            'imagenPublicidad' =>$Documento,
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idPublicidad', $_POST['PublicidadidPublicidad']);
        $insert_data["Registro"] = $this->db->update('publicidad', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Publicidad: ".$this->input->post('PublicidadTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarPublicidad()
    {
        $this->db->select('p.idPublicidad,p.NombrePublicidad as Titulo,p.linkPublicidad,p.imagenPublicidad as imagen, DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.Estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('publicidad p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idPublicidad', 'desc');
        return $this->db->get();
    }
    public function ObtenerPublicidad()
    {
        $this->db->where('idPublicidad', $_POST['idPublicidad']);
        $query = $this->db->get('publicidad');
        return $query->row();
    }
    public function EliminarPublicidad()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idPublicidad', $_POST['idPublicidad']);
        $row = $this->db->get('publicidad');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Publicidad: ".$query->NombrePublicidad."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idPublicidad', $_POST['idPublicidad']);
        $delete_data["Delete"] = $this->db->delete('publicidad');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }
    public function EstadoPublicidad($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );
         /** Recuperar Datos para Historial **/
        $this->db->where('idPublicidad', $_POST['idPublicidad']);
        $row = $this->db->get('publicidad');
        $query=$row->row();
        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Publicidad: ".$query->NombrePublicidad."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Publicidad: ".$query->NombrePublicidad."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }
        $this->db->where('idPublicidad', $_POST['idPublicidad']);
        $insert_data["accion"] = $this->db->update('publicidad', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }




}
/* End of file MPublicidad.php */
