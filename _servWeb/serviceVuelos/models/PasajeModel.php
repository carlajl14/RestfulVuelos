<?php

class PasajeModel extends Basedatos {

    private $table;
    private $conexion;

    public function __construct() {
        $this->table = "pasaje";
        $this->conexion = $this->getConexion();
    }

    /**
     * Obtener los pasajes de un vuelo
     * 
     * @param type $id
     * @return string
     */
    public function getPasaje($id) {
        try {
            $sql = 'select p.idpasaje, p.pasajerocod, pa.nombre, pa.pais, p.numasiento, p.clase, p.pvp from pasaje p join pasajero pa on(p.pasajerocod = pa.pasajerocod) where p.identificador = ?';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $id);
            $sentencia->execute();
            $pasaje = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            if ($pasaje) {
                return $pasaje;
            }
            return 'Vuelo incorrecto';
        } catch (PDOException $e) {
            return 'Error al devolver los pasajes.<br>' . $e->getMessage();
        }
    }

    /**
     * Eliminar un pasaje
     * 
     * @param type $id
     * @return type
     */
    public function deletePasaje($id) {
        try {
            $sql = 'delete from pasaje where idpasaje=?';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $id);
            $delete = $sentencia->execute();
            if ($sentencia->rowCount() == 0) {
                return "Pasaje NO Borrado, no se localiza: " . $id;
            } else {
                return "Pasaje Borrado: " . $id;
            }
        } catch (PDOException $e) {
            return "ERROR AL BORRAR.<br>" . $e->getMessage();
        }
    }

    /**
     * Comprobar si existe el asiento
     * 
     * @param type $asiento
     * @return boolean
     */
    public function comprobarAsiento($asiento, $vuelo) {
        try {
            $sql = 'SELECT COUNT(*) FROM pasaje WHERE identificador=? and numasiento = ?';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(2, $asiento);
            $sentencia->bindParam(1, $vuelo);
            $sentencia->execute();
            $num = $sentencia->fetch(PDO::FETCH_ASSOC);

            if ($num['COUNT(*)'] > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return 'Asiento erroneo';
        }
    }

    /**
     * Comprobar si hay un pasajero y un vuelo pasado por parámetro
     * 
     * @param type $pasajero
     * @param type $vuelo
     * @return boolean
     */
    public function comprobarPasajeroVuelo($pasajero, $vuelo) {
        try {
            $sql = 'SELECT COUNT(*) FROM pasaje WHERE pasajerocod = ? AND identificador = ?';
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $pasajero);
            $sentencia->bindParam(2, $vuelo);
            $sentencia->execute();
            $num = $sentencia->fetch(PDO::FETCH_ASSOC);

            if ($num['COUNT(*)'] > 0) {
                return true;
            } else {
                return false;
            }
            
        } catch (PDOException $e) {
            return 'Pasaje erroneo';
        }
    }

    /**
     * Insertar un pasajero en la tabla pasaje
     * 
     * @param type $post
     * @return type
     */
    public function insertPasaje($post) {
        try {
            $vuelopasajero = $this->comprobarPasajeroVuelo($post['pasajerocod'], $post['identificador']);
            $asiento = $this->comprobarAsiento($post['numasiento'], $post['identificador']);
            
            if ($vuelopasajero == false && $asiento == false) {
                $sql = 'INSERT INTO `pasaje`(`pasajerocod`, `identificador`, `numasiento`, `clase`, `pvp`) VALUES (?,?,?,?,?)';
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $post['pasajerocod']);
                $sentencia->bindParam(2, $post['identificador']);
                $sentencia->bindParam(3, $post['numasiento']);
                $sentencia->bindParam(4, $post['clase']);
                $sentencia->bindParam(5, $post['pvp']);
                $insert = $sentencia->execute();

                return "Registro insertado: " . $post['pasajerocod'];
            }
            
            if ($vuelopasajero == true || $asiento == true) {
                return "Asiento o pasaje erroneo.";
            }
        } catch (PDOException $e) {
            return "ERROR AL INSERTAR.<br>" . $e->getMessage();
        }
    }
    
    /**
     * Modificar un pasaje
     * 
     * @param type $post
     * @return string
     */
    public function updatePasaje($post) {
        try {
            //$vuelopasajero = $this->comprobarPasajeroVuelo($post['pasajerocod'], $post['identificador']);
            $asiento = $this->comprobarAsiento($post['numasiento'], $post['identificador']);
            
            if ($asiento == false) {
                $sql = 'UPDATE pasaje SET pasajerocod = ?, identificador = ?, numasiento = ?, clase = ?, pvp = ? WHERE idpasaje = ?;';
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $post['pasajerocod']);
                $sentencia->bindParam(2, $post['identificador']);
                $sentencia->bindParam(3, $post['numasiento']);
                $sentencia->bindParam(4, $post['clase']);
                $sentencia->bindParam(5, $post['pvp']);
                $sentencia->bindParam(6, $_GET['idpasaje']);
                $update = $sentencia->execute();
                
                return "Registro modificado: " . $post['pasajerocod'];
            } 
            
            if ($asiento == true){
                return "Asiento erroneo.";
            }

        } catch (PDOException $e) {
            return "ERROR AL MODIFICAR.<br>" . $e->getMessage();
        }
    }
}