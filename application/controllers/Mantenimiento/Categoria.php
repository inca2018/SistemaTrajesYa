<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MCategoria');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Categoria');
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
            <button type="button" title="SubCategorias" class="btn btn-grd-info btn-mini btn-round" onclick="SubCategorias(' . $reg->idCategoria . ')"><i class="fa fa-tasks"></i></button>
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarCategoria(' . $reg->idCategoria . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarCategoria(' . $reg->idCategoria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarCategoria(' . $reg->idCategoria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->Estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarCategoria(' . $reg->idCategoria . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarCategoria(' . $reg->idCategoria . ')"><i class="fa fa-trash"></i></button> ';
        }
    }

    public function ListarCategoria()
    {
        $rspta = $this->MCategoria->ListarCategoria();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $reg->grupoCategoria,
                "2" => $this->BuscarImagen($reg),
                "3" => $reg->Descripcion,
                "4" => $this->BuscarAccion($reg),
                "5" => $reg->fechaRegistro,
                "6" => $reg->fechaUpdate,
                "7" => $this->BuscarEstado($reg)
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

    public function InsertUpdateCategoria()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $this->form_validation->set_rules('CategoriaTitulo', 'Titulo del Categoria', 'trim|required|min_length[3]|max_length[120]');

        $this->form_validation->set_rules('CategoriaGrupo', 'Grupo de Categoria', 'required|callback_check_default');
        $this->form_validation->set_message('check_default', 'El campo Grupo es Obligatorio');

        if ($this->form_validation->run() == true) {
            /* Registras Categoria */
            if (empty($_POST['CategoriaidCategoria'])) {
                /* valida Categoria */
                $validacion = $this->Recurso->Validaciones('categoria', 'NombreCategoria', $_POST['CategoriaTitulo']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Categoria:  "' . $_POST['CategoriaTitulo'] . '" , ya se encuentra registrado ';
                }


                if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace("ñ","n",str_replace(" ","_",$_POST['CategoriaTitulo']));
                     $Documento = "Categoria/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }


                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro =$this->MCategoria->RegistroCategoria($Documento);

                    if($Documento!=null || $Documento!=''){

                        $nombre=str_replace("ñ","n",str_replace(" ","_",$_POST['CategoriaTitulo']));
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Categoria",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Categoria Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                $validacion = $this->Recurso->Validaciones('categoria', 'NombreCategoria', $_POST['CategoriaTitulo'], 'idCategoria', $_POST['CategoriaidCategoria']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Categoria:' . $_POST['CategoriaTitulo'] . ' ya se encuentra registrado <br>';
                }

                 if ($_POST['Imagenes']!= '') {
                    $nombre=str_replace("ñ","n",str_replace(" ","_",$_POST['CategoriaTitulo']));
                     $Documento = "Categoria/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MCategoria->UpdateCategoria($Documento);

                    if($Documento!=null || $Documento!=''){

                        $nombre=str_replace("ñ","n",str_replace(" ","_",$_POST['CategoriaTitulo']));
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Categoria",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Categoria Modificado con exito.';
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

    public function ObtenerCategoria()
    {
        $data = $this->MCategoria->ObtenerCategoria();
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
    public function EliminarCategoria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MCategoria->EliminarCategoria();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Categoria Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarCategoria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MCategoria->EstadoCategoria(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Categoria Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarCategoria()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MCategoria->EstadoCategoria(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Categoria Inhabilitado con exito';
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
      		 $rspta = $this->MCategoria->ListarGrupo();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idGrupo . '>' . $reg->Descripcion . '</option>';
            }
    }

}
