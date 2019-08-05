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

            return '
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarDelivery(' . $reg->idDelivery . ",'" . $reg->Ubicacion . "'" . ')"><i class="fa fa-trash"></i></button>
               ';

    }


    public function ListarTarifa()
    {
        $rspta = $this->MTarifa->ListarTarifa();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => $reg->Ubicacion,
                "1" => "S/. ".$reg->precioDelivery,
                "2" => $reg->fechaRegistro,
                "3" => $reg->fechaUpdate,
                "4" => $this->BuscarAccion($reg)
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


        $this->form_validation->set_rules('TarifaPrecioBaseV', 'Precio de Alquiler', 'trim|required|min_length[3]|max_length[12]');
        $this->form_validation->set_rules('TarifaPrecioVentaV', 'Precio de Venta', 'trim|required|min_length[3]|max_length[12]');

        if ($this->form_validation->run() == true) {


            $VerificarRegistro=$this->Recurso->Validaciones('tarifa','Producto_idProducto',$_POST["idProducto"]);

            if($VerificarRegistro>0){
                //Actualizar
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
            }else{
                //Registrar

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
            $data = array(
                'Error' => true,
                'Tipo' => 'warning',
                'Mensaje' => validation_errors('<li>', '</li>')
            );
        }
        echo json_encode($data);
    }

    public function InsertUpdateDelivery()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('DeliveryPrecio', 'Delivery Precio', 'trim|required|min_length[3]|max_length[12]');

        if ($this->form_validation->run() == true) {
            /* Registras Grupo */
            if (empty($_POST['Delivery_idDelivery'])) {



                /* valida Delivery */
                $Validacion=$this->MTarifa->ValidacionUbigeo();

                if ($Validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Delivery para la Ubicación, ya se encuentra registrado ';
                }
                if($_POST["tarifaDelivery"]==0){
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Debe ingresar un moneto Válido.';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MTarifa->RegistroDelivery();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Delivery Registrado con exito.';
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
               /* valida Delivery */
                $Validacion=$this->MTarifa->ValidacionUbigeo();

                if ($Validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Delivery para la Ubicación, ya se encuentra registrado ';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MTarifa->UpdateDelivery();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Delivery Modificado con exito.';
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
    public function EliminarDelivery()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MTarifa->EliminarDelivery();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Delivery Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }


}
/* End of file MenuPhp.php */
