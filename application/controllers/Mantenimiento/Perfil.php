<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MPerfil');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Perfil');
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
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarPerfil(' . $reg->idPerfil . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarPerfil(' . $reg->idPerfil . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPerfil(' . $reg->idPerfil . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarPerfil(' . $reg->idPerfil . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPerfil(' . $reg->idPerfil . ')"><i class="fa fa-trash"></i></button> ';
        }
    }



    public function ListarPerfil()
    {
        $rspta = $this->MPerfil->ListarPerfil();
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

    public function InsertUpdatePerfil()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('PerfilTitulo', 'Titulo del Perfil', 'trim|required|min_length[3]|max_length[120]');

        if ($this->form_validation->run() == true) {
            /* Registras Perfil */
            if (empty($_POST['PerfilidPerfil'])) {
                /* valida Perfil */
                $VRuc = $this->Recurso->Validaciones('perfil', 'DescripcionPerfil', $_POST['PerfilTitulo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Perfil:  "' . $_POST['PerfilTitulo'] . '" , ya se encuentra registrado ';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MPerfil->RegistroPerfil();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Perfil Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar Perfil */
                /* valida Perfil */
                $VRuc = $this->Recurso->Validaciones('perfil', 'DescripcionPerfil', $_POST['PerfilTitulo'], 'idPerfil', $_POST['PerfilidPerfil']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Perfil:' . $_POST['PerfilTitulo'] . ' ya se encuentra registrado <br>';
                }
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MPerfil->UpdatePerfil();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Perfil Modificado con exito.';
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

    public function ObtenerPerfil()
    {
        $data = $this->MPerfil->ObtenerPerfil();
        echo json_encode($data);
    }
    public function EliminarPerfil()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MPerfil->EliminarPerfil();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Perfil Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarPerfil()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MPerfil->EstadoPerfil(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Perfil Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarPerfil()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MPerfil->EstadoPerfil(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Perfil Inhabilitado con exito';
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
      		 $rspta = $this->MPerfil->ListarPrioridad();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idPrioridad . '>' . $reg->Descripcion . '</option>';
            }
    }
}
/* End of file MenuPhp.php */
