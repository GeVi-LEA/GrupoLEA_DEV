<?php

class EquipoLaboratorio {

    private $id;
    private $pruebaId;
    private $estatusId;
    private $unidadId;
    private $codigo;
    private $nombre;
    private $marca;
    private $modelo;
    private $numeroSerie;
    private $intervaloUso;
    private $intervaloTrabajo;
    private $intervaloPrueba;
    private $puntosCalibrar;
    private $factura;
    private $fechaCompra;
    private $fechaAlta;
    private $fechaBaja;
    private $observaciones;
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

        public function getId() {
        return $this->id;
    }

    public function getPruebaId() {
        return $this->pruebaId;
    }

    public function getEstatusId() {
        return $this->estatusId;
    }

    public function getUnidadId() {
        return $this->unidadId;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function getModelo() {
        return $this->modelo;
    }

    public function getNumeroSerie() {
        return $this->numeroSerie;
    }

    public function getIntervaloUso() {
        return $this->intervaloUso;
    }

    public function getIntervaloTrabajo() {
        return $this->intervaloTrabajo;
    }

    public function getIntervaloPrueba() {
        return $this->intervaloPrueba;
    }

    public function getPuntosCalibrar() {
        return $this->puntosCalibrar;
    }

    public function getFactura() {
        return $this->factura;
    }

    public function getFechaCompra() {
        return $this->fechaCompra;
    }

    public function getFechaAlta() {
        return $this->fechaAlta;
    }

    public function getFechaBaja() {
        return $this->fechaBaja;
    }

    public function getObservaciones() {
        return $this->observaciones;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setPruebaId($pruebaId): void {
        $this->pruebaId = $pruebaId;
    }

    public function setEstatusId($estatusId): void {
        $this->estatusId = $estatusId;
    }

    public function setUnidadId($unidadId): void {
        $this->unidadId = $unidadId;
    }

    public function setCodigo($codigo): void {
        $this->codigo = $codigo;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setMarca($marca): void {
        $this->marca = $marca;
    }

    public function setModelo($modelo): void {
        $this->modelo = $modelo;
    }

    public function setNumeroSerie($numeroSerie): void {
        $this->numeroSerie = $numeroSerie;
    }

    public function setIntervaloUso($intervaloUso): void {
        $this->intervaloUso = $intervaloUso;
    }

    public function setIntervaloTrabajo($intervaloTrabajo): void {
        $this->intervaloTrabajo = $intervaloTrabajo;
    }

    public function setIntervaloPrueba($intervaloPrueba): void {
        $this->intervaloPrueba = $intervaloPrueba;
    }

    public function setPuntosCalibrar($puntosCalibrar): void {
        $this->puntosCalibrar = $puntosCalibrar;
    }

    public function setFactura($factura): void {
        $this->factura = $factura;
    }

    public function setFechaCompra($fechaCompra): void {
        $this->fechaCompra = $fechaCompra;
    }

    public function setFechaAlta($fechaAlta): void {
        $this->fechaAlta = $fechaAlta;
    }

    public function setFechaBaja($fechaBaja): void {
        $this->fechaBaja = $fechaBaja;
    }

    public function setObservaciones($observaciones): void {
        $this->observaciones = $observaciones;
    }
    
    public function save() {
        $sql = "insert into catalogo_equipos_laboratorio values(null,'{$this->getPruebaId()}',  {$this->getUsuarioId()}, {$this->getTipoEquipo()}, '{$this->getModelo()}', {$this->getMarca()}, '{$this->getNumeroSerie()}', "
                . "'{$this->getFactura()}', '{$this->getProcesador()}', '{$this->getMemoriaRam()}', '{$this->getDiscoDuro()}', '{$this->getRedLan()}', '{$this->getRedWifi()}','{$this->getAplicaciones()}',";
         
                if($this->getFechaCompra() != null){
                    $sql.= "'{$this->getFechaCompra()}',";
                }else{
                    $sql.="null, ";
                }
                
               if($this->getFechaAsignacion() != null){
                    $sql.= "'{$this->getFechaAsignacion()}',";
                }  else{
                    $sql.="null, ";
                }
                  $sql.="null, curdate(), '{$this->getObservaciones()}');";
     
        $save = $this->db->query($sql);
        $result = false;
        if ($save) {
            $result = true;
        }
        return $result;
    }

    public function edit() {
        $sql =  "update catalogo_equipos_computo set usuario_id = {$this->getUsuarioId()}, tipo_equipo = {$this->getTipoEquipo()}, modelo = '{$this->getModelo()}', marca = {$this->getMarca()}, "
                . "numero_serie = '{$this->getNumeroSerie()}', factura = '{$this->getFactura()}', procesador = '{$this->getProcesador()}', memoria_ram = '{$this->getMemoriaRam()}', "
                . "disco_duro = '{$this->getDiscoDuro()}', red_lan = '{$this->getRedLan()}', red_wifi = '{$this->getRedWifi()}', aplicaciones = '{$this->getAplicaciones()}', ";
        
                if($this->getFechaCompra() != null){
                    $sql.= "fecha_compra = '{$this->getFechaCompra()}',";
                }else{
                    $sql.="fecha_compra = null, ";
                }
                
               if($this->getFechaAsignacion() != null){
                    $sql.= "fecha_asignacion = '{$this->getFechaAsignacion()}',";
                }  else{
                    $sql.="fecha_asignacion = null, ";
                }
        $sql = $sql . "observaciones ='{$this->getObservaciones()}' where id = {$this->getId()};";

        $save = $this->db->query($sql);
        $result = false;
        if ($save) {
            $result = true;
        }
        return $result;
    }

    public function delete() {
        $delete = $this->db->query("update catalogo_equipos_computo set fecha_baja = curdate() where id={$this->getId()}");
        $result = false;
        if ($delete) {
            $result = true;
        }
        return $result;
    }
    
        public function mantenimientoEquipo() {
        $delete = $this->db->query("update catalogo_equipos_computo set fecha_mantenimiento = curdate() where id={$this->getId()}");
        $result = false;
        if ($delete) {
            $result = true;
        }
        return $result;
    }
    
        public function getAll($where = null) {
        $result = array();
        $sql = "SELECT e.*, d.nombre as departamento, u.id as usuarioId, concat(u.nombres,' ', u.apellidos) as usuario FROM catalogo_equipos_computo as e "
                . "left join catalogo_usuarios u on u.id = e.usuario_id "
                . "left join catalogo_departamentos d on u.departamento_id = d.id ";
                if($where != null){
              $sql .= $where;
        }
      else{
         $sql .= " order by e.folio asc";
      }      
        $equipos = $this->db->query($sql);
        while ($e = $equipos->fetch_object()) {
            array_push($result, $e);
        }
        return $result;
    }
    
    public function getEquipoByTipoUsuario($equipo, $usuario){
        $where = "where e.tipo_equipo= {$equipo}";
        
        if($usuario != null){
           $where.= " and e.usuario_id = {$usuario}";
        }
        return $this->getAll($where);
    }
  
        public function getEquipoById($equipo){
        $where = "where e.id= {$equipo}";
        return $this->getAll($where);
    }
    
    public function getEquiposMantenimiento(){
        $where = " where e.fecha_mantenimiento <= DATE_ADD(curdate(), INTERVAL -5 month) OR e.fecha_mantenimiento is null order by e.id desc";
             return $this->getAll($where);
    }
    
       public function ultimoEquipoTipoEquipo(){
       $sql = "select e.* from catalogo_equipos_computo e where e.tipo_equipo={$this->getTipoEquipo()} order by e.id desc limit 1";
        $query = $this->db->query($sql);
        return $query->fetch_object();
  }

}
