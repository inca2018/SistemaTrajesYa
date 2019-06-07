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

   public function upload_documento_binary($archivo,$grupo,$tipo,$nombre) {
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
                 $destination ='assets/images/'.$grupo.'/'.$nombre.'.jpg';
                 $subida = move_uploaded_file($_FILES[$archivo]['tmp_name'], $destination);
                 return $subida;
            }
       }else{
           //registrar
            if(isset($_FILES[$archivo])){
                $destination ='assets/images/'.$grupo.'/';
                //Como el elemento es un arreglos utilizamos foreach para extraer todos los valores
               foreach($_FILES[$archivo]['tmp_name'] as $key => $tmp_name)
               {

                  //Validamos que el archivo exista
                  if($_FILES[$archivo]["name"][$key]) {
                     $filename = $_FILES[$archivo]["name"][$key]; //Obtenemos el nombre original del archivo
                     $source = $_FILES[$archivo]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
                     //Movemos y validamos que el archivo se haya cargado correctamente
                     //El primer campo es el origen y el segundo el destino
                    move_uploaded_file($source, $destination.$nombre);
                  }
               }
                 return true;
              }
       }
   }

    public function GuardarImagenes($Archivos,$Carpeta,$Tipo,$Nombre){
         // ubicar el de recurso
      $linkDocumento='assets/images/'.$Carpeta.'/';
      if(!file_exists($linkDocumento)){
         mkdir("$linkDocumento",0777);
      }

       if($Tipo==2){
           //editar
           $linkRecurso2='assets/images/'.$Carpeta.'/'.$Nombre.'.jpg';
            if(file_exists($linkRecurso2)){
                 unlink($linkRecurso2);
              }
               if(isset($_FILES[$Archivos])){
                 $destination ='assets/images/'.$Carpeta.'/'.$Nombre.'.jpg';
                 $subida = move_uploaded_file($_FILES[$archivo]['tmp_name'], $destination);
                 return $subida;
            }
       }else{
           //registrar
            if(isset($Archivos)){
                $Destination ='assets/images/'.$Carpeta.'/';
                //Como el elemento es un arreglos utilizamos foreach para extraer todos los valores
                $Archivos=explode("|",$Archivos);
               foreach($Archivos as $key => $tmp_name)
               {
                  if($Archivos[$key]){
                     $temp=explode(",",$Archivos[$key]);
                     $data = base64_decode($temp[1]);
                     file_put_contents($Destination.$Nombre, $data);
                  }
               }
                 return true;
              }
       }
    }

}

/* End of file ModelName.php */
