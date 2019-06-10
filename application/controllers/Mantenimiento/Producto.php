<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Producto extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mantenimiento/MProducto');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Mantenimiento/Producto');
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

            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarProducto(' . $reg->idProducto . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarProducto(' . $reg->idProducto . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarProducto(' . $reg->idProducto . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarProducto(' . $reg->idProducto . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarProducto(' . $reg->idProducto . ')"><i class="fa fa-trash"></i></button> ';
        }
    }



    public function ListarProducto()
    {
        $rspta = $this->MProducto->ListarProducto();
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

    public function InsertUpdateProducto()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );
        $this->form_validation->set_rules('ProductoTitulo', 'Titulo del Producto', 'trim|required|min_length[3]|max_length[120]');

        if ($this->form_validation->run() == true) {
            /* Registras Producto */
            if (empty($_POST['ProductoidProducto'])) {
                /* valida Producto */
                $VRuc = $this->Recurso->Validaciones('Producto', 'NombreProducto', $_POST['ProductoTitulo']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Producto:  "' . $_POST['ProductoTitulo'] . '" , ya se encuentra registrado ';
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MProducto->RegistroProducto();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Producto Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                /* modificar Producto */
                /* valida Producto */
                $VRuc = $this->Recurso->Validaciones('Producto', 'NombreProducto', $_POST['ProductoTitulo'], 'idProducto', $_POST['ProductoidProducto']);
                if ($VRuc > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Producto:' . $_POST['ProductoTitulo'] . ' ya se encuentra registrado <br>';
                }
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MProducto->UpdateProducto();
                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Producto Modificado con exito.';
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

    public function ObtenerProducto()
    {
        $data = $this->MProducto->ObtenerProducto();
        echo json_encode($data);
    }
    public function EliminarProducto()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MProducto->EliminarProducto();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Producto Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarProducto()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MProducto->EstadoProducto(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Producto Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarProducto()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MProducto->EstadoProducto(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Producto Inhabilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Inhabilitar en base de datos Error:' . $disable["errDB"]["code"] . ':' . $disable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function ListarCategoria(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarCategoria();
            foreach ($rspta->result() as $reg) {
             	echo '<option data-grupo="'.$reg->Grupo_idGrupo.'"  value=' . $reg->idCategoria . '>' . $reg->NombreCategoria . '</option>';
            }
    }
     public function ListarSubCategoria(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarSubCategoria();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idSubCategoria . '>' . $reg->NombreSubCategoria . '</option>';
            }
    }

     public function ListarDepartamento(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarDepartamento();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idDepartamento . '>' . $reg->departamento . '</option>';
            }
    }
    public function ListarProvincia(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarProvincia();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idProvincia . '>' . $reg->provincia . '</option>';
            }
    }
     public function ListarDistrito(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MProducto->ListarDistrito();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idDistrito . '>' . $reg->distrito . '</option>';
            }
    }



}
/* End of file MenuPhp.php */
