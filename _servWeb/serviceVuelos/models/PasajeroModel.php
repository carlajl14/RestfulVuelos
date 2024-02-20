<?php

class PasajeroModel extends Basedatos {
    
    private $table;
    private $conexion;

    public function __construct() {
        $this->table = "pasajero";
        $this->conexion = $this->getConexion();
    }
    
    /**
     * Obtener información de todos los pasajeros
     * 
     * @return string
     */
    public function allPasajeros() {
        try {
            $sql = 'select * from pasajero';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            $pasajeros = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $pasajeros;
        } catch (PDOException $e) {
            return 'Error al devolver los pasajeros.<br>'. $e->getMessage();
        }
    }
}