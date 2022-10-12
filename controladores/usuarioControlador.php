<?php

if ($peticionAjax) {
    require_once "../modelos/usuarioModelo.php";
} else {
    require_once "./modelos/usuarioModelo.php";
}

class usuarioControlador extends usuarioModelo
{

    /**------------------ Controlador agregar usuario ------------------**/
    public function agregar_usuario_controlador()
    {

        $dni = mainModel::limpiar_cadena($_POST['usuario_dni_reg']);
        $nombre = mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
        $apellido = mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
        $telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
        $direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);

        $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
        $email = mainModel::limpiar_cadena($_POST['usuario_email_reg']);
        $clave1 = mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
        $clave2 = mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);

        $privilegio = mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);

        /*== Comprobar campos vacios ==*/

        if ($dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $telefono == "" || $direccion == "" || $clave1 == "" || $clave2 == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "No has llenado todos los campos que son obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Comprobar campos vacios ==*/

        if (mainModel::verificar_datos("[0-9-]{10,20}", $dni)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El DNI no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{10,35}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El Nombre no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{10,35}", $apellido)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto"  => "El Apellido no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if ($telefono == "") {
            if (mainModel::verificar_datos("[0-9()+-]{8,20}", $telefono)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El Teléfono no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        if ($direccion == "") {

            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El Dirección no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9]{8,20}", $usuario)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El Nombre de Usuario no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" =>  "Las Claves no coinciden con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*==.Comprabando.DNI.==*/
        $check_dni = mainModel::ejecutar_cosulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");
        if ($check_dni->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El DNI ingresado ya se encuentra registrado en el sistema  ",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*==.Comprabando.USUARIO.==*/
        $check_user = mainModel::ejecutar_cosulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El NOMBRE DE USAURIO ingresado ya se encuentra registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*== Comprabando email ==*/
        if ($email != "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_email = mainModel::ejecutar_cosulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");
                if ($check_email->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrio un error inesperado",
                        "Texto" => "El EMAIL ingresado ya se encuentra registrado en el sistema",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Ha ingresado un correo no valido",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        /*== Comprabando claves ==*/
        if ($clave1 != $clave2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Las claves que acaba de ingresar no coinciden",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $clave = mainModel::encrypt_decrypt('encrypt', $clave1);
        }

        /*==  Comprobando privilegio==*/
        if ($privilegio < 1 || $privilegio > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un  error inesperado",
                "Texto" => "El privilegio seleccionado no es valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*== Registrar Usuario ==*/

        $datos_usuario_reg = [
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "Email" => $email,
            "Usuario" => $usuario,
            "Clave" => $clave,
            "Estado" => "Activo",
            "Privilegio" => $privilegio
        ];

        $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

        if ($agregar_usuario->rowCount() == 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Su usuario se ha registrado con éxito",
                "Texto" => "Los datos del usuario han sido registrados con éxito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un eror inesperado",
                "Texto" => "No hemos podido registrar el nuevo usuario ",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    } /* Fin del Controlador */

    /**------------------ Controlador paginar usuario ------------------**/
    public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
    {
        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);
        $id = mainModel::limpiar_cadena($id);

        $url = mainModel::limpiar_cadena($url);
        $url = SERVERURL . $url . "/";

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";

        // Inicio de operador terneario / 20 * 10 - 1
        $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        // Busqueda
        if (isset($busqueda) && $busqueda != "") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id!='$id' AND usuario_id!='1') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_telefono LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";
        } else {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id!='$id' AND usuario_id!='1' ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";
        }

        $conexion = mainModel::conectar();

        $datos = $conexion->query($consulta);

        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / $registros);

        $tabla .= '<div class="table-responsive">
            <table class="table table-dark table-sm">
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>#</th>
                        <th>DNI</th>
                        <th>NOMBRE</th>
                        <th>TELÉFONO</th>
                        <th>USUARIO</th>
                        <th>EMAIL</th>
                        <th>ACTUALIZAR</th>
                        <th>ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            $reg_inicio = $contador + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                <tr class="text-center">
                <td>' . $contador . '</td>
                <td>' . $rows['usuario_dni'] . '</td>
                <td>' . $rows['usuario_nombre'] . ' ' . $rows['usuario_apellido'] . '</td>
                <td>' . $rows['usuario_telefono'] . '</td>
                <td>' . $rows['usuario_usuario'] . '</td>
                <td>' . $rows['usuario_email'] . '</td>
                <td>
                    <a href="' . SERVERURL . 'user-update/' . mainModel::encrypt_decrypt('encrypt', $rows['usuario_id']) . '/" class="btn btn-success">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </td>
                <td>
                    <form class="FormularioAjax" action="' . SERVERURL . 'ajax/usuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
                        <input type="hidden" name="usuario_id_del" value="' . mainModel::encrypt_decrypt('encrypt', $rows['usuario_id']) . '">
                        <button type="submit" class="btn btn-warning">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>';
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
            $tabla .= '<p class="text-right">Mostrando usuario ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';

            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }

        return $tabla;
    }/* Fin del Controlador */

    /**------------------ Controlador eliminar usuario ------------------**/
    public function eliminar_usuario_controlador()
    {
        /* Recibiendo id del usuario */
        $id = mainModel::encrypt_decrypt('decrypt', $_POST['usuario_id_del']);
        $id = mainModel::limpiar_cadena($id);

        // Comprobando el usuario principal

        if ($id == 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No podemos eliminar el usuario principal",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando el usuario en DB
        $check_usuario = mainModel::ejecutar_cosulta_simple("SELECT usuario_id FROM usuario WHERE usuario_id= '$id'");

        if ($check_usuario->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El usuario que intenta eliminar no existe en el sistema!",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando los prestamos
        $check_usuario = mainModel::ejecutar_cosulta_simple("SELECT usuario_id FROM prestamo WHERE usuario_id= '$id' LIMIT 1");

        if ($check_usuario->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No podemos eliminar este usuario debido a que tiene prestamos asociados, recomendamos deshabilidar el usuario si ya no sera utilizado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando privilegios
        session_start(['name' => 'SPM']);
        if ($_SESSION['privilegio_spm'] != 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No tienes los permisos para realizar esta operacion",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $eliminar_usuario = usuarioModelo::eliminar_usuario_modelo($id);

        if ($eliminar_usuario->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El usuario ha sido eliminado del sistema exitosamente",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido eliminar el usuario, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin del Controlador */
    /**------------------ Controlador datos usuario ------------------**/
    public function datos_usuario_controlador($tipo, $id)
    {
        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::encrypt_decrypt('decrypt', $id);
        $id = mainModel::limpiar_cadena($id);

        return usuarioModelo::datos_usuario_modelo($tipo, $id);
    }/* Fin del Controlador */
    /**------------------ Controlador datos usuario ------------------**/
    public function actualizar_usuario_controlador()
    {
        // Recibiendo el id
        $id = mainModel::encrypt_decrypt('decrypt', $_POST['usuario_id_up']);
        $id = mainModel::limpiar_cadena($id);

        // echo  $id;
        // COndicionamos el usuario en la base de datos
        $check_user = mainModel::ejecutar_cosulta_simple("SELECT * FROM usuario WHERE usuario_id='$id'");
        if ($check_user->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos encontrado el usuario en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_user->fetch();
        }
        $dni = mainModel::limpiar_cadena($_POST['usuario_dni_up']);
        $nombre = mainModel::limpiar_cadena($_POST['usuario_nombre_up']);
        $apellido = mainModel::limpiar_cadena($_POST['usuario_apellido_up']);
        $telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_up']);
        $direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_up']);

        $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_up']);
        $email = mainModel::limpiar_cadena($_POST['usuario_email_up']);

        // Condicionamos
        if (isset($_POST['usuario_estado_up'])) {
            $estado = mainModel::limpiar_cadena($_POST['usuario_estado_up']);
        } else {
            $estado = $campos['usuario_estado'];
        }
        if (isset($_POST['usuario_privilegio_up'])) {
            $privilegio = mainModel::limpiar_cadena($_POST['usuario_privilegio_up']);
        } else {
            $privilegio = $campos['usuario_privilegio'];
        }

        $admin_usuario = mainModel::limpiar_cadena($_POST['usuario_admin']);

        $admin_clave = mainModel::limpiar_cadena($_POST['clave_admin']);

        $tipo_cuenta = mainModel::limpiar_cadena($_POST['tipo_cuenta']);

        /*== Comprobar campos vacios ==*/
        if ($dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $admin_usuario == "" || $admin_clave == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No has llenado todos los campos que son obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*== Verificando integridad de los datos ==*/

        if (mainModel::verificar_datos("[0-9-]{10,20}", $dni)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El DNI no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        // Apellido
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{10,35}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El Nombre no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{10,35}", $apellido)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto"  => "El Apellido no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if ($telefono == "") {
            if (mainModel::verificar_datos("[0-9()+-]{8,20}", $telefono)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El Teléfono no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        if ($direccion == "") {

            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error inesperado",
                    "Texto" => "El Dirección no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9]{8,20}", $usuario)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error inesperado",
                "Texto" => "El Nombre de Usuario no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $admin_usuario)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" =>  "TU NOMBRE DE USUARIO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" =>  "TU CLAVE no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $admin_clave = mainModel::encrypt_decrypt('encrypt', $admin_clave);

        /*==  Comprobando privilegio==*/
        if ($privilegio < 1 || $privilegio > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un  error inesperado",
                "Texto" => "El privilegio seleccionado no es valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando estado
        if ($estado != "Activo" && $estado != "Deshabilitada") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un  error inesperado",
                "Texto" => "El estado del acuenta no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*== Comprobando DNI ==*/
        if ($dni != $campos['usuario_dni']) {
            $check_dni = mainModel::ejecutar_cosulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni='$dni'");
            if ($check_dni->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El DNI ingresado ya se encuentra registrado en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        /*== Comprobando Usuario ==*/
        if ($usuario != $campos['usuario_usuario']) {
            $check_user = mainModel::ejecutar_cosulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
            if ($check_user->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error- inesperado",
                    "Texto" => "El NOMBRE DE USUARIO ya se encuentra registrado en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        // Comropobando email
        if ($email != $campos['usuario_email'] && $email != "") {

            $check_email = mainModel::ejecutar_cosulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
            if ($check_email->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error- inesperado",
                    "Texto" => "El nuevo email ya se encuentra registrado en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error- inesperado",
                    "Texto" => "Ha ingresado un correo no valido",
                    "Tipo" => "error"
                ];
            }
        }
        /**------------------ Comprobando claves ------------------**/
        if ($_POST['usuario_clave_nueva_1'] != "" || $_POST['usuario_clave_nueva_2'] != "") {
            if ($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Las nuevas claves ingresadas no coinciden",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            } else {
                if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_1']) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_2'])) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Las nuevas claves no ingresadas no coinciden con el formato solicitado",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $clave = mainModel::encrypt_decrypt('encrypt', $_POST['usuario_clave_nueva_1']);
            }
        } else {
            $clave = $campos['usuario_clave'];
        }

        /* Comprobando credenciales para actualizar datos*/
        if ($tipo_cuenta == "Propia") {
            $check_cuenta = mainModel::ejecutar_cosulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_clave='$admin_clave' AND usuario_id='$id'");
        } else {
            session_start(['name' => 'SPM']);
            if ($_SESSION['privilegio_spm'] != 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error- inesperado",
                    "Texto" => "No tienes los permisos necesarios para realizar esta operación",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $check_cuenta = mainModel::ejecutar_cosulta_simple("SELECT usuario_id FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_clave='$admin_clave'");
            // echo var_dump($check_cuenta);
        }
        // echo  "Consulta del row: ", $check_cuenta->rowCount();
        if ($check_cuenta->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Nombre y clave de administrador no validos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        //Preparandos datos para enviarlos al modelo 

        $datos_usuario_up = [
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "Email" => $email,
            "Usuario" => $usuario,
            "Clave" => $clave,
            "Estado" => $estado,
            "Privilegio" => $privilegio,
            "ID" => $id
        ];

        if (usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Datos actualizados",
                "Texto" => "Los datos han sido actualizados con exito",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos podido actualizar los datos, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }/* Fin del Controlador */
}