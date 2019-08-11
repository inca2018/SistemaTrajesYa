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
    public function RegistroProducto($Documento)
    {
        $departamento=null;
        if($this->input->post('ProductoDepartamento')==''){
            $departamento=null;
        }else{
            $departamento=$this->input->post('ProductoDepartamento');
        }

        $provincia=null;
        if($this->input->post('ProductoProvincia')==''){
            $provincia=null;
        }else{
            $provincia=$this->input->post('ProductoProvincia');
        }

        $distrito=null;
        if($this->input->post('ProductoDistrito')==''){
            $distrito=null;
        }else{
            $distrito=$this->input->post('ProductoDistrito');
        }

        $data= array(
            'NombreProducto' =>mb_convert_case(mb_strtolower($this->input->post('ProductoTitulo')), MB_CASE_TITLE, "UTF-8"),
            'DescripcionProducto' =>$this->input->post('ProductoDescripcion'),
            'verificado' =>$this->input->post('ProductoVerificado'),
            'imagenPortada' =>$Documento,
            'Categoria_idCategoria' =>$this->input->post('ProductoCategoria'),
            'SubCategoria_idSubCategoria' =>$this->input->post('ProductoSubCategoria'),
            'Departamento_idDepartamento' =>$departamento,
            'Provincia_idProvincia' =>$provincia,
            'Distrito_idDistrito' =>$distrito,
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('producto', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Producto: ".$this->input->post('ProductoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();


        return $insert_data;
    }
    public function UpdateProducto($Documento)
    {
         $departamento=null;
        if($this->input->post('ProductoDepartamento')==''){
            $departamento=null;
        }else{
            $departamento=$this->input->post('ProductoDepartamento');
        }

        $provincia=null;
        if($this->input->post('ProductoProvincia')==''){
            $provincia=null;
        }else{
            $provincia=$this->input->post('ProductoProvincia');
        }

        $distrito=null;
        if($this->input->post('ProductoDistrito')==''){
            $distrito=null;
        }else{
            $distrito=$this->input->post('ProductoDistrito');
        }

        $data= array(
            'NombreProducto' =>mb_convert_case(mb_strtolower($this->input->post('ProductoTitulo')), MB_CASE_TITLE, "UTF-8"),
            'DescripcionProducto' =>$this->input->post('ProductoDescripcion'),
            'verificado' =>$this->input->post('ProductoVerificado'),
            'imagenPortada' =>$Documento,
            'Categoria_idCategoria' =>$this->input->post('ProductoCategoria'),
            'SubCategoria_idSubCategoria' =>$this->input->post('ProductoSubCategoria'),
            'Departamento_idDepartamento' =>$departamento,
            'Provincia_idProvincia' =>$provincia,
            'Distrito_idDistrito' =>$distrito,
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idProducto', $_POST['ProductoidProducto']);
        $insert_data["Registro"] = $this->db->update('producto', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Producto: ".$this->input->post('ProductoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarProducto()
    {
        $this->db->select('p.idProducto,p.NombreProducto as Titulo,p.DescripcionProducto,p.verificado,p.imagenPortada as imagen,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.Estado_idEstado,e.DescripcionEstado as nombreEstado,cat.Grupo_idGrupo as tipo,cat.idCategoria,cat.NombreCategoria as categoria,sub.idSubCategoria as subcategoria,sub.NombreSubCategoria,dep.departamento,pro.provincia,dis.distrito ');
        $this->db->from('producto p');
        $this->db->join('categoria cat', 'cat.idCategoria=p.Categoria_idCategoria');
        $this->db->join('subcategoria sub', 'sub.idSubCategoria=p.SubCategoria_idSubCategoria');
        $this->db->join('estado e', 'e.idEstado=p.Estado_idEstado');
        $this->db->join('departamento dep', 'dep.idDepartamento=p.Departamento_idDepartamento','left');
        $this->db->join('provincia pro', 'pro.idProvincia=p.Provincia_idProvincia','left');
        $this->db->join('distrito dis', 'dis.idDistrito=p.Distrito_idDistrito','left');
        $this->db->order_by('p.idProducto', 'desc');
        return $this->db->get();
    }
    public function ObtenerProducto()
    {
        $this->db->where('idProducto', $_POST['idProducto']);
        $query = $this->db->get('producto');
        return $query->row();
    }
    public function ObtenerProductoTarifa()
    {
        $this->db->select('p.idProducto,p.imagenPortada,p.NombreProducto,t.precioAlquiler,t.precioVenta');
        $this->db->from('producto p');
        $this->db->join('tarifa t', 't.Producto_idProducto=p.idProducto','left');
        $this->db->where('p.idProducto', $_POST['idProducto']);
        $query = $this->db->get('producto');
        return $query->row();
    }
    public function EliminarProducto()
    {
         /** Recuperar Datos para Historial **/
        $this->db->where('idProducto', $_POST['idProducto']);
        $row = $this->db->get('producto');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Producto: ".$query->NombreProducto."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idProducto', $_POST['idProducto']);
        $delete_data["Delete"] = $this->db->delete('producto');
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
        $row = $this->db->get('producto');
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
        $insert_data["accion"] = $this->db->update('producto', $data);
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
