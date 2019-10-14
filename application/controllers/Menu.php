<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

   public function index(){
      $this->load->view('Menu');      
   }

   function CerrarSession(){
      $this->session->sess_destroy();      
      $this->load->view('Login');
   }

}

/* End of file MenuPhp.php */
