<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MPedido extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroPedido($Documento)
    {
        $data= array(
            'NombrePedido' =>mb_convert_case(mb_strtolower($this->input->post('PedidoTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Descripcion' => $this->input->post('PedidoDescripcion'),
            'imagenPortada' =>$Documento,
            'Grupo_idGrupo' => $this->input->post('PedidoGrupo'),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('Pedido', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Pedido: ".$this->input->post('PedidoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdatePedido($Documento)
    {
        $data= array(
            'NombrePedido' =>mb_convert_case(mb_strtolower($this->input->post('PedidoTitulo')), MB_CASE_TITLE, "UTF-8"),
            'Descripcion' => $this->input->post('PedidoDescripcion'),
            'imagenPortada' =>$Documento,
            'Grupo_idGrupo' => $this->input->post('PedidoGrupo'),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idPedido', $_POST['PedidoidPedido']);
        $insert_data["Registro"] = $this->db->update('Pedido', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Pedido: ".$this->input->post('PedidoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarPedido()
    {

        $this->db->select('p.idPedido,p.NombrePedido as Titulo,p.Descripcion,p.imagenPortada as imagen,g.Descripcion as grupoPedido,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.Estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('Pedido p');
        $this->db->join('grupo g', 'g.idGrupo=p.Grupo_idGrupo');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idPedido', 'desc');
        return $this->db->get();
    }
    public function ObtenerPedido()
    {
        $this->db->where('idPedido', $_POST['idPedido']);
        $query = $this->db->get('Pedido');
        return $query->row();
    }
    public function EliminarPedido()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idPedido', $_POST['idPedido']);
        $row = $this->db->get('Pedido');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Pedido: ".$query->NombrePedido."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idPedido', $_POST['idPedido']);
        $delete_data["Delete"] = $this->db->delete('Pedido');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }
    public function EstadoPedido($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );
         /** Recuperar Datos para Historial **/
        $this->db->where('idPedido', $_POST['idPedido']);
        $row = $this->db->get('Pedido');
        $query=$row->row();
        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Pedido: ".$query->NombrePedido."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Pedido: ".$query->NombrePedido."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }
        $this->db->where('idPedido', $_POST['idPedido']);
        $insert_data["accion"] = $this->db->update('Pedido', $data);
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
/* End of file MPedido.php */
