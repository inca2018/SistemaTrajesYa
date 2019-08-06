<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MPromocion extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroPromocion($Documento)
    {
        $data= array(
            'NombrePromocion' =>mb_convert_case(mb_strtolower($this->input->post('PromocionTitulo')), MB_CASE_TITLE, "UTF-8"),
            'linkPromocion' => $this->input->post('PromocionLink'),
            'imagenPromocion' =>$Documento,
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('promocion', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Promocion: ".$this->input->post('PromocionTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdatePromocion($Documento)
    {
        $data= array(
            'NombrePromocion' =>mb_convert_case(mb_strtolower($this->input->post('PromocionTitulo')), MB_CASE_TITLE, "UTF-8"),
            'linkPromocion' => $this->input->post('PromocionLink'),
            'imagenPromocion' =>$Documento,
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idPromocion', $_POST['PromocionidPromocion']);
        $insert_data["Registro"] = $this->db->update('promocion', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Promocion: ".$this->input->post('PromocionTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarPromocion()
    {
        $this->db->select('p.idPromocion,p.NombrePromocion as Titulo,p.linkPromocion,p.imagenPromocion as imagen, DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.Estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('promocion p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idPromocion', 'desc');
        return $this->db->get();
    }
    public function ObtenerPromocion()
    {
        $this->db->where('idPromocion', $_POST['idPromocion']);
        $query = $this->db->get('promocion');
        return $query->row();
    }
    public function EliminarPromocion()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idPromocion', $_POST['idPromocion']);
        $row = $this->db->get('promocion');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Promocion: ".$query->NombrePromocion."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idPromocion', $_POST['idPromocion']);
        $delete_data["Delete"] = $this->db->delete('promocion');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }
    public function EstadoPromocion($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );
         /** Recuperar Datos para Historial **/
        $this->db->where('idPromocion', $_POST['idPromocion']);
        $row = $this->db->get('promocion');
        $query=$row->row();
        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Promocion: ".$query->NombrePromocion."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Promocion: ".$query->NombrePromocion."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }
        $this->db->where('idPromocion', $_POST['idPromocion']);
        $insert_data["accion"] = $this->db->update('promocion', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }




}
/* End of file MPromocion.php */
