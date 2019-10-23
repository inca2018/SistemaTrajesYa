<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignacionGenero extends CI_Controller {

     public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MAsignacionGenero');
    }

   public function index(){
      $this->load->view('Mantenimiento/AsignacionGenero');
   }


    public function AgregarGenero(){
        $data = $this->MAsignacionGenero->AgregarGenero();
        echo json_encode($data);
    }
    public function QuitarGenero(){
        $data = $this->MAsignacionGenero->QuitarGenero();
        echo json_encode($data);
    }
     public function ObtenerAsignaciones(){

        $rspta = $this->MAsignacionGenero->ObtenerAsignaciones();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => $reg->idGenero
            );
        }

        echo json_encode($data);
    }
    public function ListarGeneros(){
         $rspta = $this->MAsignacionGenero->ListarGeneros();
            foreach ($rspta->result() as $reg) {
             	echo '<option class="opcionGenero"   value=' . $reg->idGenero . '>' . $reg->Titulo. '</option>';
            }
    }
}

/* End of file MenuPhp.php */
