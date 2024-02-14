<?php

require_once ('db/db.php');
require_once ('models/VueloModel.php');
$vuelo = new VueloModel();
@header("Content-type: application/json");
// Consultar GET
// devuelve o 1 o todos, dependiendo si recibe o no parámetro
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $res = $vuelo->getOneVuelo($_GET['id']);
        echo json_encode($res);
        exit();
    } else {
        $res = $vuelo->allVuelos();
        echo json_encode($res);
        echo json_encode($ide);
        exit();
    }
}
// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");