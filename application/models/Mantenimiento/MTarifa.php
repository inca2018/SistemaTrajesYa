<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MTarifa extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroTarifa()
    {
        $data= array(
            'precioAlquiler' =>$this->input->post('tarifaAlquiler'),
            'precioVenta' =>$this->input->post('tarifaVenta'),
            'Producto_idProducto' => $this->input->post('idProducto'),
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('tarifa', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Tarifa:";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateTarifa()
    {
        $data= array(
            'precioAlquiler' =>$this->input->post('tarifaAlquiler'),
            'precioVenta' =>$this->input->post('tarifaVenta'),
        );
        $this->db->where('Producto_idProducto', $_POST['idProducto']);
        $insert_data["Registro"] = $this->db->update('tarifa', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Tarifa:";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarTarifa()
    {

        $this->db->select("del.idDelivery,del.precioDelivery,del.fechaRegistro,del.fechaUpdate,CONCAT(dep.departamento,'/',pro.provincia,'/',dis.distrito) as Ubicacion ");
        $this->db->from('delivery del');
        $this->db->join('departamento dep', 'dep.idDepartamento=del.Departamento_idDepartamento');
        $this->db->join('provincia pro', 'pro.idProvincia=del.Provincia_idProvincia');
        $this->db->join('distrito dis', 'dis.idDistrito=del.Distrito_idDitrito');
        $this->db->where('del.Producto_idProducto', $_POST['idProducto']);
        $this->db->order_by('del.idDelivery', 'desc');
        return $this->db->get();
    }
    public function ObtenerTarifa()
    {
        $this->db->where('idTarifa', $_POST['idTarifa']);
        $query = $this->db->get('Tarifa');
        return $query->row();
    }
    public function EliminarDelivery()
    {

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Delivery: ".$_POST['Descripcion']."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        $this->db->where('idDelivery', $_POST['idDelivery']);
        $delete_data["Delete"] = $this->db->delete('delivery');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;

    }


    public function ValidacionUbigeo(){

      $this->db->where('Departamento_idDepartamento', $_POST['DeliveryDepartamento']);
      $this->db->where('Provincia_idProvincia', $_POST['DeliveryProvincia']);
      $this->db->where('Distrito_idDitrito', $_POST['DeliveryDistrito']);
      $this->db->where('Producto_idProducto', $_POST['idProducto']);
      $query = $this->db->get('delivery');

      return $query->num_rows();
    }


    public function RegistroDelivery()
    {

        $data= array(
            'Producto_idProducto' =>$this->input->post('idProducto'),
            'Departamento_idDepartamento' => $this->input->post('DeliveryDepartamento'),
            'Provincia_idProvincia' => $this->input->post('DeliveryProvincia'),
            'Distrito_idDitrito' => $this->input->post('DeliveryDistrito'),
            'precioDelivery' =>$this->input->post('tarifaDelivery'),
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('delivery', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Delivery:";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateDelivery()
    {
        $data= array(
            'precioDelivery' =>$this->input->post('tarifaDelivery'),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('Producto_idProducto', $_POST['idProducto']);
        $this->db->where('Departamento_idDepartamento', $_POST['DeliveryDepartamento']);
        $this->db->where('Provincia_idProvincia', $_POST['DeliveryProvincia']);
        $this->db->where('Distrito_idDitrito', $_POST['DeliveryDistrito']);
        $insert_data["Registro"] = $this->db->update('delivery', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Delivery:";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }


}
/* End of file MTarifa.php */
