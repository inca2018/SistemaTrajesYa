<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gestion/MPedido');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Gestion/Pedido');
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
            <button type="button" title="SubPedidos" class="btn btn-grd-info btn-mini btn-round" onclick="SubPedidos(' . $reg->idPedido . ')"><i class="fa fa-tasks"></i></button>
            <button type="button" title="Editar" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarPedido(' . $reg->idPedido . ')"><i class="fa fa-edit"></i></button>
            <button type="button"  title="Inabilitar" class="btn btn-grd-primary btn-mini btn-round" onclick="InabilitarPedido(' . $reg->idPedido . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-down"></i></button>
            <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPedido(' . $reg->idPedido . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-trash"></i></button>
               ';
        } elseif ($reg->Estado_idEstado == 2) {
            return '<button type="button"  title="Habilitar" class="btn btn-grd-info btn-mini btn-round" onclick="HabilitarPedido(' . $reg->idPedido . ",'" . $reg->Titulo . "'" . ')"><i class="fa fa-arrow-circle-up"></i></button> <button type="button"  title="Eliminar" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarPedido(' . $reg->idPedido . ')"><i class="fa fa-trash"></i></button> ';
        }
    }

    public function ListarPedido()
    {
        $rspta = $this->MPedido->ListarPedido();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(

                "0" => $reg->Titulo,
                "1" => $this->BuscarImagen($reg),
                "2" => $reg->grupoPedido,
                "3" => $this->BuscarAccion($reg),
                "4" => $reg->Descripcion,
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

    public function InsertUpdatePedido()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $this->form_validation->set_rules('PedidoTitulo', 'Titulo del Pedido', 'trim|required|min_length[3]|max_length[120]');

        $this->form_validation->set_rules('PedidoGrupo', 'Grupo de Pedido', 'required|callback_check_default');
        $this->form_validation->set_message('check_default', 'El campo Grupo es Obligatorio');

        if ($this->form_validation->run() == true) {
            /* Registras Pedido */
            if (empty($_POST['PedidoidPedido'])) {
                /* valida Pedido */
                $validacion = $this->Recurso->Validaciones('Pedido', 'NombrePedido', $_POST['PedidoTitulo']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Pedido:  "' . $_POST['PedidoTitulo'] . '" , ya se encuentra registrado ';
                }


                if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['PedidoTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Pedido/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }


                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro =$this->MPedido->RegistroPedido($Documento);

                    if($Documento!=null || $Documento!=''){

                       $nombre=str_replace(" ","_",$_POST['PedidoTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Pedido",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Pedido Registrado con exito.';
                    } else {
                        $data = array(
                            'Error' => true,
                            'Tipo' => 'danger',
                            'Mensaje' => 'Error al Registrar en base de datos Error:' . $registro["errDB"]["code"] . ':' . $registro["errDB"]["message"] . ', Comuniquese con el area de sistemas'
                        );
                    }
                }
            } else {
                $validacion = $this->Recurso->Validaciones('Pedido', 'NombrePedido', $_POST['PedidoTitulo'], 'idPedido', $_POST['PedidoidPedido']);
                if ($validacion > 0) {
                    $data['Error'] = true;
                    $data['Mensaje'] .= 'Pedido:' . $_POST['PedidoTitulo'] . ' ya se encuentra registrado <br>';
                }

                 if ($_POST['Imagenes']!= '') {
                     $nombre=str_replace(" ","_",$_POST['PedidoTitulo']);
                     $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                     $Documento = "Pedido/".$nombre.".jpg";
                } else {
                    $Documento = null;
                }

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MPedido->UpdatePedido($Documento);

                    if($Documento!=null || $Documento!=''){

                        $nombre=str_replace(" ","_",$_POST['PedidoTitulo']);
                        $nombre=mb_convert_case(mb_strtolower($nombre), MB_CASE_TITLE, "UTF-8");
                        $Subida=$this->Recurso->GuardarImagenes($_POST['Imagenes'],"Pedido",1,$nombre.".jpg");
                    }

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Pedido Modificado con exito.';
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

    public function ObtenerPedido()
    {
        $data = $this->MPedido->ObtenerPedido();
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
    public function EliminarPedido()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

         $respta = $this->MPedido->ObtenerPedido();
            if($respta->imagenPortada!=null){
               $linkEliminar='assets/images/'.$respta->imagenPortada;
                if(file_exists($linkEliminar)){
                     unlink($linkEliminar);
                  }
            }

        $delete = $this->MPedido->EliminarPedido();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Pedido Eliminado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function HabilitarPedido()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $enable = $this->MPedido->EstadoPedido(1);
        if ($enable['accion']) {
            $data['Mensaje'] .= 'Pedido Habilitado con exito';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Habilitar en base de datos Error:' . $enable["errDB"]["code"] . ':' . $enable["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }
    public function InhabilitarPedido()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $disable = $this->MPedido->EstadoPedido(2);
        if ($disable['accion']) {
            $data['Mensaje'] .= 'Pedido Inhabilitado con exito';
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
      		 $rspta = $this->MPedido->ListarGrupo();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idGrupo . '>' . $reg->Descripcion . '</option>';
            }
    }

}
