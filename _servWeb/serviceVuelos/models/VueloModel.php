<?php

class VueloModel extends Basedatos {
    
    private $table;
    private $conexion;

    public function __construct() {
        $this->table = "vuelo";
        $this->conexion = $this->getConexion();
    }
    
    /**
     * Obtener información de todos los vuelos
     * 
     * @return string
     */
    public function allVuelos() {
        try {
            $sql = 'select v.identificador, v.aeropuertoorigen, a.nombre, a.pais, vu.aeropuertodestino, a.nombre, a.pais, v.tipovuelo, count(p.identificador) AS "Número de pasajeros" from vuelo v join aeropuerto a on (v.aeropuertoorigen = a.codaeropuerto) join vuelo vu on(vu.aeropuertodestino = a.codaeropuerto) left join pasaje p on (p.identificador = v.identificador) GROUP BY v.identificador';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            $vuelos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $vuelos;
        } catch (PDOException $e) {
            return 'Error al devolver los vuelos.<br>'. $e->getMessage();
        }
    }
    
    /**
     * Obtener información de un vuelo pasado por parámetro
     * 
     * @param type $id
     * @return string
     */
    public function getOneVuelo($id) {
        try {
            $sql = 'select v.identificador, v.aeropuertoorigen, a.nombre, a.pais, vu.aeropuertodestino, a.nombre, a.pais, v.tipovuelo, count(p.identificador) AS "Número de pasajeros" from vuelo v join aeropuerto a on (v.aeropuertoorigen = a.codaeropuerto) join vuelo vu on(vu.aeropuertodestino = a.codaeropuerto) left join pasaje p on (p.identificador = v.identificador) WHERE v.identificador = ?';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $id);
            $sentencia->execute();
            $vuelo = $sentencia->fetch(PDO::FETCH_ASSOC);
            
            //Comprobar si devuelve el vuelo
            if ($vuelo) {
                return $vuelo;
            } 
            return 'Vuelo incorrecto';
        } catch (PDOException $e) {
            return 'Error al devolver el vuelo.<br>'. $e->getMessage();
        }
    }
    
    /*public function getIdentificador() {
        try {
            $sql = 'select identificador from vuelos';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            $identificador = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $identificador;
        } catch (PDOException $e) {
            return 'Error al devolver los identificadores de los vuelos.<br>'. $e->getMessage();
        }
    }*/
}