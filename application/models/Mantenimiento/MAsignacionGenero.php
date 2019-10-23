<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MAsignacionGenero extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }

    public function AgregarGenero(){
         $data= array(
            'Producto_idProducto' => $_POST['idProducto'],
            'Genero_idGenero' => $_POST['idGenero'],
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('producto_genero', $data);
        $insert_data["errDB"]    = $this->db->error();
        return $insert_data;
    }
    public function QuitarGenero(){
        $where= array(
            'Producto_idProducto' => $_POST['idProducto'],
            'Genero_idGenero' => $_POST['idGenero']
        );

        $this->db->where($where);

        $delete_data["Delete"] = $this->db->delete('producto_genero');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;
    }

    public function ObtenerAsignaciones()
    {
        $where= array(
            'Producto_idProducto' => $_POST['idProducto']
        );

        $this->db->select('asi.Genero_idGenero as idGenero');
        $this->db->from('producto_genero asi');
        $this->db->where($where);
        return $this->db->get();
    }

     public function ListarGeneros()
    {
        $this->db->select('m.idGenero,m.NombreGenero as Titulo,DATE_FORMAT(m.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(m.fechaUpdate,"%d/%m/%Y") as fechaUpdate,m.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('genero m');
        $this->db->join('estado e', 'e.idEstado=m.estado_idEstado');
        $this->db->order_by('m.idGenero', 'desc');
        return $this->db->get();
    }


}

