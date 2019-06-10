<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MProducto extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroProducto()
    {
        $data= array(
            'NombreProducto' =>mb_convert_case(mb_strtolower($this->input->post('ProductoTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('Producto', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Producto: ".$this->input->post('ProductoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();


        return $insert_data;
    }
    public function UpdateProducto()
    {
        $data= array(
            'NombreProducto' => $this->mb_convert_case(mb_strtolower($this->input->post('ProductoTitulo')), MB_CASE_TITLE, "UTF-8"),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idProducto', $_POST['ProductoidProducto']);
        $insert_data["Registro"] = $this->db->update('Producto', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Producto: ".$this->input->post('ProductoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarProducto()
    {

        $this->db->select('m.idProducto,m.NombreProducto as Titulo,DATE_FORMAT(m.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(m.fechaUpdate,"%d/%m/%Y") as fechaUpdate,m.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('Producto m');
        $this->db->join('estado e', 'e.idEstado=m.estado_idEstado');
        $this->db->order_by('m.idProducto', 'desc');
        return $this->db->get();
    }
    public function ObtenerProducto()
    {
        $this->db->where('idProducto', $_POST['idProducto']);
        $query = $this->db->get('Producto');
        return $query->row();
    }
    public function EliminarProducto()
    {
         /** Recuperar Datos para Historial **/
        $this->db->where('idProducto', $_POST['idProducto']);
        $row = $this->db->get('Producto');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Producto: ".$query->NombreProducto."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idProducto', $_POST['idProducto']);
        $delete_data["Delete"] = $this->db->delete('Producto');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;

    }
    public function EstadoProducto($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idProducto', $_POST['idProducto']);
        $row = $this->db->get('Producto');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Producto: ".$query->NombreProducto."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Producto: ".$query->NombreProducto."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }



        $this->db->where('idProducto', $_POST['idProducto']);
        $insert_data["accion"] = $this->db->update('Producto', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }

      public function ListarCategoria()
    {
          $query=$this->db->select("*");
          $this->db->from('categoria');
          $this->db->where('Estado_idEstado','1');
          return $this->db->get();
    }
      public function ListarSubCategoria()
    {
          $query=$this->db->select("*");
          $this->db->from('subcategoria');
          $this->db->where('Estado_idEstado','1');
          $this->db->where('Categoria_idCategoria', $_POST['idCategoria']);
          return $this->db->get();
    }
     public function ListarDepartamento()
    {
          $query=$this->db->select("*");
          $this->db->from('departamento');
          return $this->db->get();
    }
     public function ListarProvincia()
    {
          $query=$this->db->select("*");
          $this->db->from('provincia');
          $this->db->where('departamento_idDepartamento', $_POST['idDepartamento']);
          return $this->db->get();
    }
      public function ListarDistrito()
    {
          $query=$this->db->select("*");
          $this->db->from('distrito');
          $this->db->where('provincia_idProvincia', $_POST['idProvincia']);
          return $this->db->get();
    }

}
/* End of file MProducto.php */
