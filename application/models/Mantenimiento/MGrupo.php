<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Lima");
$FechaAhora = date("Y-m-d H:i:s");
$GLOBALS    = array(
    'FechaAhora' => $FechaAhora,
    'idUsuario' => $_SESSION['idLogin']
);

class MGrupo extends CI_Model
{
    protected $glob;
    public function __construct()
    {
        parent::__construct();
        global $GLOBALS;
        $this->glob =& $GLOBALS;
    }
    public function RegistroGrupo()
    {
        $data= array(
            'Descripcion' => $this->input->post('GrupoTitulo'),
            'Estado_idEstado' => 1,
            'fechaRegistro' => $this->glob['FechaAhora']
        );
        $insert_data["Registro"] = $this->db->insert('grupo', $data);
        $insert_data["errDB"]    = $this->db->error();

         /** Registro de Historial **/
        $Mensaje=" Se Registró nuevo Grupo: ".$this->input->post('GrupoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(1,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function UpdateGrupo()
    {
        $data= array(
            'Descripcion' => $this->input->post('GrupoTitulo'),
            'fechaUpdate' => $this->glob['FechaAhora']
        );
        $this->db->where('idGrupo', $_POST['GrupoidGrupo']);
        $insert_data["Registro"] = $this->db->update('grupo', $data);
        $insert_data["errDB"]    = $this->db->error();


         /** Registro de Historial **/
        $Mensaje=" Se Actualizó  Grupo: ".$this->input->post('GrupoTitulo')."";
        $this->db->select("FU_REGISTRO_HISTORIAL(2,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();

        return $insert_data;
    }
    public function ListarGrupo()
    {

        $this->db->select('p.idGrupo,p.Descripcion as Titulo,DATE_FORMAT(p.fechaRegistro,"%d/%m/%Y") as fechaRegistro,DATE_FORMAT(p.fechaUpdate,"%d/%m/%Y") as fechaUpdate,p.estado_idEstado,e.DescripcionEstado as nombreEstado ');
        $this->db->from('grupo p');
        $this->db->join('estado e', 'e.idEstado=p.estado_idEstado');
        $this->db->order_by('p.idGrupo', 'desc');
        return $this->db->get();
    }
    public function ObtenerGrupo()
    {
        $this->db->where('idGrupo', $_POST['idGrupo']);
        $query = $this->db->get('grupo');
        return $query->row();
    }
    public function EliminarGrupo()
    {

         /** Recuperar Datos para Historial **/
        $this->db->where('idGrupo', $_POST['idGrupo']);
        $row = $this->db->get('grupo');
        $query=$row->row();

         /** Registro de Historial **/
        $Mensaje=" Se Eliminó  Grupo: ".$query->Descripcion."";
        $this->db->select("FU_REGISTRO_HISTORIAL(5,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
        $func["Historial"] = $this->db->get();



        $this->db->where('idGrupo', $_POST['idGrupo']);
        $delete_data["Delete"] = $this->db->delete('grupo');
        $delete_data["errDB"]  = $this->db->error();

        return $delete_data;

    }
    public function EstadoGrupo($codigo)
    {
        $data = array(
            'Estado_idEstado' => $codigo
        );

         /** Recuperar Datos para Historial **/
        $this->db->where('idGrupo', $_POST['idGrupo']);
        $row = $this->db->get('grupo');
        $query=$row->row();

        /** Registro de Historial **/
        $Mensaje="";
        if($codigo==1){
             $Mensaje=" Se Habilitó  Grupo: ".$query->Descripcion."";
             $this->db->select("FU_REGISTRO_HISTORIAL(3,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }else{
             $Mensaje=" Se Inhabilitó  Grupo: ".$query->Descripcion."";
             $this->db->select("FU_REGISTRO_HISTORIAL(4,".$this->glob['idUsuario'].",'".$Mensaje."','".$this->glob['FechaAhora']."') AS Respuesta");
             $func["Historial"] = $this->db->get();
        }

        $this->db->where('idGrupo', $_POST['idGrupo']);
        $insert_data["accion"] = $this->db->update('grupo', $data);
        $insert_data["errDB"]  = $this->db->error();
        return $insert_data;
    }


}
/* End of file MGrupo.php */
