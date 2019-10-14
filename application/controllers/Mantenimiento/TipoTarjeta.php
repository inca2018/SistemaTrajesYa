<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoTarjeta extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MTipoTarjeta');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/TipoTarjeta');
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

            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarTipoTarjeta(' . $reg->idTipoTarjeta . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarTipoTarjeta(' . $reg->idTipoTarjeta . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarTipoTarjeta(' . $reg->idTipoTarjeta . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarTipoTarjeta(' . $reg->idTipoTarjeta . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarTipoTarjeta(' . $reg->idTipoTarjeta . ')"><i class="fa fa-trash"></i></button> ';
        }
    }



    public function ListarTipoTarjeta()
    {
        $rspta = $this->MTipoTarjeta->ListarTipoTarjeta();
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

    public function InsertUpdateTipoTarjeta()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('TipoTarjetaTitulo', 'Titulo del TipoTarjeta', 'trim|required|min_length[3]|max_length[120]');

        if ($this->form_validation->run() == true) {
            /* Registras TipoTarjeta */
            if (empty($_POST['TipoTarjetaidTipoTarjeta'])) {
                /* valida TipoTarjeta */
                $VRuc = $this->Recurso->Validaciones('tipo_tarjeta', 'NombreTarjeta', $_POST['TipoTarjetaTitulo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'TipoTarjeta:  "' . $_POST['TipoTarjetaTitulo'] . '" , ya se encuentra registrado ';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MTipoTarjeta->RegistroTipoTarjeta();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'TipoTarjeta Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar TipoTarjeta */
                /* valida TipoTarjeta */
                $VRuc = $this->Recurso->Validaciones('tipo_tarjeta', 'NombreTarjeta', $_POST['TipoTarjetaTitulo'], 'idTipoTarjeta', $_POST['TipoTarjetaidTipoTarjeta']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'TipoTarjeta:' . $_POST['TipoTarjetaTitulo'] . ' ya se encuentra registrado <br>';
                }
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MTipoTarjeta->UpdateTipoTarjeta();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'TipoTarjeta Modificado con exito.';
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

    public function ObtenerTipoTarjeta()
    {
        $data = $this->MTipoTarjeta->ObtenerTipoTarjeta();
        echo json_encode($data);
    }
    public function EliminarTipoTarjeta()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MTipoTarjeta->EliminarTipoTarjeta();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'TipoTarjeta Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarTipoTarjeta()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MTipoTarjeta->EstadoTipoTarjeta(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'TipoTarjeta Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarTipoTarjeta()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MTipoTarjeta->EstadoTipoTarjeta(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'TipoTarjeta Inhabilitado con exito';
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
      		 $rspta = $this->MTipoTarjeta->ListarPrioridad();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idPrioridad . '>' . $reg->Descripcion . '</option>';
            }
    }
}
/* End of file MenuPhp.php */
