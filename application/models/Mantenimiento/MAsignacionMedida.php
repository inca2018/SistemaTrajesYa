<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MAsignacionMedida extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }

    public function AgregarMedida(){
         $data= array(
            'Producto_idProducto' => $_POST['idProducto'],
            'Medida_idMedida' => $_POST['idMedida'],
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('producto_medida', $data);
        $insert_data["errDB"]    = $this->db->error();
        return $insert_data;
    }
    public function QuitarMedida(){
        $where= array(
            'Producto_idProducto' => $_POST['idProducto'],
            'Medida_idMedida' => $_POST['idMedida']
        );

        $this->db->where($where);

        $delete_data["Delete"] = $this->db->delete('producto_medida');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;
    }

    public function ObtenerAsignaciones()
    {
        $where= array(
            'Producto_idProducto' => $_POST['idProducto']
        );

        $this->db->select('asi.Medida_idMedida as idMedida');
        $this->db->from('producto_medida asi');
        $this->db->where($where);
        return $this->db->get();
    }

     public function ListarMedidas()
    {
        $this->db->select('m.idMedida,m.NombreMedida as Titulo,DATE_FORMAT(m.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(m.fechaUpdate,"%d/%m/%Y") as fechaUpdate,m.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('medida m');
        $this->db->join('estado e', 'e.idEstado=m.estado_idEstado');
        $this->db->order_by('m.idMedida', 'desc');
        return $this->db->get();
    }


}

