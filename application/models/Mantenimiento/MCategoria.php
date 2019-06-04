<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MCategoria extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroCategoria()
    {
        $data= array(
            'DescripcionCategoria' => $this->input->post('CategoriaTitulo'),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('Categoria', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Categoria: ".$this->input->post('CategoriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateCategoria()
    {
        $data= array(
            'DescripcionCategoria' => $this->input->post('CategoriaTitulo'),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idCategoria', $_POST['CategoriaidCategoria']);
        $insert_data["Registro"] = $this->db->update('Categoria', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Categoria: ".$this->input->post('CategoriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarCategoria()
    {

        $this->db->select('p.idCategoria,p.DescripcionCategoria as Titulo,p.permisos,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('Categoria p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idCategoria', 'desc');
        return $this->db->get();
    }
    public function ObtenerCategoria()
    {
        $this->db->where('idCategoria', $_POST['idCategoria']);
        $query = $this->db->get('Categoria');
        return $query->row();
    }
    public function EliminarCategoria()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idCategoria', $_POST['idCategoria']);
        $row = $this->db->get('Categoria');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Categoria: ".$query->DescripcionCategoria."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idCategoria', $_POST['idCategoria']);
        $delete_data["Delete"] = $this->db->delete('Categoria');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;

    }
    public function EstadoCategoria($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idCategoria', $_POST['idCategoria']);
        $row = $this->db->get('Categoria');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Categoria: ".$query->DescripcionCategoria."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Categoria: ".$query->DescripcionCategoria."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }

        $this->db->where('idCategoria', $_POST['idCategoria']);
        $insert_data["accion"] = $this->db->update('Categoria', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MCategoria.php */
