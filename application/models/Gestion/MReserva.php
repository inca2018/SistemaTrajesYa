<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin'],
    'idPerfil' => $_SESSION['idPerfil']
);

class MReserva extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }

    public function ListarReserva()
    {

        $this->db->select('r.idReserva,
        r.TipoReserva,
        DATE_FORMAT(r.fechaReserva,"%d/%m/%Y") as fechaReserva,
        DATE_FORMAT(r.fechaRegistro,"%d/%m/%Y") as fechaRegistro,
        DATE_FORMAT(r.fechaReserva,"%h:%m") as horaReserva,
        r.tiempo,
        tp.TipoPago,
        tc.NombreComprobante,
        IFNULL(tt.NombreTarjeta,"SIN TARJETA") as TarjetaReserva,
        CONCAT(u.NombreUsuario," ",u.ApellidosUsuario,"<BR> DNI: ",u.Dni) as UsuarioReserva,
        r.UbicacionDireccion,
        r.UbicacionReferencia,
        r.TelefonoContacto,
        r.condiciones,
        dis.distrito as DistritoReserva,
        e.DescripcionEstado,
        IFNULL((SELECT l.NombreLocal FROM local l WHERE l.idLocal=r.LocalAsignado),"Sin Local Asignado") as NombreLocalAsignado,
        r.LocalAsignado as idLocalAsignado,
        IFNULL(r.Observaciones,"") as Observaciones,
        r.Distrito_idDistrito,
        IFNULL(r.UsuarioAsignado,0) as idUsuarioAsignado,
        (SELECT u.usuario FROM usuario u WHERE u.idUsuario=r.UsuarioAsignado) as UsuarioAsignado,
        DATE_FORMAT(r.fechaAsignacion,"%d/%m/%Y") as fechaAsignacion,
        r.Estado_idEstado,
        e.DescripcionEstado as nombreEstado,
        (SELECT COUNT(*) FROM reserva_item ri WHERE ri.Reserva_idReserva=r.idReserva) as ItemsReserva,

        (SELECT SUM(ta.precioAlquiler*ri.Cantidad) FROM reserva_item ri INNER JOIN producto pro ON pro.idProducto=ri.Producto_idProducto INNER JOIN tarifa ta ON ta.Producto_idProducto=pro.idProducto  WHERE ri.Reserva_idReserva=r.idReserva ) as totalAlquiler,

        (SELECT SUM(ta.precioVenta*ri.Cantidad) FROM reserva_item ri INNER JOIN producto pro ON pro.idProducto=ri.Producto_idProducto INNER JOIN tarifa ta ON ta.Producto_idProducto=pro.idProducto  WHERE ri.Reserva_idReserva=r.idReserva ) as totalVenta,

        (SELECT de.precioDelivery FROM delivery de  WHERE de.Distrito_idDitrito=r.Distrito_idDistrito) as Delivery,

        TRUNCATE((SELECT SUM(((ta.precioAlquiler*ri.Cantidad)*ppro.Descuento)/100)
        FROM reserva_item ri  INNER JOIN producto prod ON prod.idProducto=ri.Producto_idProducto INNER JOIN tarifa ta ON ta.Producto_idProducto=prod.idProducto INNER JOIN producto_promocion ppro ON ppro.Producto_idProducto=prod.idProducto  WHERE ri.Reserva_idReserva=r.idReserva),2) as totalDescuento ');

        $this->db->from('reserva r ');
        $this->db->join('tipo_pago tp', 'tp.idTipoPago=r.TipoPago_idTipoPago','left');
        $this->db->join('tipo_comprobante tc', 'tc.idTipoComprobante=r.TipoDocumento_idTipoDocumento ','left');
        $this->db->join('tipo_tarjeta tt', 'tt.idTipoTarjeta=r.TipoTarjeta_idTipoTarjeta','left');
        $this->db->join('usuario u', 'u.idUsuario=r.Usuario_idUsuario','left');
        $this->db->join('distrito dis', 'dis.idDistrito=r.Distrito_idDistrito','left');
        $this->db->join('estado e', 'e.idEstado=r.Estado_idEstado','left');
        $this->db->order_by('r.idReserva', 'desc');
        return $this->db->get();
    }


      public function ObtenerReserva()
    {

        $this->db->select('r.idReserva,
        r.TipoReserva,
        DATE_FORMAT(r.fechaReserva,"%d/%m/%Y") as fechaReserva,
        DATE_FORMAT(r.fechaReserva,"%h:%m") as horaReserva,
        DATE_FORMAT(r.fechaRegistro,"%d/%m/%Y") as fechaRegistro,
        r.tiempo,
        tp.TipoPago,
        tc.NombreComprobante,
        IFNULL(tt.NombreTarjeta,"SIN TARJETA") as TarjetaReserva,
        CONCAT(u.NombreUsuario," ",u.ApellidosUsuario,"<BR> DNI: ",u.Dni) as UsuarioReserva,
        r.UbicacionDireccion,
        r.UbicacionReferencia,
        r.TelefonoContacto,
        r.condiciones,
        dis.distrito as DistritoReserva,
        e.DescripcionEstado,
        IFNULL((SELECT l.NombreLocal FROM local l WHERE l.idLocal=r.LocalAsignado),"Sin Local Asignado") as NombreLocalAsignado,
        r.LocalAsignado as idLocalAsignado,
        IFNULL(r.Observaciones,"") as Observaciones,
        r.Distrito_idDistrito,
        IFNULL(r.UsuarioAsignado,0) as idUsuarioAsignado,
        (SELECT u.usuario FROM usuario u WHERE u.idUsuario=r.UsuarioAsignado) as UsuarioAsignado,
        DATE_FORMAT(r.fechaAsignacion,"%d/%m/%Y") as fechaAsignacion,
        r.Estado_idEstado,
        e.DescripcionEstado as nombreEstado,
        (SELECT COUNT(*) FROM reserva_item ri WHERE ri.Reserva_idReserva=r.idReserva) as ItemsReserva,

        (SELECT SUM(ta.precioAlquiler*ri.Cantidad) FROM reserva_item ri INNER JOIN producto pro ON pro.idProducto=ri.Producto_idProducto INNER JOIN tarifa ta ON ta.Producto_idProducto=pro.idProducto  WHERE ri.Reserva_idReserva=r.idReserva ) as totalAlquiler,

        (SELECT SUM(ta.precioVenta*ri.Cantidad) FROM reserva_item ri INNER JOIN producto pro ON pro.idProducto=ri.Producto_idProducto INNER JOIN tarifa ta ON ta.Producto_idProducto=pro.idProducto  WHERE ri.Reserva_idReserva=r.idReserva ) as totalVenta,

        (SELECT de.precioDelivery FROM delivery de  WHERE de.Distrito_idDitrito=r.Distrito_idDistrito) as Delivery,

        TRUNCATE((SELECT SUM(((ta.precioAlquiler*ri.Cantidad)*ppro.Descuento)/100)
        FROM reserva_item ri  INNER JOIN producto prod ON prod.idProducto=ri.Producto_idProducto INNER JOIN tarifa ta ON ta.Producto_idProducto=prod.idProducto INNER JOIN producto_promocion ppro ON ppro.Producto_idProducto=prod.idProducto  WHERE ri.Reserva_idReserva=r.idReserva),2) as totalDescuento ');

        $this->db->from('reserva r');
        $this->db->join('tipo_pago tp', 'tp.idTipoPago=r.TipoPago_idTipoPago','left');
        $this->db->join('tipo_comprobante tc', 'tc.idTipoComprobante=r.TipoDocumento_idTipoDocumento ','left');
        $this->db->join('tipo_tarjeta tt', 'tt.idTipoTarjeta=r.TipoTarjeta_idTipoTarjeta','left');
        $this->db->join('usuario u', 'u.idUsuario=r.Usuario_idUsuario','left');
        $this->db->join('distrito dis', 'dis.idDistrito=r.Distrito_idDistrito','left');
        $this->db->join('estado e', 'e.idEstado=r.Estado_idEstado','left');
        $this->db->where('r.idReserva', $_POST['idReserva']);
        $query = $this->db->get();
        return $query->row();
    }
    public function ListarReservaDetalle(){

        $this->db->select('
        rei.idReservaItem,
        rei.Cantidad,
        pro.idProducto,
        pro.NombreProducto,
        IFNULL((SELECT ta.precioAlquiler FROM tarifa ta WHERE ta.Producto_idProducto=pro.idProducto),0.00)as PrecioAlquiler,
        IFNULL((SELECT ta.precioVenta FROM tarifa ta WHERE ta.Producto_idProducto=pro.idProducto),0.00)as Precioventa,
        IFNULL((SELECT pp.Descuento FROM producto_promocion pp WHERE pp.Producto_idProducto=pro.idProducto),0.00) as PrecioDescuento,me.NombreMedida,
        (SELECt CONCAT(g.Descripcion,"<BR>",ca.NombreCategoria,"<BR>",sub.NombreSubCategoria) FROM grupo g INNER JOIN categoria ca ON ca.Grupo_idGrupo=g.idGrupo INNER JOIN subcategoria sub ON sub.Categoria_idCategoria=ca.idCategoria INNER JOIN producto prod ON prod.SubCategoria_idSubCategoria=sub.idSubCategoria WHERE prod.idProducto=pro.idProducto) as DetalleProducto ');

        $this->db->from('reserva_item rei');
        $this->db->join('producto pro', 'pro.idProducto=rei.Producto_idProducto','inner');
        $this->db->join('medida me', 'me.idMedida=rei.Medida_idMedida','inner');
        $this->db->where('rei.Reserva_idReserva', $_POST['idReserva']);
        return $this->db->get();
    }


      public function RegistroDetalleReservaProducto()
    {

        $data= array(

            'Reserva_idReserva' =>$this->input->post('idReservaOculta'),
            'Producto_idProducto' =>$this->input->post('ReservaDetalleProducto'),
            'Cantidad' =>$this->input->post('ReservaDetalleCantidad'),
            'Medida_idMedida' =>$this->input->post('ReservaDetalleMedida'),
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('reserva_item', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Producto en Reserva Codigo : ".$this->input->post('idReservaOculta')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();


        return $insert_data;
    }

     public function UpdateDetalleReservaProducto()
    {

        $data= array(
            'Producto_idProducto' =>$this->input->post('ReservaDetalleProducto'),
            'Cantidad' =>$this->input->post('ReservaDetalleCantidad'),
            'Medida_idMedida' =>$this->input->post('ReservaDetalleMedida'),
        );
        $this->db->where('idReservaItem', $_POST['idReservaProductoItem']);
        $insert_data["Registro"] = $this->db->update('reserva_item', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Producto en Reserva Codigo : ".$this->input->post('idReservaOculta')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }



         public function EliminarProductoReserva()
    {

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Producto de Reserva" ;
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();


        $this->db->where('idReservaItem', $_POST['idReservaItem']);
        $delete_data["Delete"] = $this->db->delete('reserva_item');
        $delete_data["errDB"]  = $this->db->error();
        return $delete_data;

    }
       public function AsignarReservaAccion()
    {

         /** Registro de Historial **/
        $Mensaje="Se Asigno Usuario Cod:".$_POST["idUsuario"]." a la Reserva Cod:".$_POST["idReserva"] ;
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        $data= array(
            'Estado_idEstado' =>4,
            'UsuarioAsignado' =>$_POST["idUsuario"],
            'fechaAsignacion' => $this->glob['FechaAhora']
        );
        $this->db->where('idReserva', $_POST['idReserva']);
        $delete_data["Delete"] = $this->db->update('reserva',$data);
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;

    }
    public function ActualizarObservaciones(){

         /** Registro de Historial **/
        $Mensaje="Se Actualizo Observaciones Msg:".$_POST["Observaciones"]." a la Reserva Cod:".$_POST["idReserva"] ;
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        $data= array(
            'Observaciones' =>$_POST["Observaciones"],
            'LocalAsignado'=>$_POST["idLocal"]
        );
        $this->db->where('idReserva', $_POST['idReserva']);
        $delete_data["Delete"] = $this->db->update('reserva',$data);
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }


        public function AnularProductoReserva(){

         /** Registro de Historial **/
        $Mensaje="Se Anulo la Reserva Cod:".$_POST["idReserva"] ;
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        $data= array(
            'Estado_idEstado' =>7,
            'DetalleAnulacion' =>$_POST["DetalleAnulacion"],
            'fechaAnulacion' => $this->glob['FechaAhora']
        );
        $this->db->where('idReserva', $_POST['idReserva']);
        $delete_data["Delete"] = $this->db->update('reserva',$data);
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }

     public function CerrarProductoReserva(){

         /** Registro de Historial **/
        $Mensaje="Se Cerro la Reserva Cod:".$_POST["idReserva"] ;
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        $data= array(
            'Estado_idEstado' =>6,
            'DetalleCierre' =>$_POST["DetalleCierre"],
            'fechaCierre' => $this->glob['FechaAhora']
        );
        $this->db->where('idReserva', $_POST['idReserva']);
        $delete_data["Delete"] = $this->db->update('reserva',$data);
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;
    }


    public function ObtenerReservaItem()
    {
        $this->db->select("pro.idProducto,pro.Categoria_idCategoria as idCategoria,pro.SubCategoria_idSubCategoria as idSubCategoria,me.idMedida,ri.Cantidad");
        $this->db->from('reserva_item ri');
        $this->db->join('producto pro', 'pro.idProducto=ri.Producto_idProducto','inner');
        $this->db->join('medida me', 'me.idMedida=ri.Medida_idMedida','inner');
        $this->db->where('ri.idReservaItem', $_POST['idRerservaItem']);
        $query = $this->db->get();
        return $query->row();
    }

    public function RecuperarDatosCierre(){
          $this->db->select("r.DetalleCierre,DATE_FORMAT(r.fechaCierre,'%d/%m/%Y') as fechaCierre");
        $this->db->from('reserva r');
        $this->db->where('r.idReserva', $_POST['idReserva']);
        $query = $this->db->get();
        return $query->row();
    }
    public function RecuperarDatosAnulacion(){
          $this->db->select("r.DetalleAnulacion,DATE_FORMAT(r.fechaAnulacion,'%d/%m/%Y') as fechaAnulacion");
        $this->db->from('reserva r');
        $this->db->where('r.idReserva', $_POST['idReserva']);
        $query = $this->db->get();
        return $query->row();
    }

       public function ListarUsuarioOpe()
    {
        $idPerfil=$this->glob['idPerfil'];
        $idUsuario=$this->glob['idUsuario'];

           //Administrador
        if($idPerfil==1 || $idPerfil=="1"){
            $this->db->select("u.idUsuario,CONCAT(u.usuario,'/',u.NombreUsuario,' ',u.ApellidosUsuario) as DetalleUsuario");
            $this->db->from('usuario u');
            $this->db->where('u.perfil_idPerfil',1);
            $this->db->or_where('u.perfil_idPerfil',3);
            return $this->db->get();
            //Operador
        }elseif($idPerfil==3 || $idPerfil=="3"){
            $this->db->select("u.idUsuario,CONCAT(u.usuario,'/',u.NombreUsuario,' ',u.ApellidosUsuario) as DetalleUsuario");
            $this->db->from('usuario u');
            $this->db->where('u.idUsuario',$idUsuario);
            return $this->db->get();
            //Soporte
        }elseif($idPerfil==4 || $idPerfil=="4"){
            $this->db->select("u.idUsuario,CONCAT(u.usuario,'/',u.NombreUsuario,' ',u.ApellidosUsuario) as DetalleUsuario");
            $this->db->from('usuario u');
            $this->db->where('u.perfil_idPerfil',1);
            $this->db->or_where('u.perfil_idPerfil',3);
            return $this->db->get();
        }else{
            $this->db->select("u.idUsuario,CONCAT(u.usuario,'/',u.NombreUsuario,' ',u.ApellidosUsuario) as DetalleUsuario");
            $this->db->from('usuario u');
            $this->db->where('u.perfil_idPerfil',1);
            $this->db->or_where('u.perfil_idPerfil',3);
             return $this->db->get();
        }

    }

    public function ListarLocalesDisponibles(){
         $this->db->select("l.idLocal,l.NombreLocal");
            $this->db->from('local l');
            $this->db->where('l.Estado_idEstado',1);
            return $this->db->get();
    }

    public function RecuperarIndicadores(){

        $query = $this->db->query("CALL SP_INDICADORES_GESTION()");
        return $query->result();
    }

}
/* End of file MReserva.php */
