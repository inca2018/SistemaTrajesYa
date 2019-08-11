<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publicidad extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MPublicidad');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Publicidad');
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
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarPublicidad(' . $reg->idPublicidad . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarPublicidad(' . $reg->idPublicidad . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPublicidad(' . $reg->idPublicidad . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->Estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarPublicidad(' . $reg->idPublicidad . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPublicidad(' . $reg->idPublicidad . ')"><i class="fa fa-trash"></i></button> ';
        }
    }

    public function ListarPublicidad()
    {
        $rspta = $this->MPublicidad->ListarPublicidad();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $reg->linkPublicidad,
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


    public function InsertUpdatePublicidad()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $this->form_validation->set_rules('PublicidadTitulo', 'Titulo del Promoción', 'trim|required|min_length[3]|max_length[150]');
        $this->form_validation->set_rules('PublicidadLink', 'Link de Promoción', 'trim|required|min_length[3]|max_length[120]');



        if ($this->form_validation->run() == true) {
            /* Registras Publicidad */
            if (empty($_POST['PublicidadidPublicidad'])) {
                /* valida Publicidad */
                $validacion = $this->Recurso->Validaciones('publicidad', 'NombrePublicidad', $_POST['PublicidadTitulo']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Publicidad:  "' . $_POST['PublicidadTitulo'] . '" , ya se encuentra registrado ';
                }


                if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['PublicidadTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Publicidad/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }


                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro =$this->MPublicidad->RegistroPublicidad($Documento);

                    if($Documento!=null || $Documento!=''){

                       $nombre=str_replace(" ","_",$_POST['PublicidadTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Publicidad",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Publicidad Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                $validacion = $this->Recurso->Validaciones('publicidad', 'NombrePublicidad', $_POST['PublicidadTitulo'], 'idPublicidad', $_POST['PublicidadidPublicidad']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Publicidad:' . $_POST['PublicidadTitulo'] . ' ya se encuentra registrado <br>';
                }

                 if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['PublicidadTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Publicidad/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MPublicidad->UpdatePublicidad($Documento);

                    if($Documento!=null || $Documento!=''){

                        $nombre=str_replace(" ","_",$_POST['PublicidadTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Publicidad",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Publicidad Modificado con exito.';
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

    public function ObtenerPublicidad()
    {
        $data = $this->MPublicidad->ObtenerPublicidad();
        if($data->imagenPublicidad!=null){
            $ruta="assets/images/".$data->imagenPublicidad;
            // Cargando la imagen
            $archivo = file_get_contents($ruta);
            // Decodificando la imagen en base64
            $base64 = 'data:image/jpg;base64,' . base64_encode($archivo);
            $data->imagenPublicidad=$base64;
        }
        echo json_encode($data);
    }
    public function EliminarPublicidad()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

         $respta = $this->MPublicidad->ObtenerPublicidad();
            if($respta->imagenPublicidad!=null){
               $linkEliminar='assets/images/'.$respta->imagenPublicidad;
                if(file_exists($linkEliminar)){
                     unlink($linkEliminar);
                  }
            }

        $delete = $this->MPublicidad->EliminarPublicidad();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Publicidad Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarPublicidad()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MPublicidad->EstadoPublicidad(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Publicidad Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarPublicidad()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MPublicidad->EstadoPublicidad(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Publicidad Inhabilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Inhabilitar en base de datos Error:' . $disable["errDB"]["code"] . ':' . $disable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }

}
