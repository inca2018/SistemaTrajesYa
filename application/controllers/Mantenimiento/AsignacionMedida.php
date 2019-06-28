<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignacionMedida extends CI_Controller {

     public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MAsignacionMedida');
    }

   public function index(){
      $this->load->view('Mantenimiento/AsignacionMedida');
   }


    public function AgregarMedida(){
        $data = $this->MAsignacionMedida->AgregarMedida();
        echo json_encode($data);
    }
    public function QuitarMedida(){
        $data = $this->MAsignacionMedida->QuitarMedida();
        echo json_encode($data);
    }
     public function ObtenerAsignaciones(){

        $rspta = $this->MAsignacionMedida->ObtenerAsignaciones();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => $reg->idMedida
            );
        }

        echo json_encode($data);
    }
    public function ListarMedidas(){
         $rspta = $this->MAsignacionMedida->ListarMedidas();
            foreach ($rspta->result() as $reg) {
             	echo '<option class="opcionMedida"   value=' . $reg->idMedida . '>' . $reg->Titulo. '</option>';
            }
    }
}

/* End of file MenuPhp.php */
