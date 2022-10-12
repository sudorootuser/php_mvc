<?php
// Se incluye las constantes globales
require_once "./config/APP.php";
// Se incluye los controladores de las vistas
require_once "./controladores/vistasControlador.php";

// Se instancia una clase en plantilla
$plantilla = new vistasControlador();
$plantilla->obtener_plantilla_controlador();
