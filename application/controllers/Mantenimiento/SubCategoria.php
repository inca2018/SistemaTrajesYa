<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubCategoria extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MSubCategoria');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/SubCategoria');
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
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarSubCategoria(' . $reg->idSubCategoria . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarSubCategoria(' . $reg->idSubCategoria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarSubCategoria(' . $reg->idSubCategoria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->Estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarSubCategoria(' . $reg->idSubCategoria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarSubCategoria(' . $reg->idSubCategoria . ')"><i class="fa fa-trash"></i></button> ';
        }
    }

    public function ListarSubCategoria()
    {
        $rspta = $this->MSubCategoria->ListarSubCategoria();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $this->BuscarImagen($reg),
                "2" => $this->BuscarAccion($reg),
                "3" => $reg->Descripcion,
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


    public function InsertUpdateSubCategoria()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

     $this->form_validation->set_rules('SubCategoriaTitulo', 'Titulo del SubCategoria','trim|required|min_length[3]|max_length[120]');


        if ($this->form_validation->run() == true) {
            /* Registras SubCategoria */
            if (empty($_POST['SubCategoriaidSubCategoria'])) {
                /* valida SubCategoria */
                $validacion = $this->Recurso->Validaciones('SubCategoria', 'NombreSubCategoria', $_POST['SubCategoriaTitulo']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'SubCategoria:  "' . $_POST['SubCategoriaTitulo'] . '" , ya se encuentra registrado ';
                }


                if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['SubCategoriaTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "SubCategoria/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }


                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro =$this->MSubCategoria->RegistroSubCategoria($Documento);

                    if($Documento!=null || $Documento!=''){

                        $nombre=str_replace(" ","_",$_POST['SubCategoriaTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"SubCategoria",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'SubCategoria Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                $validacion = $this->Recurso->Validaciones('SubCategoria', 'NombreSubCategoria', $_POST['SubCategoriaTitulo'], 'idSubCategoria', $_POST['SubCategoriaidSubCategoria']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'SubCategoria:' . $_POST['SubCategoriaTitulo'] . ' ya se encuentra registrado <br>';
                }

                 if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['SubCategoriaTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "SubCategoria/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MSubCategoria->UpdateSubCategoria($Documento);

                    if($Documento!=null || $Documento!=''){

                        $nombre=str_replace(" ","_",$_POST['SubCategoriaTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"SubCategoria",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'SubCategoria Modificado con exito.';
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

    public function ObtenerSubCategoria()
    {
        $data = $this->MSubCategoria->ObtenerSubCategoria();
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
    public function EliminarSubCategoria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

         $respta = $this->MSubCategoria->ObtenerSubCategoria();
            if($respta->imagenPortada!=null){
               $linkEliminar='assets/images/'.$respta->imagenPortada;
                if(file_exists($linkEliminar)){
                     unlink($linkEliminar);
                  }
            }

        $delete = $this->MSubCategoria->EliminarSubCategoria();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'SubCategoria Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarSubCategoria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MSubCategoria->EstadoSubCategoria(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'SubCategoria Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarSubCategoria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MSubCategoria->EstadoSubCategoria(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'SubCategoria Inhabilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Inhabilitar en base de datos Error:' . $disable["errDB"]["code"] . ':' . $disable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function ListarGrupo(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MSubCategoria->ListarGrupo();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idGrupo . '>' . $reg->Descripcion . '</option>';
            }
    }

}
