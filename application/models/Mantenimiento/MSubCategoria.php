<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MSubCategoria extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroSubCategoria($Documento)
    {
        $data= array(
            'NombreSubCategoria' =>mb_convert_case(mb_strtolower($this->input->post('SubCategoriaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Descripcion' => $this->input->post('SubCategoriaDescripcion'),
            'imagenPortada' =>$Documento,
            'Categoria_idCategoria' => $this->input->post('idCategoria'),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('subCategoria', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo SubCategoria: ".$this->input->post('SubCategoriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateSubCategoria($Documento)
    {
        $data= array(
            'NombreSubCategoria' =>mb_convert_case(mb_strtolower($this->input->post('SubCategoriaTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Descripcion' => $this->input->post('SubCategoriaDescripcion'),
            'imagenPortada' =>$Documento,
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idSubCategoria', $_POST['SubCategoriaidSubCategoria']);
        $insert_data["Registro"] = $this->db->update('subCategoria', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  SubCategoria: ".$this->input->post('SubCategoriaTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarSubCategoria()
    {

        $this->db->select('p.idSubCategoria,p.NombreSubCategoria as Titulo,p.Descripcion,p.imagenPortada as imagen,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.Estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('subCategoria p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->where('p.Categoria_idCategoria', $_POST['idCategoria']);
        $this->db->order_by('p.idSubCategoria', 'desc');
        return $this->db->get();
    }
    public function ObtenerSubCategoria()
    {
        $this->db->where('idSubCategoria', $_POST['idSubCategoria']);
        $query = $this->db->get('subCategoria');
        return $query->row();
    }
    public function EliminarSubCategoria()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idSubCategoria', $_POST['idSubCategoria']);
        $row = $this->db->get('subCategoria');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  SubCategoria: ".$query->NombreSubCategoria."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        $this->db->where('idSubCategoria', $_POST['idSubCategoria']);
        $delete_data["Delete"] = $this->db->delete('subCategoria');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }
    public function EstadoSubCategoria($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );
         /** Recuperar Datos para Historial **/
        $this->db->where('idSubCategoria', $_POST['idSubCategoria']);
        $row = $this->db->get('SubCategoria');
        $query=$row->row();
        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  SubCategoria: ".$query->NombreSubCategoria."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  SubCategoria: ".$query->NombreSubCategoria."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }
        $this->db->where('idSubCategoria', $_POST['idSubCategoria']);
        $insert_data["accion"] = $this->db->update('SubCategoria', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MSubCategoria.php */
