<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reserva extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gestion/MReserva');
        $this->load->model('Recurso');
    }
    public function index()
    {
        $this->load->view('Gestion/Reserva');
    }

    public function CorrelativoReserva($reg){
        $num=($reg->idReserva);
        $len=strlen($num);
        $numCeros=5-$len;
        $ceros=str_repeat("0",$numCeros);
        $re="R-".$ceros.$num;
        return $re;

    }
    public function CorrelativoReservaCodigo($codigo){
        $len=strlen($codigo);
        $numCeros=5-$len;
        $ceros=str_repeat("0",$numCeros);
        $re="R-".$ceros.$codigo;
        return $re;
    }

     public function CorrelativoProducto($reg){
        $num=($reg->idProducto);
        $len=strlen($num);
        $numCeros=5-$len;
        $ceros=str_repeat("0",$numCeros);
        $re="PTY-".$ceros.$num;
        return $re;

    }

    public function Codigolocal($reg){
        if($reg->idLocalAsignado==0){
            return $reg->NombreLocalAsignado;
        }else{
            $num=($reg->idLocalAsignado);
            $len=strlen($num);
            $numCeros=5-$len;
            $ceros=str_repeat("0",$numCeros);
            $re="LTY-".$ceros.$num;
            return $re." - ".$reg->NombreLocalAsignado;
        }

    }
    public function CalcularTotal($reg){
        $totalAlquiler=$reg->totalAlquiler;
        $totalVenta=$reg->totalVenta;
        $delivery=$reg->Delivery;
        $totalDesc=$reg->totalDescuento;

        if($reg->TipoReserva="1" || $reg->TipoReserva=1){
            $parcial1=($totalAlquiler+$delivery);
            $parcial2=$parcial1-$totalDesc;
            $total=($totalAlquiler+$delivery)-$totalDesc;
            return "S/ ".(number_format($total,2));
        }else{
            $total=($totalVenta+$delivery)-$totalDesc;
            return "S/ ".(number_format($total,2));
        }
    }

    public function TipoReserva($reg){
        if($reg->TipoReserva="1" || $reg->TipoReserva=1){
            return '<div class="badge badge-danger">URGENTE</div>';
        }else{
            return '<div class="badge badge-info">REGULAR</div>';
        }
    }

     public function CalcularBaseDetalleReserva($reg,$tipoReserva){
         if($tipoReserva="1" || $tipoReserva=1){
            return "S/ ".number_format($reg->PrecioAlquiler,2);
        }else{
            return "S/ ".number_format($reg->Precioventa,2);
        }
    }
     public function CalcularTotalDetalleReserva($reg,$tipoReserva){
         if($tipoReserva="1" || $tipoReserva=1){
            return "S/ ".number_format(($reg->PrecioAlquiler*$reg->Cantidad),2);
        }else{
            return "S/ ".number_format(($reg->Precioventa*$reg->Cantidad),2);
        }
    }

     public function MontoBase($reg){
        $totalAlquiler=number_format($reg->totalAlquiler,2);
        $totalVenta=number_format($reg->totalVenta,2);
        if($reg->TipoReserva="1" || $reg->TipoReserva=1){
            return "S/ ".$totalAlquiler;
        }else{
            return "S/ ".$totalVenta;
        }
    }

     public function BuscarEstado($reg)
    {
        if ($reg->Estado_idEstado == '3' || $reg->Estado_idEstado == 3) {
            return '<div class="badge badge-success">' . $reg->nombreEstado . '</div>';
        } elseif ($reg->Estado_idEstado == '4' || $reg->Estado_idEstado == 4) {
            return '<div class="badge badge-info">' . $reg->nombreEstado . '</div>';
        } elseif ($reg->Estado_idEstado == '6' || $reg->Estado_idEstado == 6) {
            return '<div class="badge badge-primary">' . $reg->nombreEstado . '</div>';
        } elseif ($reg->Estado_idEstado == '7' || $reg->Estado_idEstado == 7) {
            return '<div class="badge badge-danger">' . $reg->nombreEstado . '</div>';
        }
    }

    public function Acciones($reg)
    {
         $idPerfil=$this->session->userdata('idPerfil');
         $idUsuario=$this->session->userdata('idUsuario');
        //NUEVO
        if ($reg->Estado_idEstado == '3' || $reg->Estado_idEstado == 3) {
            return
            '<button type="button" title="Ver Detalle Reserva" class="btn btn-grd-warning btn-mini btn-round" onclick="DetalleReserva('.$reg->idReserva.',1,0,'.$reg->idUsuarioAsignado.')"><i class="fa fa-wpforms"></i></button>
            <button type="button"  title="Asignar Reserva" class="btn btn-grd-primary btn-mini btn-round" onclick="AsignarReserva('.$reg->idReserva.')"><i class="fa fa-check"></i></button>';
        //Atendido
        } elseif ($reg->Estado_idEstado == '4' || $reg->Estado_idEstado == 4) {

             if($idPerfil==1  || $idPerfil==4 ){
                  return
                    '<button type="button" title="Ver Detalle Reserva" class="btn btn-grd-warning btn-mini btn-round" onclick="DetalleReserva('.$reg->idReserva.',1,1,'.$reg->idUsuarioAsignado.')"><i class="fa fa-wpforms"></i></button>
                    <button type="button"  title="Asignar Reserva" class="btn btn-grd-primary btn-mini btn-round" onclick="AsignarReserva('.$reg->idReserva.')"><i class="fa fa-check"></i></button>
                    <button type="button"  title="Anular Reserva" class="btn btn-grd-danger btn-mini btn-round" onclick="AnularReserva('.$reg->idReserva.')"><i class="fa fa-times"></i></button>
                    <button type="button"  title="Cerrar Reserva" class="btn btn-grd-success btn-mini btn-round" onclick="CerrarReserva('.$reg->idReserva.')"><i class="fa fa-money"></i></button>';
             }elseif($idPerfil==3){

                 if($idUsuario===$reg->idUsuarioAsignado){
                      return
                    '<button type="button" title="Ver Detalle Reserva" class="btn btn-grd-warning btn-mini btn-round" onclick="DetalleReserva('.$reg->idReserva.',1,1,'.$reg->idUsuarioAsignado.')"><i class="fa fa-wpforms"></i></button>
                    <button type="button"  title="Anular Reserva" class="btn btn-grd-danger btn-mini btn-round" onclick="AnularReserva('.$reg->idReserva.')"><i class="fa fa-times"></i></button>
                    <button type="button"  title="Cerrar Reserva" class="btn btn-grd-success btn-mini btn-round" onclick="CerrarReserva('.$reg->idReserva.')"><i class="fa fa-money"></i></button>';
                 }else{
                       return
                    '<button type="button" title="Ver Detalle Reserva" class="btn btn-grd-warning btn-mini btn-round" onclick="DetalleReserva('.$reg->idReserva.',1,0,'.$reg->idUsuarioAsignado.')"><i class="fa fa-wpforms"></i></button>';
                  }
             }

        //Cerrado
        } elseif ($reg->Estado_idEstado == '6' || $reg->Estado_idEstado == 6) {
             return
            '<button type="button" title="Ver Detalle Reserva" class="btn btn-grd-warning btn-mini btn-round" onclick="DetalleReserva('.$reg->idReserva.',1,0,'.$reg->idUsuarioAsignado.')"><i class="fa fa-wpforms"></i></button>
            <button type="button"  title="Detalle de Cierre" class="btn btn-grd-danger btn-mini btn-round" onclick="DetalleCierre('.$reg->idReserva.')"><i class="fa fa-question-circle-o"></i></button>';
        //Anulado
        } elseif ($reg->Estado_idEstado == '7' || $reg->Estado_idEstado == 7) {
            return
            '<button type="button" title="Ver Detalle Reserva" class="btn btn-grd-warning btn-mini btn-round" onclick="DetalleReserva('.$reg->idReserva.',1,0,'.$reg->idUsuarioAsignado.')"><i class="fa fa-wpforms"></i></button>
            <button type="button"  title="Detalle Anulación" class="btn btn-grd-danger btn-mini btn-round" onclick="DetalleAnulacion('.$reg->idReserva.')"><i class="fa fa-question-circle-o"></i></button>
             ';
        }
    }

    public function AccionesDetalleReserva($reg,$EstadoReserva,$idUsuarioAsignado){
            $idPerfil=$this->session->userdata('idPerfil');
            $idUsuario=$this->session->userdata('idUsuario');
         switch ($EstadoReserva){
             case 3:
                return 'No Disponible';
                 break;
             case 4:

                 if($idPerfil==3){
                     if($idUsuario==$idUsuarioAsignado){
                     return
                    '<button type="button" title="Editar Item" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarItem('.$reg->idReservaItem.')"><i class="fa fa-edit"></i></button>
                    <button type="button"  title="Eliminar Item" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarItem('.$reg->idReservaItem.')"><i class="fa fa-trash"></i></button>';
                     }else{
                         return 'No Disponible';
                     }
                 }elseif($idPerfil==1 || $idPerfil==4){
                       return
                    '<button type="button" title="Editar Item" class="btn btn-grd-warning btn-mini btn-round" onclick="EditarItem('.$reg->idReservaItem.')"><i class="fa fa-edit"></i></button>
                    <button type="button"  title="Eliminar Item" class="btn btn-grd-danger btn-mini btn-round" onclick="EliminarItem('.$reg->idReservaItem.')"><i class="fa fa-trash"></i></button>';
                 }
                 break;
             case 6:
                 return 'No Disponible';
                 break;
             case 7:
                 return 'No Disponible';
                 break;

         }

    }

      public function ListarReserva()
    {
        $rspta = $this->MReserva->ListarReserva();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => $this->CorrelativoReserva($reg),
                "1" => $this->BuscarEstado($reg),
                "2" => $reg->fechaReserva.' '.$reg->tiempo,
                "3" => $this->TipoReserva($reg),
                "4" => $reg->UsuarioReserva,
                "5" => $this->CalcularTotal($reg),
                "6" => $this->Acciones($reg),
                "7" => $reg->fechaAsignacion,
                "8" => $reg->UsuarioAsignado,
                "9" => $this->Codigolocal($reg),
                "10" => $reg->fechaRegistro
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

     public function ObtenerReserva()
    {
        $data = $this->MReserva->ObtenerReserva();
        $data->CodigoReserva=$this->CorrelativoReservaCodigo($data->idReserva);
        echo json_encode($data);
    }


      public function ListarReservaDetalle()
    {
        $rspta = $this->MReserva->ListarReservaDetalle();
        $data  = array();

        foreach ($rspta->result() as $reg) {
            $data[] = array(
                "0" => "",
                "1" => $this->CorrelativoProducto($reg),
                "2" => $this->AccionesDetalleReserva($reg,$_POST["EstadoReserva"],$_POST["idUsuarioAsignado"]),
                "3" => $reg->NombreProducto,
                "4" => $reg->Cantidad." Trajes",
                "5" => number_format($reg->PrecioDescuento,0)." %",
                "6" => $this->CalcularBaseDetalleReserva($reg,$_POST["TipoReserva"]),
                "7" => $this->CalcularTotalDetalleReserva($reg,$_POST["TipoReserva"]),
                "8" => $reg->NombreMedida,
                "9" => $reg->DetalleProducto
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
     function check_default2($post_string)
    {
      return $post_string == '0' ? FALSE : TRUE;
    }
    function check_default3($post_string)
    {
      return $post_string == '0' ? FALSE : TRUE;
    }
    function check_default4($post_string)
    {
      return $post_string == '0' ? FALSE : TRUE;
    }

     public function InsertUpdateProductoReserva()
    {

        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $this->form_validation->set_rules('ReservaDetalleCantidad', 'Cantidad del Producto', 'trim|required|min_length[1]|max_length[2]');
        $this->form_validation->set_rules('ReservaDetalleCategoria', 'Categoria del Producto', 'required|callback_check_default');
        $this->form_validation->set_message('check_default', 'El campo Categoria del Producto es Obligatorio');
        $this->form_validation->set_rules('ReservaDetalleSubCategoria', 'SubCategoria del Producto', 'required|callback_check_default2');
        $this->form_validation->set_message('check_default2', 'El campo SubCategoria del Producto es Obligatorio');
        $this->form_validation->set_rules('ReservaDetalleProducto', 'Producto', 'required|callback_check_default3');
        $this->form_validation->set_message('check_default3', 'El campo Producto  es Obligatorio');
        $this->form_validation->set_rules('ReservaDetalleMedida', 'SubCategoria del Producto', 'required|callback_check_default4');
        $this->form_validation->set_message('check_default4', 'El campo Medida del Producto es Obligatorio');

        if ($this->form_validation->run() == true) {
            /* Registras Producto */
            if (empty($_POST['idReservaProductoItem'])) {
                /* valida Producto */

                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {

                    $registro = $this->MReserva->RegistroDetalleReservaProducto();

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Producto Registrado con exito en Reserva.';
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
                if ($data['Error']) {
                    $data['Tipo'] = 'warning';
                    $data['Mensaje'] .= 'Corregir los datos ingresados';
                } else {
                    $registro = $this->MReserva->UpdateDetalleReservaProducto();

                    if ($registro['Registro']) {
                        $data['Mensaje'] .= 'Producto Modificado con exito en Reserva.';
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

     public function EliminarProductoReserva()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );


        $delete = $this->MReserva->EliminarProductoReserva();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Producto Eliminado con exito de Reserva';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }

     public function AsignarReservaAccion()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MReserva->AsignarReservaAccion();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Usuario Asignado con exito a la Reserva';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }


         public function AnularProductoReserva()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MReserva->AnularProductoReserva();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Reserva Anulada con exito!';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }

     public function CerrarProductoReserva()
    {
        $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MReserva->CerrarProductoReserva();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Reserva Anulada con exito!';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }

    public function ActualizarObservaciones(){
          $data = array(
            'Error' => false,
            'Mensaje' => '',
            'Tipo' => 'success'
        );

        $delete = $this->MReserva->ActualizarObservaciones();
        if ($delete['Delete']) {
            $data['Mensaje'] .= 'Observaciones Actualizadas con exito.';
        } else {
            $data = array(
                'Error' => true,
                'Tipo' => 'danger',
                'Mensaje' => 'Error al Eliminar en base de datos Error:' . $delete["errDB"]["code"] . ':' . $delete["errDB"]["message"] . ', Comuniquese con el area de sistemas.'
            );
        }

        echo json_encode($data);
    }

        public function ObtenerReservaItem()
    {
        $data = $this->MReserva->ObtenerReservaItem();
        echo json_encode($data);
    }

    public function RecuperarDatosCierre(){
       $data = $this->MReserva->RecuperarDatosCierre();
        echo json_encode($data);
    }
    public function RecuperarIndicadores(){
        $data = $this->MReserva->RecuperarIndicadores();
        echo json_encode($data);
    }

     public function RecuperarDatosAnulacion(){
       $data = $this->MReserva->RecuperarDatosAnulacion();
        echo json_encode($data);
    }



      public function ListarUsuarioOpe(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MReserva->ListarUsuarioOpe();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idUsuario . '>' . $reg->DetalleUsuario . '</option>';
            }
    }

    public function ListarLocalesDisponibles(){
         echo '<option value="0"> --- SELECCIONE --- </option>';
      		 $rspta = $this->MReserva->ListarLocalesDisponibles();
            foreach ($rspta->result() as $reg) {
             	echo '<option   value=' . $reg->idLocal . '>' . $reg->NombreLocal . '</option>';
            }
    }

}
