<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Producto extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MProducto');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Producto');
    }

     public function BuscarImagen($reg){
        if($reg->imagen==null){
            return 'Sin Portada';
        }else{
           return  ' <a href="../../assets/images/'.$reg->imagen.'" data-lightbox="1" data-title="'.$reg->Titulo.'">
                                                                            <img src="../../assets/images/'.$reg->imagen.'" class="img-fluid rounded img-30">
                                                                        </a>';
        }
    }
    public function BuscarEstado($reg)
    {
        if ($reg->Estado_idEstado == '1' || $reg->Estado_idEstado == 1) {
            return '<div class="badge badge-success">' . $reg->nombreEstado . '</div>';
        } elseif ($reg->Estado_idEstado == '2' || $reg->Estado_idEstado == 2) {
            return '<div class="badge badge-danger">' . $reg->nombreEstado . '</div>';
        } else {
            return '<div class="badge badge-primary">' . $reg->nombreEstado . '</div>';
        }
    }
    public function BuscarUbicacion($reg){
        $ubicacion="";
        if($reg->departamento==""){
           $ubicacion.="-";
            return $ubicacion;
        }else{
            if($reg->provincia==""){
              $ubicacion.=$reg->departamento;
                return $ubicacion;
            }else{
               if($reg->distrito==""){
                   $ubicacion.=$reg->departamento." / ".$reg->provincia;
                   return $ubicacion;
               }else{
                  $ubicacion.=$reg->departamento." / ".$reg->provincia." / ".$reg->distrito;
                   return $ubicacion;
               }
            }
        }
    }
    public function CorrelativoProducto($reg){
        $num=($reg->idProducto);
        $len=strlen($num);
        $numCeros=5-$len;
        $ceros=str_repeat("0",$numCeros);
        $re="PTY-".$ceros.$num;
        return $re;

    }

    public function BuscarAccion($reg)
    {
        if ($reg->Estado_idEstado == 1) {
            return '

            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarProducto(' . $reg->idProducto . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarProducto(' . $reg->idProducto . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarProducto(' . $reg->idProducto . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->Estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarProducto(' . $reg->idProducto . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarProducto(' . $reg->idProducto . ')"><i class="fa fa-trash"></i></button> ';
        }
    }
    public function VerificarTama($str){
        if(strlen($str)>120){
            $str_e="";
            $str_e=substr($str,0,100);
            $str_e=$str_e."...";
            return $str_e;

        }else{
            return $str;
        }
    }



    public function ListarProducto()
    {
        $rspta = $this->MProducto->ListarProducto();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => $this->CorrelativoProducto($reg),
                "1" => $reg->Titulo,
                "2" => $reg->verificado,
                "3" => $this->BuscarImagen($reg),
                "4" => $reg->categoria." / ".$reg->NombreSubCategoria,
                "5" => $this->BuscarUbicacion($reg),
                "6" => $this->VerificarTama($reg->DescripcionProducto),
                "7" => $this->BuscarAccion($reg),
                "8" => ' <button type="button" title="Ver Galeria de Fotos" class="btn btn-grd-inverse  btn-mini btn-round" onclick="Galeria(' . $reg->idProducto . ')">Ver Galeria de Fotos</button>',
                "9" =>  ' <button type="button" title="Ver Tarifa Disponibles" class="btn btn-grd-primary btn-mini btn-round" onclick="Tarifa(' . $reg->idProducto . ')">Ver Tarifas Disponibles </button>',
                "10" =>  ' <button type="button" title="Ver Medidas Disponibles" class="btn btn-grd-info btn-mini btn-round" onclick="AsignacionMedida(' . $reg->idProducto . ')">Ver Medidas Disponibles</button>',
                "11" => $reg->fechaRegistro,
                "12" => $reg->fechaUpdate,
                "13" => $this->BuscarEstado($reg)
            );
        }

        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
    }

    function check_default($post_string)
    {
      return $post_string == '0' ? FALSE : TRUE;
    }
     function check_default2($post_string)
    {
      return $post_string == '0' ? FALSE : TRUE;
    }

    public function InsertUpdateProducto()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('ProductoTitulo', 'Titulo del Producto', 'trim|required|min_length[3]|max_length[120]');


        $this->form_validation->set_rules('ProductoCategoria', 'Categoria de Producto', 'required|callback_check_default');
        $this->form_validation->set_message('check_default', 'El campo Categoria de Producto es Obligatorio');

        $this->form_validation->set_rules('ProductoSubCategoria', 'SubCategoria de Producto', 'required|callback_check_default2');
        $this->form_validation->set_message('check_default2', 'El campo SubCategoria de Producto es Obligatorio');

        if ($this->form_validation->run() == true) {
            /* Registras Producto */
            if (empty($_POST['ProductoidProducto'])) {
                /* valida Producto */
                $VRuc = $this->Recurso->Validaciones('producto', 'NombreProducto', $_POST['ProductoTitulo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Producto:  "' . $_POST['ProductoTitulo'] . '" , ya se encuentra registrado ';
                }

                 if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['ProductoTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Producto/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }


                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MProducto->RegistroProducto($Documento);

                    if($Documento!=null || $Documento!=''){

                         $nombre=str_replace(" ","_",$_POST['ProductoTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Producto",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Producto Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar Producto */
                /* valida Producto */
                $VRuc = $this->Recurso->Validaciones('producto', 'NombreProducto', $_POST['ProductoTitulo'], 'idProducto', $_POST['ProductoidProducto']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Producto:' . $_POST['ProductoTitulo'] . ' ya se encuentra registrado <br>';
                }

                 if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['ProductoTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Producto/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MProducto->UpdateProducto($Documento);

                    if($Documento!=null || $Documento!=''){

                         $nombre=str_replace(" ","_",$_POST['ProductoTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Producto",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Producto Modificado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            }
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'warning',
                'Mensaje' => validation_errors('<li>', '</li>')
            );
        }
        echo json_encode($data);
    }

    public function ObtenerProducto()
    {
        $data = $this->MProducto->ObtenerProducto();
        if($data->imagenPortada!=null){
            $ruta="assets/images/".$data->imagenPortada;
            // Cargando la imagen
            $archivo = file_get_contents($ruta);
            // Decodificando la imagen en base64
            $base64 = 'data:image/jpg;base64,' . base64_encode($archivo);
            $data->imagenPortada=$base64;
        }
        echo json_encode($data);
    }
     public function ObtenerProductoTarifa()
    {
        $data = $this->MProducto->ObtenerProductoTarifa();
        if($data->imagenPortada!=null){
            $ruta="assets/images/".$data->imagenPortada;
            // Cargando la imagen
            $archivo = file_get_contents($ruta);
            // Decodificando la imagen en base64
            $base64 = 'data:image/jpg;base64,' . base64_encode($archivo);
            $data->imagenPortada=$base64;
        }
        echo json_encode($data);
    }
    public function EliminarProducto()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

         $respta = $this->MProducto->ObtenerProducto();
            if($respta->imagenPortada!=null){
               $linkEliminar='assets/images/'.$respta->imagenPortada;
                if(file_exists($linkEliminar)){
                     unlink($linkEliminar);
                  }
            }

        $delete = $this->MProducto->EliminarProducto();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Producto Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarProducto()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MProducto->EstadoProducto(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Producto Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarProducto()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MProducto->EstadoProducto(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Producto Inhabilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Inhabilitar en base de datos Error:' . $disable["errDB"]["code"] . ':' . $disable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function ListarCategoria(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarCategoria();
            foreach ($rspta->result() as $reg) {
             	echo '<option data-grupo="'.$reg->Grupo_idGrupo.'"  value=' . $reg->idCategoria . '>' . $reg->NombreCategoria . '</option>';
            }
    }
     public function ListarSubCategoria(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarSubCategoria();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idSubCategoria . '>' . $reg->NombreSubCategoria . '</option>';
            }
    }

     public function ListarProductosSelect(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarProductosSelect();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idProducto . '>' . $reg->NombreProducto . '</option>';
            }
    }
     public function ListarMedidasSelect(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarMedidasSelect();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idMedida . '>' . $reg->NombreMedida . '</option>';
            }
    }

     public function ListarDepartamento(){
         echo '<option value=""> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarDepartamento();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idDepartamento . '>' . $reg->departamento . '</option>';
            }
    }
    public function ListarProvincia(){
         echo '<option value=""> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarProvincia();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idProvincia . '>' . $reg->provincia . '</option>';
            }
    }
     public function ListarDistrito(){
         echo '<option value=""> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarDistrito();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idDistrito . '>' . $reg->distrito . '</option>';
            }
    }



}
/* End of file MenuPhp.php */
