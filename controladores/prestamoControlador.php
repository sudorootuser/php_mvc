<?php

if ($peticionAjax) {
    require_once "../modelos/prestamoModelo.php";
} else {
    require_once "./modelos/prestamoModelo.php";
}

class prestamoControlador extends prestamoModelo
{
    /*----------- Controlador buscar cliente ------------*/
    public function buscar_cliente_prestamo_controlador()
    {
        $cliente = mainModel::limpiar_cadena($_POST['buscar_cliente']);

        /* Comprobar texto */
        if ($cliente == "") {
            return '<div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            Debes introducir el DNI, Nombre, Apellido ,Télefono 
                        </p>
                  </div>';
            exit();
        }

        /* Seleccionando clientes en la db */
        $datos_cliente = mainModel::ejecutar_cosulta_simple("SELECT * FROM cliente WHERE cliente_dni LIKE '%$cliente%' OR cliente_nombre LIKE '%$cliente%' OR cliente_apellido LIKE '%$cliente%' OR cliente_telefono LIKE '%$cliente%' ORDER BY cliente_nombre ASC");

        if ($datos_cliente->rowCount() >= 1) {
            $tabla = ' <div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
            foreach ($datos_cliente as $rows) {
                $tabla .= '<tr class="text-center">
                                <td>' . $rows['cliente_nombre'] . ' ' . $rows['cliente_apellido'] . ' ' . $rows['cliente_dni'] . '</td>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="agregar_cliente(' . $rows['cliente_id'] . ')"><i class="fas fa-user-plus"></i></button>
                                </td>
                          </tr>';
            }
            $tabla .= '</tbody></table></div>';
            return $tabla;
        } else {
            return '<div class="alert alert-warning" role="alert">
            <p class="text-center mb-0">
                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                No hemos encontrado ningún cliente en el sistema que coincida con <strong>“' . $cliente . '”</strong>
            </p>
        </div>';
            exit();
        }
    } /* Fin controlador*/

    /*----------- Controlador agregar cliente ------------*/
    public function agregar_cliente_prestamo_controlador()
    {
        /* Recupera el Id */
        $id = mainModel::limpiar_cadena($_POST['id_agregar_cliente']);

        /* Comrpobando el cliente en la db */
        $check_cliente = mainModel::ejecutar_cosulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");

        if ($check_cliente->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido encontrar el cliente en la base de datos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_cliente->fetch();
        }

        /* Iniciando la sesion */
        session_start(['name' => 'SPM']);

        if (empty($_SESSION['datos_cliente'])) {
            $_SESSION['datos_cliente'] = [
                "ID" => $campos['cliente_id'],
                "DNI" => $campos['cliente_dni'],
                "Nombre" => $campos['cliente_nombre'],
                "Apellido" => $campos['cliente_apellido']
            ];
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente agregado",
                "Texto" => "El cliente se agrego para realizar un prestamo o servacion",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido agregar el cliente al prestamo",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
        }
    } /* Fin controlador*/

