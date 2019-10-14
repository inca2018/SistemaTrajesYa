<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promocion extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MPromocion');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Promocion');
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

    public function BuscarAccion($reg)
    {
        if ($reg->Estado_idEstado == 1) {
            return '
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarPromocion(' . $reg->idPromocion . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarPromocion(' . $reg->idPromocion . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPromocion(' . $reg->idPromocion . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->Estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarPromocion(' . $reg->idPromocion . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPromocion(' . $reg->idPromocion . ')"><i class="fa fa-trash"></i></button> ';
        }
    }

    public function ListarPromocion()
    {
        $rspta = $this->MPromocion->ListarPromocion();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $reg->linkPromocion,
                "2" => $this->BuscarImagen($reg),
                "3" => $this->BuscarAccion($reg),
                "4" => $reg->fechaRegistro,
                "5" => $reg->fechaUpdate,
                "6" => $this->BuscarEstado($reg)
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


    public function InsertUpdatePromocion()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $this->form_validation->set_rules('PromocionTitulo', 'Titulo del Promoción', 'trim|required|min_length[3]|max_length[150]');
        $this->form_validation->set_rules('PromocionLink', 'Link de Promoción', 'trim|required|min_length[3]|max_length[120]');



        if ($this->form_validation->run() == true) {
            /* Registras Promocion */
            if (empty($_POST['PromocionidPromocion'])) {
                /* valida Promocion */
                $validacion = $this->Recurso->Validaciones('promocion', 'NombrePromocion', $_POST['PromocionTitulo']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Promocion:  "' . $_POST['PromocionTitulo'] . '" , ya se encuentra registrado ';
                }


                if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['PromocionTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Promocion/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }


                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro =$this->MPromocion->RegistroPromocion($Documento);

                    if($Documento!=null || $Documento!=''){

                       $nombre=str_replace(" ","_",$_POST['PromocionTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Promocion",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Promocion Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                $validacion = $this->Recurso->Validaciones('promocion', 'NombrePromocion', $_POST['PromocionTitulo'], 'idPromocion', $_POST['PromocionidPromocion']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Promocion:' . $_POST['PromocionTitulo'] . ' ya se encuentra registrado <br>';
                }

                 if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['PromocionTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Promocion/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MPromocion->UpdatePromocion($Documento);

                    if($Documento!=null || $Documento!=''){

                        $nombre=str_replace(" ","_",$_POST['PromocionTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Promocion",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Promocion Modificado con exito.';
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

    public function ObtenerPromocion()
    {
        $data = $this->MPromocion->ObtenerPromocion();
        if($data->imagenPromocion!=null){
            $ruta="assets/images/".$data->imagenPromocion;
            // Cargando la imagen
            $archivo = file_get_contents($ruta);
            // Decodificando la imagen en base64
            $base64 = 'data:image/jpg;base64,' . base64_encode($archivo);
            $data->imagenPromocion=$base64;
        }
        echo json_encode($data);
    }
    public function EliminarPromocion()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

         $respta = $this->MPromocion->ObtenerPromocion();
            if($respta->imagenPromocion!=null){
               $linkEliminar='assets/images/'.$respta->imagenPromocion;
                if(file_exists($linkEliminar)){
                     unlink($linkEliminar);
                  }
            }

        $delete = $this->MPromocion->EliminarPromocion();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Promocion Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarPromocion()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MPromocion->EstadoPromocion(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Promocion Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarPromocion()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MPromocion->EstadoPromocion(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Promocion Inhabilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Inhabilitar en base de datos Error:' . $disable["errDB"]["code"] . ':' . $disable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }


     public function ListarProductos(){
         $rspta = $this->MPromocion->ListarProductos();
            foreach ($rspta->result() as $reg) {
                $temp="";
                if($reg->Descuento!=""){
                    $temp=" - Descuento : ".$reg->Descuento." %";
                }

             	echo '<option class="opcionProducto"   value=' . $reg->idProducto . '>' . $reg->Titulo. ''.$temp.'</option>';
            }
    }

    public function ObtenerPromociones(){

        $rspta = $this->MPromocion->ObtenerPromociones();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => $reg->idProducto,
                "1" => $reg->Descuento
            );
        }

        echo json_encode($data);
    }

    public function AgregarPromocionProducto(){
        $data = $this->MPromocion->AgregarPromocionProducto();
        echo json_encode($data);
    }
    public function QuitarPromoDescuentos(){
        $data = $this->MPromocion->QuitarPromoDescuentos();
        echo json_encode($data);
    }


}
