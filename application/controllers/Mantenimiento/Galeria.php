<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galeria extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MGaleria');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Galeria');
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
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarGaleria(' . $reg->idGaleria . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarGaleria(' . $reg->idGaleria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarGaleria(' . $reg->idGaleria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->Estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarGaleria(' . $reg->idGaleria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarGaleria(' . $reg->idGaleria . ')"><i class="fa fa-trash"></i></button> ';
        }
    }

    public function ListarGaleria()
    {
        $rspta = $this->MGaleria->ListarGaleria();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $this->BuscarImagen($reg),
                "2" => $this->BuscarAccion($reg),
                "3" => $reg->fechaRegistro,
                "4" => $reg->fechaUpdate,
                "5" => $this->BuscarEstado($reg)
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



    public function InsertUpdateGaleria()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $this->form_validation->set_rules('GaleriaTitulo', 'Titulo del Imagen', 'trim|required|min_length[3]|max_length[120]');

        if ($this->form_validation->run() == true) {
            /* Registras Galeria */
            if (empty($_POST['GaleriaidGaleria'])) {
                /* valida Galeria */
                $validacion = $this->Recurso->Validaciones('galeria', 'NombreGaleria', $_POST['GaleriaTitulo']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Galeria:  "' . $_POST['GaleriaTitulo'] . '" , ya se encuentra registrado ';
                }
                 $cantidadRegistros = $this->Recurso->Validaciones('galeria', 'Producto_idProducto', $_POST['idProducto']);
                if ($cantidadRegistros == 10) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'No puede Registrar mas de 10 Imagenes.';
                }

                if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['GaleriaTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Galeria/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }


                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro =$this->MGaleria->RegistroGaleria($Documento);

                    if($Documento!=null || $Documento!=''){

                       $nombre=str_replace(" ","_",$_POST['GaleriaTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Galeria",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Galeria Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                $validacion = $this->Recurso->Validaciones('Galeria', 'NombreGaleria', $_POST['GaleriaTitulo'], 'idGaleria', $_POST['GaleriaidGaleria']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Galeria:' . $_POST['GaleriaTitulo'] . ' ya se encuentra registrado <br>';
                }

                 if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['GaleriaTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Galeria/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                     if($Documento!=null || $Documento!=''){
                         $respta = $this->MGaleria->ObtenerGaleria2();
                            if($respta->imagenPortada!=null){
                               $linkEliminar='assets/images/'.$respta->imagenPortada;
                                if(file_exists($linkEliminar)){
                                     unlink($linkEliminar);
                                     $nombre=str_replace(" ","_",$_POST['GaleriaTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Galeria",1,$nombre.".jpg");
                                  }
                            }
                    }

                    $registro = $this->MGaleria->UpdateGaleria($Documento);

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Galeria Modificado con exito.';
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

    public function ObtenerGaleria()
    {
        $data = $this->MGaleria->ObtenerGaleria();
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
    public function EliminarGaleria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

         $respta = $this->MGaleria->ObtenerGaleria();
            if($respta->imagenPortada!=null){
               $linkEliminar='assets/images/'.$respta->imagenPortada;
                if(file_exists($linkEliminar)){
                     unlink($linkEliminar);
                  }
            }

        $delete = $this->MGaleria->EliminarGaleria();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Galeria Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarGaleria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MGaleria->EstadoGaleria(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Galeria Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarGaleria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MGaleria->EstadoGaleria(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Galeria Inhabilitado con exito';
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
      		 $rspta = $this->MGaleria->ListarGrupo();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idGrupo . '>' . $reg->Descripcion . '</option>';
            }
    }

}
