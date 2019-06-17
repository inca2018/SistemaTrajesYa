<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MGaleria extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroGaleria($Documento)
    {
        $data= array(
            'NombreGaleria' =>mb_convert_case(mb_strtolower($this->input->post('GaleriaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'imagenPortada' =>$Documento,
            'Producto_idProducto'=>$_POST['idProducto'],
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('galeria', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Galeria: ".$this->input->post('GaleriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateGaleria($Documento)
    {
        $data= array(
            'NombreGaleria' =>mb_convert_case(mb_strtolower($this->input->post('GaleriaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'imagenPortada' =>$Documento,
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idGaleria', $_POST['GaleriaidGaleria']);
        $this->db->where('Producto_idProducto', $_POST['idProducto']);
        $insert_data["Registro"] = $this->db->update('galeria', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Galeria: ".$this->input->post('GaleriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarGaleria()
    {

        $this->db->select('p.idGaleria,p.NombreGaleria as Titulo,p.imagenPortada as imagen,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.Estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('galeria p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->where('Producto_idProducto', $_POST['idProducto']);
        $this->db->order_by('p.idGaleria', 'desc');
        return $this->db->get();
    }
    public function ObtenerGaleria()
    {
        $this->db->where('idGaleria', $_POST['idGaleria']);
        $query = $this->db->get('galeria');
        return $query->row();
    }
    public function ObtenerGaleria2()
    {
        $this->db->where('idGaleria', $_POST['GaleriaidGaleria']);
        $query = $this->db->get('galeria');
        return $query->row();
    }
    public function EliminarGaleria()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idGaleria', $_POST['idGaleria']);
        $row = $this->db->get('galeria');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Galeria: ".$query->NombreGaleria."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idGaleria', $_POST['idGaleria']);
        $delete_data["Delete"] = $this->db->delete('galeria');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }
    public function EstadoGaleria($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );
         /** Recuperar Datos para Historial **/
        $this->db->where('idGaleria', $_POST['idGaleria']);
        $row = $this->db->get('galeria');
        $query=$row->row();
        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Galeria: ".$query->NombreGaleria."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Galeria: ".$query->NombreGaleria."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }
        $this->db->where('idGaleria', $_POST['idGaleria']);
        $insert_data["accion"] = $this->db->update('galeria', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MGaleria.php */
