<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Genero extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MGenero');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Genero');
    }
    public function BuscarEstado($reg)
    {
        if ($reg->estado_idEstado == '1' || $reg->estado_idEstado == 1) {
            return '<div class="badge badge-success">' . $reg->nombreEstado . '</div>';
        } elseif ($reg->estado_idEstado == '2' || $reg->estado_idEstado == 2) {
            return '<div class="badge badge-danger">' . $reg->nombreEstado . '</div>';
        } else {
            return '<div class="badge badge-primary">' . $reg->nombreEstado . '</div>';
        }
    }

    public function BuscarAccion($reg)
    {
        if ($reg->estado_idEstado == 1) {
            return '

            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarGenero(' . $reg->idGenero . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarGenero(' . $reg->idGenero . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarGenero(' . $reg->idGenero . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarGenero(' . $reg->idGenero . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarGenero(' . $reg->idGenero . ')"><i class="fa fa-trash"></i></button> ';
        }
    }



    public function ListarGenero()
    {
        $rspta = $this->MGenero->ListarGenero();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $reg->simbolo,
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

    public function InsertUpdateGenero()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('GeneroTitulo', 'Titulo del Genero', 'trim|required|min_length[3]|max_length[120]');
        $this->form_validation->set_rules('GeneroSimbolo', 'Simbolos de la Genero', 'trim|required|min_length[1]|max_length[3]');

        if ($this->form_validation->run() == true) {
            /* Registras Genero */
            if (empty($_POST['GeneroidGenero'])) {
                /* valida Genero */
                $VRuc = $this->Recurso->Validaciones('genero', 'NombreGenero', $_POST['GeneroTitulo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Genero:  "' . $_POST['GeneroTitulo'] . '" , ya se encuentra registrado ';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MGenero->RegistroGenero();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Genero Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar Genero */
                /* valida Genero */
                $VRuc = $this->Recurso->Validaciones('genero', 'NombreGenero', $_POST['GeneroTitulo'], 'idGenero', $_POST['GeneroidGenero']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Genero:' . $_POST['GeneroTitulo'] . ' ya se encuentra registrado <br>';
                }
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MGenero->UpdateGenero();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Genero Modificado con exito.';
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

    public function ObtenerGenero()
    {
        $data = $this->MGenero->ObtenerGenero();
        echo json_encode($data);
    }
    public function EliminarGenero()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MGenero->EliminarGenero();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Genero Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarGenero()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MGenero->EstadoGenero(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Genero Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarGenero()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MGenero->EstadoGenero(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Genero Inhabilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Inhabilitar en base de datos Error:' . $disable["errDB"]["code"] . ':' . $disable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function ListarPrioridad(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MGenero->ListarPrioridad();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idPrioridad . '>' . $reg->Descripcion . '</option>';
            }
    }
}
/* End of file MenuPhp.php */