    /*----------- Controlador iniciar sesion ------------*/
    public function eliminar_cliente_prestamo_controlador()
    {
        /* Iniciando la sesion */
        session_start(['name' => 'SPM']);

        unset($_SESSION['datos_cliente']);

        if (empty($_SESSION['datos_cliente'])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente removido",
                "Texto" => "Los datos del cliente se han removido con exito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido remover los datos del cliente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    } /* Fin controlador*/

    /*----------- Controlador buscar item ------------*/
    public function buscar_item_prestamo_controlador()
    {
        $item = mainModel::limpiar_cadena($_POST['buscar_item']);

        /*  Comprobar texto */
        if ($item == "") {
            return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debes introducir el código o nombre del Item 
                    </p>
               </div>';
            exit();
        }

        /* Seleccionando items en la db */
        $datos_item = mainModel::ejecutar_cosulta_simple("SELECT * FROM item WHERE (item_codigo LIKE '%$item%' OR item_nombre LIKE '%$item%') AND (item_estado='Habilitado') ORDER BY item_nombre ASC");

        if ($datos_item->rowCount() >= 1) {
            $tabla = ' <div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
            foreach ($datos_item as $rows) {
                $tabla .= '<tr class="text-center">
                                <td>' . $rows['item_codigo'] . '-' . $rows['item_nombre'] . '</td>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="modal_agregar_item(' . $rows['item_id'] . ')"><i class="fas fa-box-open"></i></button>
                                </td>
                          </tr>';
            }
            $tabla .= '</tbody></table></div>';
            return $tabla;
        } else {
            return '<div class="alert alert-warning" role="alert">
            <p class="text-center mb-0">
                <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                No hemos encontrado ningún cliente en el sistema que coincida con <strong>“' . $item . '”</strong>
            </p>
        </div>';
            exit();
        }
    }/* Fin controlador*/

    /*----------- Controlador agregar item ------------*/
    public function agregar_item_prestamo_controlador()
    {
        /* Recupera el Id */
        $id = mainModel::limpiar_cadena($_POST['id_agregar_item']);

        /* Comprobando el cliente en la db */
        $check_item = mainModel::ejecutar_cosulta_simple("SELECT * FROM item WHERE item_id='$id' AND item_estado='Habilitado'");

        if ($check_item->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido seleccionar el item,por favor intente nuevamente",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_item->fetch();
        }


        /* Recuperando detalles del prestamo */
        $formato = mainModel::limpiar_cadena($_POST['detalle_formato']);
        $cantidad = mainModel::limpiar_cadena($_POST['detalle_cantidad']);
        $tiempo = mainModel::limpiar_cadena($_POST['detalle_tiempo']);
        $costo = mainModel::limpiar_cadena($_POST['detalle_costo_tiempo']);



        /*== Comprobar campos vacios ==*/

        if ($cantidad == "" || $tiempo == "" || $costo == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "No has llenado todos los campos que son obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Verificando integridad de los datos ==*/
        if (mainModel::verificar_datos("[0-9]{1,7}", $cantidad)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "La cantidad no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[0-9]{1,7}", $tiempo)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El tiempo no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[0-9.]{1,15}", $costo)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El costo no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($formato != "Horas" && $formato != "Dias" && $formato != "Evento" && $formato != "Mes") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El formato no es valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /* Iniciando la sesion */

        session_start(['name' => 'SPM']);

        if (empty($_SESSION['datos_item'][$id])) {
            $costo = number_format($costo, 2, '.', '');

            $_SESSION['datos_item'][$id] = [
                "ID" => $campos['item_id'],
                "Codigo" => $campos['item_codigo'],
                "Nombre" => $campos['item_nombre'],
                "Detalle" => $campos['item_detalle'],
                "Formato" => $formato,
                "Cantidad" => $cantidad,
                "Tiempo" => $tiempo,
                "Costo" => $costo
            ];

            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item agregado",
                "Texto" => "El item ha sido agregado para realizar un prestamo",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El item que intenta agregar ya se encuentra seleccionado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    }/* Fin controlador*/

    /*----------- Controlador eliminasr item ------------*/
    public function eliminar_item_prestamo_controlador()
    {
        /* Recuperar el Id item*/
        $id = mainModel::limpiar_cadena($_POST['id_eliminar_item']);

        /* Iniciando la sesion */
        session_start(['name' => 'SPM']);

        unset($_SESSION['datos_item'][$id]);

        if (empty($_SESSION['datos_item'][$id])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item removido",
                "Texto" => "Los datos del item se han removido con exito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido remover los datos del item",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    } /* Fin controlador*/


    /*-------------- Controlador datos prestamo --------------*/
    public  function datos_prestamo_controlador($tipo, $id)
    {
        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::encrypt_decrypt('decrypt', $id);

        $id = mainModel::limpiar_cadena($id);

        return prestamoModelo::datos_prestamo_modelo($tipo, $id);
    } /*---- Fin del controlador ----*/

    /*-------------- Controlador agregar prestamo --------------*/
    public function agregar_prestamo_controlador()
    {

        /* --Iniciando la sesion --*/
        session_start(['name' => 'SPM']);

        /*-- Comprobando items --*/
        if ($_SESSION['prestamo_item'] == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No has seleccionado ningún item para realizar el prestamo",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- Comprobando cliente --*/
        if ($_SESSION['datos_cliente'] == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No has seleccionado ningún cliente para realizar el prestamo",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Recibiendo datos del formulario ==*/
        $fecha_inicio = mainModel::limpiar_cadena($_POST['prestamo_fecha_inicio_reg']);
        $hora_inicio = mainModel::limpiar_cadena($_POST['prestamo_hora_inicio_reg']);
        $fecha_final = mainModel::limpiar_cadena($_POST['prestamo_fecha_final_reg']);
        $hora_final = mainModel::limpiar_cadena($_POST['prestamo_hora_final_reg']);
        $estado = mainModel::limpiar_cadena($_POST['prestamo_estado_reg']);
        $total_pagado = mainModel::limpiar_cadena($_POST['prestamo_pagado_reg']);
        $observacion = mainModel::limpiar_cadena($_POST['prestamo_observacion_reg']);

        /*== Verificando los campos vaciós ==*/
        if ($fecha_inicio == "" || $hora_inicio == "" || $fecha_final == "" || $hora_final == "" || $total_pagado == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Los campos (Fecha de prestamo, Hora de prestamo, Fecha de entrega, Hora de entrega, Total depositado ) son obligatorios!",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Verificando integridad de los datos ==*/
        if (mainModel::verificar_fecha($fecha_inicio)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La fecha de inicio no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("^((([0]?[1-9]|1[0-2])(:|\.)[0-5][0-9]((:|\.)[0-5][0-9])?( )?(AM|am|aM|Am|PM|pm|pM|Pm))|(([0]?[0-9]|1[0-9]|2[0-3])(:|\.)[0-5][0-9]((:|\.)[0-5][0-9])?))$", $hora_inicio)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La hora de inicio no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_fecha($fecha_final)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La fecha de entrega no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])", $hora_final)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La hora de entrega no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[0-9.]{1,10}", $total_pagado)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El total depositado no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($observacion != "") {
            if (mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}", $observacion)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La observación no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        if ($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El estado no coincide con el formato solicitado, el estado es obligatorio!",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*== Comprobando las fechas ==*/
        if (strtotime($fecha_final) < strtotime($fecha_inicio)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La fecha de entrega no puede ser menor que la fecha de inicio del prestamo",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Formateando totales, fechas y horas ==*/
        $total_prestamo = number_format($_SESSION['prestamo_total'], 2, '.', '');

        $total_pagado = number_format($total_pagado, 2, '.', '');

        $fecha_inicio = date("y-m-d", strtotime($fecha_inicio));
        $fecha_final = date("y-m-d", strtotime($fecha_final));

        $hora_final = date("h:i a", strtotime($hora_final));
        $hora_final = date("h:i a", strtotime($hora_final));

        /*== Generando código de prestamo ==*/
        $correlativo = mainModel::ejecutar_cosulta_simple("SELECT prestamo_id FROM prestamo");

        $correlativo = ($correlativo->rowCount() + 1);
        $codigo = mainModel::generar_codigo_aleatorio("CP", 7, $correlativo);

        $datos_prestamo_reg = [
            "Codigo" => $codigo,
            "FechaInicio" => $fecha_inicio,
            "HoraInicio" => $hora_inicio,
            "FechaFinal" => $fecha_final,
            "HoraFinal" => $hora_final,
            "Cantidad" => $_SESSION['prestamo_item'],
            "Total" => $total_prestamo,
            "Pagado" => $total_pagado,
            "Estado" => $estado,
            "Observacion" => $observacion,
            "Usuario" => $_SESSION['id_spm'],
            "Cliente" => $_SESSION['datos_cliente']['ID']
        ];

        /*== Agregar prestamo ==*/
        $agregar_prestamo = prestamoModelo::agregar_prestamo_modelo($datos_prestamo_reg);

        if ($agregar_prestamo->rowCount() != 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido registrar el prestamo (Error: 001), por favor intente nuevamente",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Agregar pago ==*/
        if ($total_pagado > 0) {
            $datos_pago_reg = [
                "Total" => $total_pagado,
                "Fecha" => $fecha_inicio,
                "Codigo" => $codigo
            ];
            $agregar_pago = prestamoModelo::agregar_pago_modelo($datos_pago_reg);

            if ($agregar_pago->rowCount() != 1) {
                prestamoModelo::eliminar_prestamo_modelo($codigo, "Prestamo");
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar el prestamo (Error: 002), por favor intente nuevamente",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        /*== Agregar detalle ==*/
        $errores_detalle = 0;

        foreach ($_SESSION['datos_item'] as $items) {
            $costo = number_format($items['Costo'], 2, '.', '');

            $descripcion = $items['Codigo'] . " " . $items['Nombre'];

            $datos_detalle_reg = [
                "Cantidad" => $items['Cantidad'],
                "Formato" => $items['Formato'],
                "Tiempo" => $items['Tiempo'],
                "Costo" => $costo,
                "Descripcion" => $descripcion,
                "Prestamo" => $codigo,
                "Item" => $items['ID']
            ];

            $agregar_detalle = prestamoModelo::agregar_detalle_modelo($datos_detalle_reg);

            if ($agregar_detalle->rowCount() != 1) {
                $errores_detalle = 1;
                break;
            }
        }
        if ($errores_detalle == 0) {
            unset($_SESSION['datos_cliente']);
            unset($_SESSION['datos_item']);
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Prestamo registrado",
                "Texto" => "Los datos del prestamo han sido registrados en el sistema",
                "Tipo" => "success"
            ];
        } else {
            prestamoModelo::eliminar_prestamo_modelo($codigo, "Detalle");
            prestamoModelo::eliminar_prestamo_modelo($codigo, "Pago");
            prestamoModelo::eliminar_prestamo_modelo($codigo, "Prestamo");
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido registrar el prestamo (Error: 003), por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    } /*---- Fin del controlador ----*/

    /**------------------ Controlador paginar prestamo -----------**/
    public function paginador_prestamos_controlador($pagina, $registros, $privilegio, $url, $tipo, $fecha_inicio, $fecha_final)
    {

        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);

        $url = mainModel::limpiar_cadena($url);
        $url = SERVERURL . $url . "/";

        $tipo = mainModel::limpiar_cadena($tipo);
        $fecha_inicio = mainModel::limpiar_cadena($fecha_inicio);
        $fecha_final = mainModel::limpiar_cadena($fecha_final);
        $tabla = "";

        // Inicio de operador terneario / 20 * 10 - 1
        $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        if ($tipo == 'Busqueda') {
            if (mainModel::verificar_fecha($fecha_inicio) || mainModel::verificar_fecha($fecha_final)) {
                return '
                <div class="alert alert-danger text-center" role="alert">
				<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
				<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
				<p class="mb-0">Lo sentimos, no podemos realizar la busqueda ya que ha ingresado una fecha incorrecta.</p>
			</div>
                ';
                exit();
            }
        }

        $campos = "prestamo.prestamo_id,prestamo.prestamo_codigo,prestamo.prestamo_fecha_inicio,prestamo.prestamo_fecha_final,prestamo.prestamo_total,prestamo.prestamo_pagado,prestamo.prestamo_estado,prestamo.usuario_id,prestamo.cliente_id,cliente.cliente_nombre,cliente.cliente_apellido";
        // Busqueda
        if ($tipo == "Busqueda" && $fecha_inicio != "" && $fecha_final != "") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE (prestamo.prestamo_fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_final') ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio,$registros";
        } else {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM prestamo INNER JOIN cliente ON prestamo.cliente_id=cliente.cliente_id WHERE prestamo.prestamo_estado='$tipo' ORDER BY prestamo.prestamo_fecha_inicio DESC LIMIT $inicio,$registros";
        }

        $conexion = mainModel::conectar();

        $datos = $conexion->query($consulta);

        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / $registros);

        // Inicio del diseño de la tabla

        $tabla .= '<div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>CLIENTE</th>
                            <th>FECHA DE PRÉSTAMO</th>
                            <th>FECHA DE ENTREGA</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>FACTURA</th>';
        if ($privilegio == 1 || $privilegio == 2) {
            $tabla .= '<th>ACTUALIZAR</th>';
        }
        if ($privilegio == 1) {
            $tabla .= '<th>ELIMINAR</th>';
        }
        $tabla .= '</tr>
                    </thead>
                    <tbody>';

        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            $reg_inicio = $contador + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                    <tr class="text-center">
                    <td>' . $contador . '</td>
                    <td>' . $rows['cliente_nombre'] . '' . $rows['cliente_apellido'] . ' </td>
                    <td>' . date("d-m-y", strtotime($rows['prestamo_fecha_inicio'])) . '</td>
                    <td>' . date("d-m-y", strtotime($rows['prestamo_fecha_final'])) . '</td>
                    <td>' . $rows['prestamo_estado'] . '</td>';

                if ($rows['prestamo_pagado'] < $rows['prestamo_total']) {
                    $tabla .= '<td>Pendiente: <span class="badge badge-danger">' . MODENA . number_format(($rows['prestamo_total'] - $rows['prestamo_pagado']), 2, '.', '') . '</span></td>';
                } else {
                    $tabla .= '<td><span class="badge badge-light">Cancelado</span></td>';
                }

                $tabla .= '<td>
                        <a href="' . SERVERURL . 'facturas/invoice.php?id=' . mainModel::encrypt_decrypt('encrypt', $rows['prestamo_id']) . '" class="btn btn-info" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>';
                if ($privilegio == 1 || $privilegio == 2) {

                    if ($rows['prestamo_estado'] == "Finalizado" && $rows['prestamo_pagado'] == $rows['prestamo_total']) {
                        $tabla .= '<td>
                        <a href="' . SERVERURL . 'reservation-update/' . mainModel::encrypt_decrypt('encrypt', $rows['prestamo_codigo']) . '/" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </td>';
                    } else {
                        $tabla .= '<td>
                        <a href="' . SERVERURL . 'reservation-update/' . mainModel::encrypt_decrypt('encrypt', $rows['prestamo_id']) . '/" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </td>';
                    }
                }

                if ($privilegio == 1) {
                    $tabla .= '<td>
                        <form class="FormularioAjax" action="' . SERVERURL . 'ajax/prestamoAjax.php" method="POST" data-form="delete" autocomplete="off">
                            <input type="hidden" name="prestamo_codigo_del" value="' . mainModel::encrypt_decrypt('encrypt', $rows['prestamo_codigo']) . '">
                            <button type="submit" class="btn btn-warning">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>';
                }
                $tabla .= '</tr>';
                $contador++;
            }
            $reg_final = $contador - 1;
        } else {
            if ($total >= 1) {
                $tabla .= '<tr class="text-center"><td colspan="9">
                      <a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga clic acá para recargar el listado</a>  
                    </td></tr>';
            } else {
                $tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
            }
        }
        $tabla .= '</tbody></table></div>';

        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<p class="text-right">Mostrando prestamo ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';

            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }

        return $tabla;
    }/* Fin del Controlador */

    /**------------------ Controlador eliminar prestamo -----------**/
    public function eliminar_prestamo_controlador()
    {
        /*-- Recibiendo codigo de prestamo --*/
        $codigo = mainModel::encrypt_decrypt('decrypt', $_POST['prestamo_codigo_del']);


        $codigo = mainModel::limpiar_cadena($codigo);
        /*-- Comprobando prestamo en la DB --*/
        $check_prestamo = mainModel::ejecutar_cosulta_simple("SELECT prestamo_codigo FROM prestamo WHERE prestamo_codigo='$codigo'");

        if ($check_prestamo->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El prestamo que intenta eliminar no existe en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobar los privilegios
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] != 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No tienes los permisos necesarios para realizar esta operación",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- Comprobando y eliminar pagos --*/
        $check_pagos = mainModel::ejecutar_cosulta_simple("SELECT prestamo_codigo FROM pago WHERE prestamo_codigo='$codigo'");


        $check_pagos = $check_pagos->rowCount();
        if ($check_pagos > 0) {
            $eliminar_pagos = prestamoModelo::eliminar_prestamo_modelo($codigo, "Pago");

            if ($eliminar_pagos->rowCount() != $check_pagos) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido eliminar el prestamo, por favor intente nuevamente 1",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        /*-- Comprobando y eliminar detalles --*/
        $check_detalles = mainModel::ejecutar_cosulta_simple("SELECT prestamo_codigo FROM detalle WHERE prestamo_codigo='$codigo'");

        $check_detalles = $check_detalles->rowCount();
        if ($check_detalles > 0) {
            $eliminar_detalles = prestamoModelo::eliminar_prestamo_modelo($codigo, "Detalle");

            if ($eliminar_detalles->rowCount() != $check_detalles) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido eliminar el prestamo, por favor intente nuevamente 2",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        $eliminar_prestamo = prestamoModelo::eliminar_prestamo_modelo($codigo, "Prestamo");

        if ($eliminar_prestamo->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Prestamo eliminado",
                "Texto" => "El prestamo ha sido eliminado del sistema",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido eliminar el prestamo, por favor intente nuevamente 3",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin del Controlador */

    /**------------------ Controlador agregar pagos -----------**/
    public function agregar_pago_controlador()
    {
        /*-- Recibiendo datos--*/
        $codigo = mainModel::encrypt_decrypt('decrypt', $_POST['pago_codigo_reg']);
        $codigo = mainModel::limpiar_cadena($codigo);

        $monto = mainModel::limpiar_cadena($_POST['pago_monto_reg']);
        $monto = number_format($monto, 2, '.', '');


        /*-- Comprobando que el pago sea mayor a 0 --*/
        if ($monto <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El pago debe de ser mayor a 0",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- Comprobando presamo en la DB --*/
        $datos_prestamo = mainModel::ejecutar_cosulta_simple("SELECT * FROM prestamo WHERE prestamo_codigo='$codigo'");

        if ($datos_prestamo->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El prestamo al cual intenta agregar el pago no existe en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $datos_prestamo = $datos_prestamo->fetch();
        }


        /*-- Comprobando que el monto no sea mayor a la que falta por pagar --*/
        $pendiente = number_format(($datos_prestamo['prestamo_total'] - $datos_prestamo['prestamo_pagado']), 2, '.', '');


        if ($monto > $pendiente) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El monto que acaba de ingresar supera el saldo pendiente que tiene este prestamo",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- Comprobar privilegios --*/
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] < 1 || $_SESSION['privilegio_spm'] > 2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No tienes los permisos necesarios para realizar esta operación",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-- Calculando total a pagar y fecha --*/
        $total_pagado = number_format(($monto + $datos_prestamo['prestamo_pagado']), 2, '.', '');

        $fecha = date("Y-m-d");

        $datos_pago_reg = [
            "Total" => $monto,
            "Fecha" => $fecha,
            "Codigo" => $codigo
        ];

        $agregar_pago = prestamoModelo::agregar_pago_modelo($datos_pago_reg);

        if ($agregar_pago->rowCount() == 1) {

            $datos_prestamo_up = [
                "Tipo" => "Pago",
                "Monto" => $total_pagado,
                "Codigo" => $codigo
            ];

            if (prestamoModelo::actualizar_prestamo_modelo($datos_prestamo_up)) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Pago realizado",
                    "Texto" => "El pago de " . MODENA . $monto . " se ha realizado con exito",
                    "Tipo" => "success"
                ];
            } else {
                prestamoModelo::actualizar_prestamo_modelo($codigo, "Pago");
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar el pago",
                    "Tipo" => "error"
                ];
            }
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido registrar el pago",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin del Controlador */

    /**------------------ Controlador agregar pagos -----------**/
    public function actualizar_prestamo_controlador()
    {
        /*-- Recibiendo codigo --*/
        $codigo = mainModel::encrypt_decrypt('decrypt', $_POST['prestamo_codigo_up']);
        $codigo = mainModel::limpiar_cadena($codigo);

        /*-- Comprobando presamo en la DB --*/
        $datos_prestamo = mainModel::ejecutar_cosulta_simple("SELECT prestamo_codigo FROM prestamo WHERE prestamo_codigo='$codigo'");

        if ($datos_prestamo->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El prestamo que intenta actualizar no existe en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- recibir datos --*/
        $estado = mainModel::limpiar_cadena($_POST['prestamo_estado_up']);
        $observacion = mainModel::limpiar_cadena($_POST['prestamo_observacion_up']);

        /*-- Verificar la integridad de los datos --*/
        if ($observacion != "") {
            if (mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}", $observacion)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La observación no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        if ($estado != "Reservacion" && $estado != "Prestamo" && $estado != "Finalizado") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El estado no coincide con el formato solicitado, el estado es obligatorio!",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-- Comprobar privilegios --*/
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] < 1 || $_SESSION['privilegio_spm'] > 2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No tienes los permisos necesarios para realizar esta operación",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $datos_prestamo_up = [
            "Tipo" => "Prestamo",
            "Estado" => $estado,
            "Observacion" => $observacion,
            "Codigo" => $codigo,
        ];
        
        if (prestamoModelo::actualizar_prestamo_modelo($datos_prestamo_up)) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Prestamo actualizado",
                "Texto" => "Los datos del prestamo se han actualizado con exito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido actualizar el prestamo",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin del Controlador */
}
