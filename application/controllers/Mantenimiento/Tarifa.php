<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarifa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MTarifa');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Tarifa');
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

            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarTarifa(' . $reg->idTarifa . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarTarifa(' . $reg->idTarifa . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarTarifa(' . $reg->idTarifa . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarTarifa(' . $reg->idTarifa . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarTarifa(' . $reg->idTarifa . ')"><i class="fa fa-trash"></i></button> ';
        }
    }



    public function ListarTarifa()
    {
        $rspta = $this->MTarifa->ListarTarifa();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $this->BuscarAccion($reg),
                "2" => $reg->fechaRegistro,
                "3" => $reg->fechaUpdate,
                "4" => $this->BuscarEstado($reg)
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

    public function InsertUpdateTarifa()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('TarifaTitulo', 'Titulo del Tarifa', 'trim|required|min_length[3]|max_length[120]');

        if ($this->form_validation->run() == true) {
            /* Registras Tarifa */
            if (empty($_POST['TarifaidTarifa'])) {
                /* valida Tarifa */
                $VRuc = $this->Recurso->Validaciones('Tarifa', 'Descripcion', $_POST['TarifaTitulo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Tarifa:  "' . $_POST['TarifaTitulo'] . '" , ya se encuentra registrado ';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MTarifa->RegistroTarifa();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Tarifa Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar Tarifa */
                /* valida Tarifa */
                $VRuc = $this->Recurso->Validaciones('Tarifa', 'Descripcion', $_POST['TarifaTitulo'], 'idTarifa', $_POST['TarifaidTarifa']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Tarifa:' . $_POST['TarifaTitulo'] . ' ya se encuentra registrado <br>';
                }
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MTarifa->UpdateTarifa();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Tarifa Modificado con exito.';
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

    public function ObtenerTarifa()
    {
        $data = $this->MTarifa->ObtenerTarifa();
        echo json_encode($data);
    }
    public function EliminarTarifa()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MTarifa->EliminarTarifa();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Tarifa Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarTarifa()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MTarifa->EstadoTarifa(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Tarifa Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarTarifa()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MTarifa->EstadoTarifa(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Tarifa Inhabilitado con exito';
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
      		 $rspta = $this->MTarifa->ListarPrioridad();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idPrioridad . '>' . $reg->Descripcion . '</option>';
            }
    }
}
/* End of file MenuPhp.php */
