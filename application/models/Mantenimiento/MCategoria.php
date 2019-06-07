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
    public function RegistroCategoria($Documento)
    {
        $data= array(
            'NombreCategoria' =>mb_convert_case(mb_strtolower($this->input->post('CategoriaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Descripcion' => $this->input->post('CategoriaDescripcion'),
            'imagenPortada' =>$Documento,
            'Grupo_idGrupo' => $this->input->post('CategoriaGrupo'),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('categoria', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Categoria: ".$this->input->post('CategoriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateCategoria($Documento)
    {
        $data= array(
            'NombreCategoria' =>mb_convert_case(mb_strtolower($this->input->post('CategoriaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Descripcion' => $this->input->post('CategoriaDescripcion'),
            'imagenPortada' =>$Documento,
            'Grupo_idGrupo' => $this->input->post('CategoriaGrupo'),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idCategoria', $_POST['CategoriaidCategoria']);
        $insert_data["Registro"] = $this->db->update('categoria', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Categoria: ".$this->input->post('CategoriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarCategoria()
    {

        $this->db->select('p.idCategoria,p.NombreCategoria as Titulo,p.Descripcion,p.imagenPortada as imagen,g.Descripcion as grupoCategoria,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.Estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('categoria p');
        $this->db->join('grupo g', 'g.idGrupo=p.Grupo_idGrupo');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idCategoria', 'desc');
        return $this->db->get();
    }
    public function ObtenerCategoria()
    {
        $this->db->where('idCategoria', $_POST['idCategoria']);
        $query = $this->db->get('categoria');
        return $query->row();
    }
    public function EliminarCategoria()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idCategoria', $_POST['idCategoria']);
        $row = $this->db->get('categoria');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Categoria: ".$query->DescripcionCategoria."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idCategoria', $_POST['idCategoria']);
        $delete_data["Delete"] = $this->db->delete('categoria');
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
        $row = $this->db->get('categoria');
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
        $insert_data["accion"] = $this->db->update('categoria', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }

     public function ListarGrupo()
    {
          $query=$this->db->select("*");
          $this->db->from('grupo');
          $this->db->where('Estado_idEstado','1');
          return $this->db->get();
    }


}
/* End of file MCategoria.php */
