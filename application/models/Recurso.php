<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Recurso extends CI_Model {


   public function Validaciones ($tabla, $columna, $valor, $ColumanaUpdate=false,$Idupdate=false)
   {
      $sql='SELECT * FROM '.$tabla.' WHERE '. $columna.' = '."'$valor'"." and not ".$columna.' = '."''";

      if($Idupdate){
         $sql.=' and not '.$ColumanaUpdate.' = '."'$Idupdate'";
      }
      $query = $this->db->query($sql);

      return $query->num_rows();
   }

   public function Validacion($tabla, $where)
   {
      $this->db->where($where);  
      $query = $this->db->get($tabla);

      return $query->num_rows();
   }
      
   public function RowSelect($Tabla, $where)
   {       
      $query = $this->db->where($where);    
      $query = $this->db->get($Tabla);      
      return $query->row();      
   }

   public function Fecha($fecha)
   {
      $this->load->helper('date');
      $datestring = '%d/%m/%Y';
      return mdate($datestring, strtotime($fecha));
   } 
     
    public function upload_documento($archivo,$grupo,$tipo,$nombre) {
      // ubicar el de recurso
      $linkDocumento='assets/images/'.$grupo.'/';
      if(!file_exists($linkDocumento)){
         mkdir("$linkDocumento",0777);
      }
    
       if($tipo==2){
           //editar
           $linkRecurso2='assets/images/'.$grupo.'/'.$nombre.'.jpg';
            if(file_exists($linkRecurso2)){
                 unlink($linkRecurso2);
              }
               if(isset($_FILES[$archivo])){
                 $extension = explode('.', $_FILES[$archivo]['name']);
                 $destination ='assets/images/'.$grupo.'/'.$nombre.'.jpg';
                 $subida = move_uploaded_file($_FILES[$archivo]['tmp_name'], $destination);
                 return $subida;
            } 
       }else{
           //registrar
            if(isset($_FILES[$archivo])){

                 $extension = explode('.', $_FILES[$archivo]['name']);
                 $destination ='assets/images/'.$grupo.'/'.$nombre.'.jpg';
                 $subida = move_uploaded_file($_FILES[$archivo]['tmp_name'], $destination);
                 return $subida;
              }
       }
   }    

}

/* End of file ModelName.php */
