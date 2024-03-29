<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MGrupo');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Grupo');
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

            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarGrupo(' . $reg->idGrupo . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarGrupo(' . $reg->idGrupo . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarGrupo(' . $reg->idGrupo . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarGrupo(' . $reg->idGrupo . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarGrupo(' . $reg->idGrupo . ')"><i class="fa fa-trash"></i></button> ';
        }
    }



    public function ListarGrupo()
    {
        $rspta = $this->MGrupo->ListarGrupo();
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

    public function InsertUpdateGrupo()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('GrupoTitulo', 'Titulo del Grupo', 'trim|required|min_length[3]|max_length[120]');

        if ($this->form_validation->run() == true) {
            /* Registras Grupo */
            if (empty($_POST['GrupoidGrupo'])) {
                /* valida Grupo */
                $VRuc = $this->Recurso->Validaciones('grupo', 'Descripcion', $_POST['GrupoTitulo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Grupo:  "' . $_POST['GrupoTitulo'] . '" , ya se encuentra registrado ';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MGrupo->RegistroGrupo();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Grupo Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar Grupo */
                /* valida Grupo */
                $VRuc = $this->Recurso->Validaciones('grupo', 'Descripcion', $_POST['GrupoTitulo'], 'idGrupo', $_POST['GrupoidGrupo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Grupo:' . $_POST['GrupoTitulo'] . ' ya se encuentra registrado <br>';
                }
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MGrupo->UpdateGrupo();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Grupo Modificado con exito.';
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

    public function ObtenerGrupo()
    {
        $data = $this->MGrupo->ObtenerGrupo();
        echo json_encode($data);
    }
    public function EliminarGrupo()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MGrupo->EliminarGrupo();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Grupo Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarGrupo()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MGrupo->EstadoGrupo(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Grupo Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarGrupo()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MGrupo->EstadoGrupo(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Grupo Inhabilitado con exito';
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
      		 $rspta = $this->MGrupo->ListarPrioridad();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idPrioridad . '>' . $reg->Descripcion . '</option>';
            }
    }
}
/* End of file MenuPhp.php */
