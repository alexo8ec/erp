<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use SoapClient;

class Utilidades extends Model
{
    public static function valorOperadoras($r)
    {
        $token = Utilidades::validateToken($r->token);
        if ($token['status'] == true && $token['token'] == true && $token['firma'] == true) {
            return ProductosTelefonicos::where('id_operadora_producto', $r->id)->where('estado_producto', 1)->get();
        }
    }
    public static function pantallas($r)
    {
        $arrayResultado = [];
        $token = Utilidades::validateToken($r->token);
        if ($token['status'] == true && $token['token'] == true && $token['firma'] == true) {
            try {
                $data = Utilidades::datosGeneralesApi($token['id'], $r);
                $arrayResultado['status'] = true;
                $arrayResultado['code'] = 200;
                $arrayResultado['description'] = 'Datos consultados correctamentes';
                $arrayResultado['data'] = $data;
            } catch (Exception $e) {
                $arrayResultado['status'] = false;
                $arrayResultado['code'] = 500;
                $arrayResultado['description'] = $e->getMessage();
                $arrayResultado['linea'] = $e->getLine();
            }
        } else {
            $arrayResultado['status'] = false;
            $arrayResultado['code'] = 500;
            $arrayResultado['description'] = 'Error al validar el token';
            $arrayResultado['data'] = ['token' => $token['mnsT'], 'firma' => $token['mnsF']];
        }
        return json_encode($arrayResultado);
    }
    public static function datosGeneralesApi($id, $r = '')
    {
        $respuesta = [];
        $usuario = Usuarios::leftjoin('db_archivos as a', 'db_usuarios.id_usuario', 'a.id_usuario_creacion_archivo')->where('id_usuario', $id)->first(['db_usuarios.id_usuario', 'db_usuarios.usuario', 'db_usuarios.nombre_usuario', 'db_usuarios.apellido_usuario', 'a.api_archivo as archivo']);
        $saldos = Usuarios::selectRaw(
            'IFNULL(SUM(ingreso_movimiento),0) AS ingreso,
            IFNULL(SUM(egreso_movimiento),0) AS egreso,
            IFNULL(SUM(ingreso_movimiento),0)-IFNULL(SUM(egreso_movimiento),0) AS saldo'
        )
            ->leftjoin('db_movimientos_billetera as m', 'db_usuarios.id_usuario', '=', 'm.id_cliente_movimiento')
            ->where('db_usuarios.id_usuario', $id)
            ->where('m.estado_movimiento', 1)
            ->first();
        $movimientos = MovimientosBilletera::join('db_usuarios as u', 'db_movimientos_billetera.id_cliente_movimiento', 'u.id_usuario')
            ->join('db_catalogos as c', 'db_movimientos_billetera.id_tipo_movimiento', 'c.id_catalogo')
            ->where('id_cliente_movimiento', $id)
            ->where('estado_movimiento', 1)
            ->orderBy('fecha_movimiento', 'DESC')
            ->limit(20)
            ->get([
                'id_movimiento',
                'uuid_movimiento',
                'ingreso_movimiento',
                'egreso_movimiento',
                'fecha_movimiento',
                'id_cliente_movimiento',
                'estado_movimiento',
                'observacion_movimiento',
                'id_tipo_movimiento',
                'id_envia_billetera_movimiento',
                'id_recibe_billetera_movimiento',
                'tipo_uso_movimiento',
                'id_usuario_creacion_movimiento',
                'created_at_movimiento',
                'nombre_usuario',
                'apellido_usuario',
                'nombre_catalogo',
                'valor_catalogo'
            ]);
        $respuesta['usuario'] = $usuario;
        $respuesta['saldos'] = $saldos;
        $respuesta['movimientos'] = $movimientos;
        if ($r['pantalla'] == 'recargas') {
            $respuesta['operadoras'] = Catalogos::traerCatalogo('operadorastelefonicas');
        }
        return $respuesta;
    }
    public static function validateToken($token)
    {
        $arrayRresult = [
            'status' => false,
        ];
        $secret = env('JWT_SECRET');
        if (!isset($token)) {
            exit('Please provide a key to verify');
        }
        $jwt = $token;
        try {
            $tokenParts = explode('.', $jwt);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signatureProvided = $tokenParts[2];
            $expiration = Carbon::createFromTimestamp(json_decode($payload)->exp);
            $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);
            $base64UrlHeader = Utilidades::base64UrlEncode($header);
            $base64UrlPayload = Utilidades::base64UrlEncode($payload);
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
            $base64UrlSignature = Utilidades::base64UrlEncode($signature);
            $p = Utilidades::base64UrlDecode($base64UrlPayload);
            $p = json_decode($p);
            $signatureValid = ($base64UrlSignature === $signatureProvided);
            $arrayRresult['id'] = $p->id;
            if ($tokenExpired) {
                $arrayRresult['token'] = false;
                $arrayRresult['mnsT'] = 'El token ha expirado';
            } else {
                $arrayRresult['token'] = true;
                $arrayRresult['status'] = true;
                $arrayRresult['mnsT'] = 'El token esta activo';
            }
            if ($signatureValid) {
                $arrayRresult['firma'] = true;
                $arrayRresult['status'] = true;
                $arrayRresult['mnsF'] = 'La firma es valida';
            } else {
                $arrayRresult['firma'] = false;
                $arrayRresult['mnsF'] = 'La firma no es valida';
            }
        } catch (Exception $e) {
            $arrayRresult['token'] = false;
            $arrayRresult['firma'] = false;
            $arrayRresult['mnsT'] = 'El token no es valido';
            $arrayRresult['mnsF'] = 'La firma no es valida';
        }
        return $arrayRresult;
    }
    public static function getBearerToken()
    {
        $headers = Utilidades::getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return Utilidades::validateToken($matches[1]);
            }
        }
        return null;
    }
    public static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    private static function base64UrlEncode($data)
    {
        $base64Url = strtr(base64_encode($data), '+/', '-_');
        return rtrim($base64Url, '=');
    }
    private static function base64UrlDecode($base64Url)
    {
        return base64_decode(strtr($base64Url, '-_', '+/'));
    }
    public static function getJwt($usuario)
    {
        $header = json_encode([
            'alg' => env('JWT_ALGO'),
            'typ' => 'JWT'
        ]);
        $exp = strtotime('+10 hour', strtotime(date('Y-m-d H:i:s')));
        $det = Utilidades::detect();
        $payload = json_encode([
            'id' => $usuario->id_usuario,
            'name' => $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario,
            'email' => $usuario->email_usuario,
            'server_name' => $_SERVER['SERVER_NAME'],
            'server_protocol' => $_SERVER['SERVER_PROTOCOL'],
            'server_port' => $_SERVER['SERVER_PORT'],
            'ip_visit' => Utilidades::getRealIP(),
            'dispositive' => $det['agente'],
            'sub' => strtotime(date('Y-m-d H:i:s')),
            'exp' => $exp,
        ]);
        $base64UrlHeader = Utilidades::base64UrlEncode($header);
        $base64UrlPayload = Utilidades::base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, env('JWT_SECRET'), true);
        $base64UrlSignature = Utilidades::base64UrlEncode($signature);
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $jwt;
    }
    public static function generateVerifyNumber($string)
    {
        $evens = 0;
        $unevens = 0;
        $string = strrev($string);
        $i = 0;
        while ($i < strlen($string)) {
            if (is_numeric($evens) && is_numeric($unevens) && is_numeric($string)) {
                if ($i % 2 == 0) $unevens += $string[$i];
                else $evens += $string[$i];
                $i++;
            }
        }
        $sum = $evens + ($unevens * 3);
        if (10 - ($sum % 10) == 10) {
            $verify_number = 0;
        } else {
            $verify_number = 10 - ($sum % 10);
        }
        return $verify_number;
    }
    public static function getDocGenerados()
    {
        $id_empresa = session('idEmpresa');
        $periodo = session('periodo');
        $sql = "SELECT 'Facturas' tipo,COUNT(num_factura_venta_cabecera) total FROM db_cabecera_ventas
		WHERE id_empresa_venta_cabecera=$id_empresa
		AND YEAR(fecha_emision_venta_cabecera)=$periodo
		AND motivo_venta_cabecera='VENTA'
		UNION
		SELECT 'Notas de Cédito' tipo,(SELECT COUNT(DISTINCT(num_factura_venta_cabecera)) FROM db_cabecera_ventas WHERE id_empresa_venta_cabecera=$id_empresa AND YEAR(fecha_emision_venta_cabecera)=$periodo AND motivo_venta_cabecera='NOTACREDITO') total 
		/*UNION
		SELECT 'Retenciones' tipo,COUNT(DISTINCT(num_retencion)) FROM bm_retenciones
		WHERE id_empresa=$id_empresa
		AND YEAR(fecha_emision)=$periodo*/";
        return DB::select($sql);
    }
    public static function db_odbc()
    {
        /*
        DB_CONNECTION_SQL=sqlsrv
        DB_HOST_SQL=181.198.95.70
        DB_PORT_SQL=7173
        DB_DATABASE_SQL=siinf_miangel_ec
        DB_USERNAME_SQL=ALEX
        DB_PASSWORD_SQL=aX56+35Macxx1
        */
        $serverName = '181.198.95.70,7173';
        $connectionInfo = array("Database" => "siinf_miangel_ec", "UID" => "ALEX", "PWD" => "aX56+35Macxx1");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        $serverName = "serverName\instanceName";
        $connectionInfo = array("Database" => "dbName", "UID" => "username", "PWD" => "password");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $sql = "SELECT FirstName, LastName FROM SomeTable";
        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo '<pre>';
            print_r($row);
        }

        sqlsrv_free_stmt($stmt);

        exit;
        if (!$conn) {
            echo 'No se pudo conectar';
        } else {
            echo 'Conexion exitosa';
        }
        $sql = "select * from Pro_Prod_Terminados;";

        $myID = 0;
        $query = "select * from Pro_Prod_Terminados;";
        $stmt = sqlsrv_prepare($conn, $query, array(&$myID));
        $result = sqlsrv_execute($stmt);

        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        dd($row);

        odbc_close($conn);

        /*
        // Cadena de conexión
        $dsn = "Driver={SQL Server};Server=181.198.95.70,7173;Database=siinf_miangel_ec;Integrated Security=SSPI;Persist Security Info=False;initial catalog=siinf_miangel_ec;User ID=ALEX;Password=aX56+35Macxx1;";
        // Conexión con la base de datos
        $conn = odbc_connect($dsn, '', '');
        if (!$conn) {
            exit("Error conectando con base de datos: " . $conn);
        }
        dd($conn);
        // Definimos la consulta a realizar
        $sql = "SELECT * FROM Tabla";
        // Ejecutamos la consulta
        $rs = odbc_exec($conn, $sql);
        if (!$rs) {
            exit("Error en la consulta SQL");
        }
        // Mostramos el resultado de la consulta
        while (odbc_fetch_row($rs)) {
            $resultado = odbc_result($rs, "Campo");
            echo $resultado;
        }

        // Cerramos la conexión
        odbc_close($conn);
        */
    }
    public static function crearLineaProducto($r)
    {
        $fila = $r->fila;
        return '<tr id="tr' . $fila . '">
        <td>
            <div id="foto' . $fila . '"><img src="' . url('/') . '/public/img/img_nodisp.gif" width="100px" height="100px"></div>
        </td>
        <td>
            <input type="hidden" id="imagen_producto' . $fila . '" name="imagen_producto' . $fila . '" value="" />
            <label title="Imagen del producto" for="imagen' . $fila . '" class="ladda-button ladda-button-demo-logo btn btn-primary" data-style="zoom-in" style="cursor:pointer;">
                <input type="file" accept="image/*" id="imagen' . $fila . '" name="imagen' . $fila . '" onchange="subirImagen(\'' . $fila . '\')" style="display:none;">
                <i class="fa fa-file-image-o"></i> Imágen
            </label>
            <div id="logo' . $fila . '"></div>
        </td>
        <td>
           <a href="javascript:;" class="btn btn-primary" onclick="abrirCrop(\'' . $fila . '\')"><i class="fa fa-crop"></i></a>
        </td>
        <td>
            <input type="text" class="form-control text-right" id="txtorden' . $fila . '" name="txtorden' . $fila . '" value="' . $fila . '">
        </td>
        <td>
            <button type="button" class="btn btn-danger" id="btneliminar' . $fila . '" onClick="eliminar(\'' . $fila . '\')" ><i class="fa fa-trash"></i> </button>
        </td>
        </tr>';
    }
    public static function modulosPrincipales()
    {
        return Modulos::where('estado_modulo', 1)->get([
            'id_modulo',
            'nombre_modulo'
        ]);
    }
    public static function verTabla($tabla)
    {
        return DB::select('DESCRIBE ' . $tabla);
    }
    public static function traerTablas()
    {
        return DB::select('SHOW TABLES FROM ' . $_ENV['DB_DATABASE'] . ';');
    }
    public static function htmlspecial($valor)
    {
        $result = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
        return $result;
    }
    public static function getDecimal($valor = 0, $digitos = 2, $formato = false)
    {
        if (trim($valor) == "") $valor = 0;
        $resultado = round($valor, $digitos, PHP_ROUND_HALF_UP);
        $resultado = number_format($resultado, $digitos, ".", $formato ? "," : "");
        return $resultado;
    }
    public static function getDate($valor)
    {
        if (trim($valor) == "") return "";
        $result = date('d/m/Y', strtotime($valor));
        return $result;
    }
    public static function getBool($valor)
    {
        $result = $valor == true || $valor == 1 || $valor == "1" ? "SI" : "NO";
        return $result;
    }
    public static function leerCorreo($r)
    {
        $carpeta = 'notls';
        $servidorEntrada = 'mail.facturalgo1.com';
        $puertoEntrada = '993';
        $tipo = 'imap';
        $cifradoEntrada = 'ssl/novalidate-cert';
        $user = 'info@facturalgo1.com';
        $pass = '0921605895aA*';
        $servidor = '{' . $servidorEntrada . ':' . $puertoEntrada . '/' . $tipo . '/' . $cifradoEntrada . '/' . $carpeta . '}';
        $inbox = imap_open($servidor, $user, $pass);
        $inbox_ = [];
        $mensaje = imap_fetchbody($inbox, trim($r->msgno), "1.2");
        if ($mensaje == '')
            $mensaje = imap_fetchbody($inbox, trim($r->msgno), "2");
        elseif ($mensaje == '')
            $mensaje = imap_fetchbody($inbox, trim($r->msgno), "1");
        $mensaje = imap_qprint($mensaje);
        $body = imap_qprint(imap_body($inbox, $r->msgno));
        $structure = imap_fetchstructure($inbox, $r->msgno);
        $attachments = array();
        if (isset($structure->parts) && count($structure->parts)) {
            for ($i = 0; $i < count($structure->parts); $i++) {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }
                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $r->msgno, $i + 1);
                    /*if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        } elseif ($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }*/
                }
            }
        }
        $num = (int)$structure->parts[0]->type + 1;
        $inbox_ = array(
            'index' => $r->msgno,
            'type' => $structure->parts[0]->subtype,
            'header' => imap_headerinfo($inbox, $r->msgno),
            'body' => $body,
            'body_' => $mensaje,
            'body_formated' => imap_fetchbody($inbox, $r->msgno, $num),
            'body_0' => imap_fetchbody($inbox, $r->msgno, 0),
            'body_1' => imap_fetchbody($inbox, $r->msgno, 1),
            'body_2' => imap_fetchbody($inbox, $r->msgno, 2),
            'body_3' => imap_fetchbody($inbox, $r->msgno, 3),
            'body_4' => imap_fetchbody($inbox, $r->msgno, 4),
            'body_5' => imap_fetchbody($inbox, $r->msgno, 5),
            'body_6' => imap_fetchbody($inbox, $r->msgno, 6),
            'body_7' => imap_fetchbody($inbox, $r->msgno, 7),
            'body_8' => imap_fetchbody($inbox, $r->msgno, 8),
            'attachments' => $attachments,
            'structure' => $structure,
        );
        imap_close($inbox);
        //echo '<pre>';print_r($inbox_);exit;
        return $inbox_;
    }
    public static function carpetasEmail($buzon = '')
    {
        $carpeta = 'notls';
        $servidorEntrada = 'mail.facturalgo1.com';
        $puertoEntrada = '993';
        $tipo = 'imap';
        $cifradoEntrada = 'ssl/novalidate-cert';
        $user = 'info@facturalgo1.com';
        $pass = '0921605895aA*';
        $servidor = '{' . $servidorEntrada . ':' . $puertoEntrada . '/' . $tipo . '/' . $cifradoEntrada . '/' . $carpeta . '}' . $buzon;
        $inbox = imap_open($servidor, $user, $pass);
        $folder_list = imap_list($inbox, $servidor, "*");
        $checar = imap_check($inbox);
        $comprobar = imap_mailboxmsginfo($inbox);
        if ($comprobar) {
            $data['noleidos'] = $comprobar->Unread;
        }
        $data['carpetas'] = $folder_list;
        $emails = imap_search($inbox, 'ALL');
        $arrayCorreos = [];
        $arrayCorreos['servidor'] = $servidor;
        $arrayCorreos['noleidos'] = $data['noleidos'];
        $arrayCorreos['carpetas'] = $data['carpetas'];
        $inbox_ = [];
        if ($emails) {
            for ($i = 1; $i <= count($emails); $i++) {
                $inbox_[] = array(
                    'index'     => $i,
                    'header'    => imap_headerinfo($inbox, $i),
                    'body'      => imap_body($inbox, $i),
                    'structure' => imap_fetchstructure($inbox, $i)
                );
            }
        }
        krsort($inbox_);
        $arrayCorreos['correos'] = $inbox_;
        imap_close($inbox);
        return $arrayCorreos;
    }
    public static function dias_pasados($fecha_inicial, $fecha_final)
    {
        $dias = (strtotime($fecha_inicial) - strtotime($fecha_final)) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        return $dias;
    }
    public static function procesarAsientos($r)
    {
        DB::beginTransaction();
        try {
            if ($r->tipo == 'ingreso') {
                $ventas = VentasCabecera::whereYear('fecha_emision_venta_cabecera', $r->anio)
                    ->whereMonth('fecha_emision_venta_cabecera', $r->mes)
                    ->where('contabilizado_venta_cabecera', 0)
                    ->get();
                if (count($ventas) > 0) {
                    foreach ($ventas as $row) {
                        $arrayAsientoCabecera = [
                            'id_cliente_asiento_cabecera' => $row->id_cliente_venta_cabecera,
                            'uuid_asiento_cabecera' => Uuid::uuid1(),
                            'fecha_asiento_cabecera' => $row->fecha_emision_venta_cabecera,
                            'debe_asiento_cabecera' => $row->total_venta_cabecera,
                            'haber_asiento_cabecera' => $row->total_venta_cabecera,
                            'id_factura_venta_asiento_cabecera' => $row->id_venta_cabecera,
                            'id_usuario_creacion_asiento_cabecera' => session('idUsuario'),
                            'id_usuario_modificacion_asiento_cabecera' => session('idUsuario'),
                        ];
                        $idAsiento = AsientosCabecera::insertGetId($arrayAsientoCabecera);
                        $codContable = '';
                        if ($row->pagado_venta_cabecera == 1) {
                            $cuentaContable = Cuentas::where('id_cuenta', $row->id_cuenta_venta_cabecera)->first();
                            $codContable = $cuentaContable->codigo_contable_cuenta;
                        } else {
                            $cliente = Clientes::where('id_cliente', $row->id_cliente_venta_cabecera)->first();

                            $codContable = $cliente->cod_contable_cliente;
                        }
                        //Debe
                        $arrayAsientoDetalle = [
                            'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                            'fecha_asiento_detalle' => $row->fecha_emision_venta_cabecera,
                            'codigo_cuenta_detalle_asiento' => $codContable,
                            'debe_asiento_detalle' => $row->total_venta_cabecera,
                            'haber_asiento_detalle' => 0,
                        ];
                        AsientosDetalle::insert($arrayAsientoDetalle);
                        //Haber
                        if ($row->subtotal0_venta_cabecera > 0) {
                            $arrayAsientoDetalle = [
                                'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                                'fecha_asiento_detalle' => $row->fecha_emision_venta_cabecera,
                                'codigo_cuenta_detalle_asiento' => '4.01.602',
                                'debe_asiento_detalle' => 0,
                                'haber_asiento_detalle' => $row->subtotal0_venta_cabecera,
                            ];
                            AsientosDetalle::insert($arrayAsientoDetalle);
                        }
                        //Haber
                        if ($row->subtotal12_venta_cabecera > 0) {
                            $arrayAsientoDetalle = [
                                'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                                'fecha_asiento_detalle' => $row->fecha_emision_venta_cabecera,
                                'codigo_cuenta_detalle_asiento' => '4.01.601',
                                'debe_asiento_detalle' => 0,
                                'haber_asiento_detalle' => $row->subtotal12_venta_cabecera,
                            ];
                            AsientosDetalle::insert($arrayAsientoDetalle);
                        }
                        //Haber
                        if ($row->iva_venta_cabecera > 0) {
                            $arrayAsientoDetalle = [
                                'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                                'fecha_asiento_detalle' => $row->fecha_emision_venta_cabecera,
                                'codigo_cuenta_detalle_asiento' => '2.01.421.000001',
                                'debe_asiento_detalle' => 0,
                                'haber_asiento_detalle' => $row->iva_venta_cabecera,
                            ];
                            AsientosDetalle::insert($arrayAsientoDetalle);
                        }
                        VentasCabecera::where('id_venta_cabecera', $row->id_venta_cabecera)->update(['contabilizado_venta_cabecera' => 1]);
                    }
                }
            } elseif ($r->tipo == 'gastos') {
                $compras = ComprasCabecera::whereYear('fecha_emision_compra_cabecera', $r->anio)
                    ->whereMonth('fecha_emision_compra_cabecera', $r->mes)
                    ->where('contabilizado_compra_cabecera', 0)
                    ->get();
                if (count($compras) > 0) {
                    foreach ($compras as $row) {
                        $arrayAsientoCabecera = [
                            'id_proveedor_asiento_cabecera' => $row->id_proveedor_compra_cabecera,
                            'uuid_asiento_cabecera' => Uuid::uuid1(),
                            'fecha_asiento_cabecera' => $row->fecha_emision_compra_cabecera,
                            'debe_asiento_cabecera' => $row->total_compra_cabecera,
                            'haber_asiento_cabecera' => $row->total_compra_cabecera,
                            'id_factura_compra_asiento_cabecera' => $row->id_compra_cabecera,
                            'id_usuario_creacion_asiento_cabecera' => session('idUsuario'),
                            'id_usuario_modificacion_asiento_cabecera' => session('idUsuario'),
                        ];
                        $idAsiento = AsientosCabecera::insertGetId($arrayAsientoCabecera);
                        $codContable = '';
                        if ($row->pagado_compra_cabecera == 1) {
                            $cuentaContable = Cuentas::where('id_cuenta', $row->id_cuenta_compra_cabecera)->first();
                            $codContable = $cuentaContable->codigo_contable_cuenta;
                        } else {
                            $proveedor = Proveedores::where('id_proveedor', $row->id_proveedor_compra_cabecera)->first();
                            $codContable = $proveedor->cod_plan_proveedor;
                        }
                        //Haber
                        $arrayAsientoDetalle = [
                            'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                            'fecha_asiento_detalle' => $row->fecha_emision_compra_cabecera,
                            'codigo_cuenta_detalle_asiento' => $codContable,
                            'debe_asiento_detalle' => 0,
                            'haber_asiento_detalle' => $row->total_compra_cabecera,
                        ];
                        AsientosDetalle::insert($arrayAsientoDetalle);
                        //Debe
                        $detalleCompra = ComprasDetalle::where('id_cabecera_compra_detalle', $row->id_compra_cabecera)->get();
                        if (count($detalleCompra) > 0) {

                            foreach ($detalleCompra as $rowdeta) {
                                $arrayAsientoDetalle_ = [
                                    'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                                    'fecha_asiento_detalle' => $row->fecha_emision_compra_cabecera,
                                    'codigo_cuenta_detalle_asiento' => $rowdeta->codigo_contable_compra_detalle,
                                    'debe_asiento_detalle' => $rowdeta->precio_unitario_compra_detalle,
                                    'haber_asiento_detalle' => 0,
                                ];
                                AsientosDetalle::insert($arrayAsientoDetalle_);
                            }
                        }
                        //Debe
                        if ($row->iva_compra_cabecera > 0) {
                            $arrayAsientoDetalle = [
                                'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                                'fecha_asiento_detalle' => $row->fecha_emision_compra_cabecera,
                                'codigo_cuenta_detalle_asiento' => '2.01.421.000001',
                                'debe_asiento_detalle' => $row->iva_compra_cabecera,
                                'haber_asiento_detalle' => 0,
                            ];
                            AsientosDetalle::insert($arrayAsientoDetalle);
                        }
                        ComprasCabecera::where('id_compra_cabecera', $row->id_compra_cabecera)->update(['contabilizado_compra_cabecera' => 1]);
                    }
                }
                /*$asientos = AsientosCabecera::whereYear('fecha_asiento_cabecera', 2022)->get();
                foreach ($asientos as $row) {
                    echo '<pre>';
                    print_r($row);
                    print_r($row->detalles);

                }*/
                /*DB::rollBack();
                exit;*/
            }
            DB::commit();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Asientos de ingresos guardados correctamente...');
            return json_encode($result);
        } catch (Exception $e) {
            DB::rollBack();
            return json_encode(['Error' => $e->getMessage(), 'Linea' => $e->getLine()]);
        }
    }
    public static function crearAnioContable()
    {
        $periodo = EstadoAsientos::where('anio_asiento', session('periodo'))
            ->first();
        if ($periodo == '') {
            $arrayMes = [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Septiembre',
                'Octubre',
                'Noviembre',
                'Diciembre',
            ];
            for ($i = 0; $i < count($arrayMes); $i++) {
                $arrayEstadoAsiento = [
                    'anio_asiento' => session('periodo'),
                    'mes_asiento' => $arrayMes[$i],
                    'id_usuario_creacion_asiento' => session('idUsuario'),
                    'id_usuario_modificacion_asiento' => session('idUsuario'),
                    'id_empresa_asiento' => session('idEmpresa')
                ];
                EstadoAsientos::insert($arrayEstadoAsiento);
            }
        }
        return EstadoAsientos::where('anio_asiento', session('periodo'))
            ->get();
    }
    public static function agregarLineaCompra($r)
    {
    }
    public static function obtenercomprobanteSRI($clave = '')
    {
        $html = '';
        //$servicio="https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl";
        $servicio = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";

        $parametros = array();
        $parametros['claveAccesoComprobante'] = $clave;
        $client = new SoapClient($servicio, $parametros);

        $result = $client->autorizacionComprobante($parametros);
        $docCod = substr($clave, 8, 2);
        $esta = substr($clave, 24, 3);
        $emis = substr($clave, 27, 3);
        $num = substr($clave, 30, 9);
        //echo '<pre>';print_r($result);exit;
        if ($result->RespuestaAutorizacionComprobante->numeroComprobantes > 0) {
            $comprobante = simplexml_load_string($result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
            //echo '<pre>';print_r($comprobante);exit;
            $fechaEmision = date('Y-m-d', strtotime(str_replace('/', '-', $comprobante->infoFactura->fechaEmision)));

            $idTipoDoc = TipoDocumento::where('codigo_sri', $comprobante->infoTributaria->codDoc)->first();

            $tipoComprobante = $idTipoDoc->codigo_sri . ' - ' . $idTipoDoc->tipo;

            $contribuyenteEspecial = isset($comprobante->infoFactura->contribuyenteEspecial) ? 'SI' : 'NO';
            $comboProductos = '';
            $comboSustentoTributario = '';
            $sustentoTributario = SustentoTributario::orderBy('codigo_sustento')->get();
            if (count($sustentoTributario) > 0) {
                foreach ($sustentoTributario as $row) {
                    $comboSustentoTributario .= '<option value="' . $row->id_sustento . '">(' . $row->codigo_sustento . ') | ' . $row->nombre_sustento . '</optio>';
                }
            }
            $html = '<table width="100%">
                <tr>
                    <td>Clave Consultada: </td>
                    <td>' . $result->RespuestaAutorizacionComprobante->claveAccesoConsultada . '</td>
                </tr>
                <tr>
                    <td>Estado: </td>
                    <td>' . $result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado . '</td>
                </tr>
                <tr>
                    <td># Autorización: </td>
                    <td>' . $result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion . '</td>
                </tr>
                <tr>
                    <td>Fecha Autorización: </td>
                    <td>' . date('Y-m-d H:i:s', strtotime($result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion)) . '</td>
                </tr>
                <tr>
                    <td>Ambiente: </td>
                    <td>' . $result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente . '</td>
                </tr>
            </table>';
            $docCod = substr($result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion, 8, 2);
            if ($docCod == '01') {
                $mPago = MetodoPago::where('cod_sri_metodo_pago', $comprobante->infoFactura->pagos->pago->formaPago)->first();
                if ($mPago != '')
                    $formaPago = $mPago->cod_sri_metodo_pago . ' - ' . $mPago->metodo_pago;
                else
                    $formaPago = '01 - SIN UTILIZACION DEL SISTEMA FINANCIERO';
                $html .= '<div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_emision" class="form-label">Fecha de emisión</label>
                                        <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" placeholder="dd/mm/yyyy" value="' . $fechaEmision . '" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ruc" class="form-label">Ruc</label>
                                        <input type="text" class="form-control text-end" id="ruc" name="ruc" placeholder="0999999999001" value="' . $comprobante->infoTributaria->ruc . '" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="razon_social" class="form-label">Razón social</label>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="" value="' . $comprobante->infoTributaria->razonSocial . '" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_comercial" class="form-label">Nombre comercial</label>
                                        <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" placeholder="" value="' . $comprobante->infoTributaria->nombreComercial . '" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="serie" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="" value="' . ltrim($comprobante->infoTributaria->dirMatriz) . '-' . $comprobante->infoTributaria->ptoEmi . '" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="secuencial" class="form-label">Sucursal</label>
                                        <input type="text" class="form-control text-end" id="direccion_sucursal" name="direccion_sucursal" placeholder="" value="' . ltrim($comprobante->infoFactura->dirEstablecimiento) . '" readonly>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="clave_acceso" class="form-label">Clave acceso</label>
                                        <input type="text" class="form-control text-end" id="clave_acceso" name="clave_acceso" placeholder="" value="' . $comprobante->infoTributaria->claveAcceso . '" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_doc" class="form-label">Tipo documento</label>
                                        <input type="text" class="form-control" id="tipo_doc" name="tipo_doc" placeholder="" value="' . $tipoComprobante . '" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="obligado_contabilidad" class="form-label">Obligado contabilidad</label>
                                        <input type="text" class="form-control" id="obligado_contabilidad" name="obligado_contabilidad" placeholder="" value="' . $comprobante->infoFactura->obligadoContabilidad . '" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contribuyente_especial" class="form-label">Contribuyente especial</label>
                                        <input type="hidden" name="numContribuyenteEspecial" value="' . $comprobante->infoFactura->contribuyenteEspecial . '" />
                                        <input type="text" class="form-control" id="contribuyente_especial" name="contribuyente_especial" placeholder="" value="' . $contribuyenteEspecial . '" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="forma_pago" class="form-label">Forma de pago</label>
                                        <input type="text" class="form-control" id="forma_pago" name="forma_pago" placeholder="" value="' . $formaPago . '" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="serie" class="form-label">Serie</label>
                                        <input type="text" class="form-control text-end" id="serie" name="serie" placeholder="" value="' . $comprobante->infoTributaria->estab . '-' . $comprobante->infoTributaria->ptoEmi . '" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="secuencial" class="form-label">Secuencial</label>
                                        <input type="text" class="form-control text-end" id="secuencial" name="secuencial" placeholder="" value="' . $comprobante->infoTributaria->secuencial . '" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="secuencial" class="form-label">Sustento tributario</label>
                                    <select class="form-control select2" id="id_sustento_tributario" name="id_sustento_tributario" style="width:100%;text-align:left;">
                                    <option value="">--Seleccionar--</option>
                                    ' . $comboSustentoTributario . '
                                    </select>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table id="tablaItems" width="100%">
                                            <thead>
                                                <tr>
                                                    <td>Descripción</td>
                                                    <td>Producto</td>
                                                    <td>Tipo</td>
                                                    <td>Cuenta</td>
                                                    <td>Cantidad</td>
                                                    <td>Precio</td>
                                                    <td>Iva</td>
                                                    <td>Descuento</td>
                                                    <td>Total</td>
                                                </tr>
                                            </thead>
                                            <tbody>';
                $totalIva = 0;
                $cont = 0;
                $subtotal0 = 0;
                $subtotal12 = 0;
                $descuento0 = 0;
                $descuento12 = 0;
                foreach ($comprobante->detalles->detalle as $row) {
                    $totalIva += $row->impuestos->impuesto->valor;
                    if ((float)$row->impuestos->impuesto->valor > 0) {
                        $subtotal12 += (float)$row->precioUnitario * $row->cantidad;
                        $descuento12 += (float)$row->descuento;
                    } else {
                        $subtotal0 += (float)$row->precioUnitario * $row->cantidad;
                        $descuento0 += (float)$row->descuento;
                    }
                    $html .= '<tr>
                <td style="width:15%;"><input type="text" class="form-control" id="producto_externo_' . $cont . '" name="producto_externo[]" value="' . $row->codigoPrincipal . ' | ' . $row->descripcion . '" /></td>
                <td style="width:15%"><select id="producto_' . $cont . '" name="producto[]" class="form-control producto" style="width:100%;text-align:left;"><option value="">--Seleccionar--</option>' . $comboProductos . '</select></td>
                <td style="width:10%">
                    <select id="tipoContable_' . $cont . '" name="tipo[]" onchange="traerPlan(this)" class="form-control tipoProducto select2" style="width:100%">
                    <option value="">--Seleccione el tipo contable--</option>
                    <option value="activo">Activo</option>
                    <option value="pasivo">Pasivo</option>
                    <option value="ingreso">Ingreso</option>
                    <option value="costo">Costo</option>
                    <option value="gasto">Gasto</option>
                    </select>
                </td>
                <td style="width:10%">
                    <select id="codigoPlan_' . $cont . '" name="codigoPlan[]" class="form-control codContable select2" style="width:100%">
                    </select>
                </td>
                <td style="text-align:right;width:10%"><input type="text" id="cantidad_' . $cont . '" name="cantidad[]" class="form-control text-end" value="' . $row->cantidad . '"/></td>
                <td style="text-align:right;width:10%"><input type="text" id="precio_' . $cont . '" name="precio[]" class="form-control text-end" value="' . $row->precioUnitario . '"/</td>
                <td style="text-align:right;width:10%"><input type="text" id="iiva_' . $cont . '" name="iiva[]" class="form-control text-end" value="' . $row->impuestos->impuesto->valor . '"/</td>
                <td style="text-align:right;width:10%"><input type="text" id="descuento_' . $cont . '" name="descuento[]" class="form-control text-end" value="' . $row->descuento . '"/</td>
                <td style="text-align:right;width:10%"><input type="text" id="total_' . $cont . '" name="total[]" class="form-control text-end" value="' . $row->precioTotalSinImpuesto . '"/</td>
                </tr>';
                    $cont++;
                }
                $subtotal0 = 0;
                $subtotal12 = 0;
                foreach ($comprobante->infoFactura->totalConImpuestos->totalImpuesto as $row) {
                    if ((float)$row->valor > 0) {
                        $subtotal12 += (float)$row->baseImponible;
                    } else {
                        $subtotal0 += (float)$row->baseImponible;
                    }
                }
                $totalPagar = $comprobante->infoFactura->totalSinImpuestos + $totalIva + $comprobante->infoFactura->propina;
                //echo '<pre>';print_r($comprobante->infoFactura);exit;
                $html .= '</tbody>';
                $html .= '<tfooter>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Subtotal 0%</td>
            <td style="text-align:right;"><input type="text" name="subtotal0" value="' . number_format($subtotal0, 2) . '" class="form-control text-end" readonly /></td>
            </tr>
            <tr>
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Subtotal 12%</td>
            <td style="text-align:right;"><input type="text" name="subtotal12" value="' . number_format($subtotal12, 2)  . '" class="form-control text-end" readonly /></td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Subtotal no objeto de iva</td>
            <td style="text-align:right;">0.00</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Subtotal exento de iva</td>
            <td style="text-align:right;">0.00</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Total descuento</td>
            <td style="text-align:right;"><input type="text" name="descuentos" value="' . $comprobante->infoFactura->totalDescuento . '" class="form-control text-end" readonly /></td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Subtotal sin impuestos</td>
            <td style="text-align:right;"><input type="text" name="subtotal" value="' . $comprobante->infoFactura->totalSinImpuestos . '" class="form-control text-end" readonly /></td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Ice</td>
            <td style="text-align:right;">0.00</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Iva 12%</td>
            <td style="text-align:right;"><input type="text" name="iva" value="' . $totalIva . '" class="form-control text-end" readonly /></td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Total devolución iva</td>
            <td style="text-align:right;">0.00</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Irbpnr</td>
            <td style="text-align:right;">0.00</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Propina</td>
            <td style="text-align:right;"><input type="text" name="propina" value="' . $comprobante->infoFactura->propina . '" class="form-control text-end" readonly /></td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:right;">Valor total</td>
            <td style="text-align:right;"><input type="text" name="total_pagar" value="' . $totalPagar . '" class="form-control text-end" readonly /></td>
            </tr>
            </tfooter>';
                $html .= '</table>

            </div>
                                </div>
                            </div>
                            <div>
                                <button type="button" id="btnguardarCompra" class="btn btn-primary w-md">Guardar</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>';
                $html .= '
        <script>
        $(document).ready(function(){
            $(\'.tipoProducto\').select2({ placeholder: "--Seleccionar--"});
            $(\'.select2\').select2({dropdownParent: $(\'#myModal\'),placeholder: "--Seleccionar--"});
            $(\'.producto\').select2({
                dropdownParent: $(\'#myModal\'),
                noResults: function () {
                    return "No hay resultado";
                },
                searching: function () {
                    return "Buscando..";
                },
                placeholder: "--Seleccionar--",
                minimumInputLength: 3,
                ajax: {
                    url: \'inventario/buscarProducto\',
                    type: "get",
                    dataType: \'json\',
                    delay: 250,
                    data: function (params) {
                        return {term: params.term};
                    },
                    processResults: function (response) {
                        if (response.length == 0) {
                            return {
                                results: [
                                    {
                                        id: 0,
                                        text: \'El producto no existe | Agregar producto\'
                                    }
                                ]
                            };
                        } else {
                            return {results: response};
                        }
                    },
                    cache: true
                }
            }).trigger(\'change\');
            $(\'#btnguardarCompra\').click(function () {
                validarTipo();
                validarCuenta();
                if(tipoContable==0)
                {
                    mensaje(\'warning\',\'Hay un tipo contable del producto que no ha seleccionado...\');
                    return;
                }else if(cuentaContable==0)
                {
                    mensaje(\'warning\',\'Seleccione la cuenta del producto...\');
                    return;
                }else if($(\'#id_sustento_tributario\').val()==\'\')
                {
                    mensaje(\'warning\',\'Seleccione el sustento tributario...\');
                    return;
                }
                mensaje(\'question\', \'Los datos serán guardados, desea continuar?\');
            });
        });
        </script>';
            } elseif ($docCod == '03') {
            } elseif ($docCod == '04') {
            } elseif ($docCod == '05') {
            } elseif ($docCod == '06') {
            } elseif ($docCod == '07') {
            }
        } else {
            $html = 'No existe comprobante o aun no ha sido autorizado...';
        }
        return $html;
    }
    public static function getCampos($id)
    {
        $submodulo = SubModulos::where('id_submodulo', $id)->first(['tabla_submodulo']);
        $columas = DB::select(DB::raw('SHOW COLUMNS FROM ' . $submodulo->tabla_submodulo));
        if (count($columas) > 0) {
            foreach ($columas as $row) {
                $campo = DetalleFormulario::where('campo_detalle_formulario', $row->Field)->first();
                if ($campo == '') {
                    $arrayFormulario = [
                        'campo_detalle_formulario' => $row->Field,
                        'tipo_campo_detalle_formulario' => $row->Type,
                        'id_tab_detalle_formulario' => 0,
                        'id_formulario_detalle_formulario' => $id,
                        'id_usuario_creacion_detalle_formulario' => session('idUsuario'),
                        'id_usuario_modificacion_detalle_formulario' => session('idUsuario'),
                    ];
                    DetalleFormulario::insert($arrayFormulario);
                }
            }
        }
        return DetalleFormulario::where('id_formulario_detalle_formulario', $id)->get();
    }
    public static function mostarvaloresventa()
    {
        $info = '';
        $datos = Catalogos::where('codigo_catalogo', 'like', '%valor%')
            ->whereNotNull('id_catalogo_pertenece')
            ->orderBy('codigo_catalogo')
            ->get();

        if (count($datos) > 0) {
            $coun = 0;
            $check = '';
            foreach ($datos as $row) {
                $coun++;
                if ($coun == 1)
                    $check = 'checked';
                else
                    $check = '';
                if ($row->valor_catalogo == '')
                    $dato = $row->nombre_catalogo;
                else
                    $dato = $row->valor_catalogo;

                $info .= '<label>' . $dato . ' <input class="valorventa" type="radio" id="valorventa' . $coun . '" title="' . $dato . '" name="valorventa" ' . $check . ' value="' . $coun . '" onchange="cambiovalores(\'valorventa' . $coun . '\');" disabled /></label> | ';
            }
            $info = substr($info, 0, -2);
        }
        return $info;
        /*$sql = "SELECT * FROM bm_parametros WHERE id_empresa='$id_empresa' AND parametro LIKE('%valor_%') ORDER BY parametro;";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$coun = 0;
			$check = '';
			foreach ($query->result() as $row) {
				$coun++;
				if ($coun == 1)
					$check = 'checked';
				else
					$check = '';
				$dato = str_replace('valor_' . $coun . '_', '', $row->parametro);

				$info .= '<label>' . $dato . ' <input type="radio" id="valorventa' . $coun . '" title="' . $dato . '" name="valorventa" ' . $check . ' value="' . $coun . '" onchange="cambiovalores(\'valorventa' . $coun . '\');" /></label> | ';
			}
			$info = substr($info, 0, -2);
		}
		return $info;*/
    }
    public static function getRolUsuarios($id)
    {
        return SubModulos::leftjoin('db_modulos as m', 'db_submodulos.id_modulo_submodulo', '=', 'm.id_modulo')
            ->leftjoin('db_roles_usuario as r', function ($q) use ($id) {
                $q->on('db_submodulos.id_submodulo', '=', 'r.id_submodulo_rol')
                    ->where('r.id_usuario_rol', '=', $id)
                    ->where('r.id_empresa_rol', '=', session('idEmpresa'));
            })
            //->where('estado_submodulo', 1)
            ->orderby('m.nombre_modulo', 'ASC')
            ->orderby('db_submodulos.nombre_submodulo', 'ASC')
            ->get([
                'id_submodulo',
                'm.icono_modulo',
                'nombre_modulo',
                'nombre_submodulo',
                DB::raw('IFNULL(r.crear_rol,0) AS crear'),
                DB::raw('IFNULL(r.actualizar_rol,0) AS actualizar'),
                DB::raw('IFNULL(r.consultar_rol,0) AS consultar'),
                DB::raw('IFNULL(r.eliminar_rol,0) AS eliminar'),
            ]);
    }
    public static function verificarPermisos($r)
    {
        $estado = true;
        $arrayPermisos = [];
        $permisos = PermisosUsuario::getPermisos(session('idUsuario'));
        if ($permisos != '') {
            $submodulos = SubModulos::where('funcion_submodulo', $r->submodulo)->first();
            if ($submodulos == '') {
                $estado = false;
            } else {
                $arrayPermisos = json_decode($permisos->id_submodulos_permiso);
                $clave = array_search($submodulos->id_submodulo, $arrayPermisos);
                if ($arrayPermisos[$clave] != $submodulos->id_submodulo) {
                    $estado = false;
                }
            }
        } else {
            $estado = false;
        }
        return $estado;
    }
    public static function saveRolUsuario($req)
    {
        $dato =  RolesUsuarios::where('id_submodulo_rol', $req->idSubmodulo)
            ->where('id_usuario_rol', $req->id_usuario)
            ->where('id_empresa_rol', session('idEmpresa'))
            ->first();
        if ($dato != '') {
            $arrayRol = [
                'id_submodulo_rol' => $req->idSubmodulo,
                'id_usuario_rol' => $req->id_usuario,
                $req->accion => $req->estado,
                'id_empresa_rol' => session('idEmpresa'),
            ];
            RolesUsuarios::where('id_submodulo_rol', $req->idSubmodulo)
                ->where('id_usuario_rol', $req->id_usuario)
                ->where('id_empresa_rol', session('idEmpresa'))
                ->update($arrayRol);
        } else {
            $arrayRol = [
                'id_submodulo_rol' => $req->idSubmodulo,
                'id_usuario_rol' => $req->id_usuario,
                $req->accion => $req->estado,
                'id_empresa_rol' => session('idEmpresa'),
                'fecha_creacion_rol' => date('Y-m-d H:i:s')
            ];
            RolesUsuarios::insert($arrayRol);
        }
    }
    public static function verificarRolesUsuario($m)
    {
        $submodulo = Submodulos::where('pagina', $m)->first(['id_submodulo']);
        return RolesUsuarios::where('id_submodulo_rol', $submodulo->id_submodulo)
            ->where('id_usuario_rol', session('idUsuario'))
            ->where('id_empresa_rol', session('idEmpresa'))
            ->first();
    }
    public static function foldersize($src)
    {
        $total_size = 0;
        if (is_dir($src)) {
            $dir = opendir($src);
            while ($element = readdir($dir)) {
                if ($element != '' && $element != '.' && $element != '..') {
                    $element = $src . $element;
                    if (is_dir($element)) {
                        $size = Utilidades::foldersize($element . '/');
                        $total_size += $size;
                    } else {
                        $size = filesize($element);
                        $total_size += $size;
                    }
                }
            }
            return $total_size;
        }
    }
    public static function generaPass($long = 7)
    {
        $cadena = "(ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890*/)";
        $longitudCadena = strlen($cadena);
        $pass = "";
        $longitudPass = $long;
        for ($i = 1; $i <= $longitudPass; $i++) {
            $pos = rand(0, $longitudCadena - 1);
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }
    public static function keyLock()
    {
        return '|F4ctvr4l602022|';
    }
    public static function sanear_string_tildes($string)
    {
        $string = trim($string);
        $string = str_replace(array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ü'), array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'), $string);
        return $string;
    }
    public static function sanear_string_sin_tildes($string)
    {
        $string = trim($string);
        $string = str_replace(array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ü', 'á', 'é', 'í', 'ó', 'ú', '�'), array('a', 'e', 'i', 'o', 'u', 'n', 'u', 'a', 'e', 'i', 'o', 'u', '?'), $string);
        return $string;
    }
    public static function sanear_string($string)
    {
        $string = trim($string);
        $string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
        $string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
        $string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
        $string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
        $string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
        $string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string);
        $string = str_replace(array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">", "< ", ";", ",", ":", " "), '', $string);
        return $string;
    }
    public static function saveAuditoria($req, $query = null)
    {
        $consulta = '';
        $parametros = '';
        $tiempo = '';
        if ($query != null) {
            $consulta = $query[0]['query'];
            $parametros = $query[0]['bindings'];
            $tiempo = $query[0]['time'];
        }
        $res = Utilidades::detect();
        $navegador = $res['browser'];
        $sistema = $res['os'];
        if (isset($res['version']))
            $version = $res['version'];
        $ip = Utilidades::getRealIP();
        $ubic = explode('|', $req->d);
        $arrayAuditoria = [
            'controlador' => $req->c,
            'submodulo' => $req->s,
            'modelo' => $req->m,
            'vista' => $req->v,
            'query' => $consulta,
            'parametros' => json_encode($parametros),
            'tiempo_consulta' => $tiempo,
            'id_usuario_auditoria' => session('idUsuario'),
            'id_empresa_auditoria' => session('idEmpresa'),
            'ip' => $ip,
            'navegador' => $navegador,
            'sistema' => $sistema,
            'version' => $version,
            'fecha_creacion_auditoria' => date('Y-m-d H:i:s'),
            'id_usuario_creacion_auditoria' => session('idUsuario'),
            'id_usuario_modificacion_auditoria' => session('idUsuario'),
            'latitud' => isset($ubic[0]) ? $ubic[0] : '',
            'longitud' => isset($ubic[1]) ? $ubic[1] : '',
            'agente' => $_SERVER['HTTP_USER_AGENT'],
            'observacion_auditoria' => $req->o,
            'obj_json' => json_encode($req->post())
        ];
        Auditorias::insert($arrayAuditoria);
    }
    public static function getRealIP()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
                $client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : "unknown");
                $entries = explode('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
                reset($entries);
                while (list(, $entry) = each($entries)) {
                    $entry = trim($entry);
                    if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
                        $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/');
                        $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
                        if ($client_ip != $found_ip) {
                            $client_ip = $found_ip;
                            break;
                        }
                    }
                }
            } else {
                $client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : "unknown");
            }
        } else {
            $client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : "unknown");
        }
        return $client_ip;
    }
    public static function detect()
    {
        $browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME", "IEMOBILE", "NINTENDOBROWSER", "MSIE", "TRIDENT", "EDGE", "EDG");
        $os = array("WIN", "MAC", "LINUX", "ANDROID", "IPHONE", "IPAD", "LUMIA", "CRKEY", "WIIU", "XBOX", "PLAYSTATION", "3DS", "SMART-TV", "SMARTTV", "ARMV7L", "X11");
        $info['browser'] = "OTHER";
        $info['os'] = "OTHER";
        $info['version'] = '';
        $info['agente'] = $_SERVER['HTTP_USER_AGENT'];
        foreach ($browser as $parent) {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/', '', $version);
            if ($s) {
                $info['browser'] = $parent;
                if (isset($version)) $info['version'] = $version;
                else $info['version'] = '';
            }
        }
        foreach ($os as $val) {
            if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false) $info['os'] = $val;
        }
        return $info;
    }
    public static function guardarArchivo($req)
    {
        $archivo = '';
        if ($req->tipo_archivo == 'usuarioFoto') {
            $archivo = 'usuarioFoto';
            $ext = explode('.', $_FILES[$archivo]['name']);
            if ($_FILES[$archivo]['type'] == 'image/png' || $_FILES[$archivo]['type'] == 'image/jpg' || $_FILES[$archivo]['type'] == 'image/jpeg') {
                $contenidoBinario = file_get_contents($_FILES[$archivo]['tmp_name']);
                $imagenComoBase64 = base64_encode($contenidoBinario);
                $archi = Archivos::where('tipo_archivo', $req->tipo_archivo)->where('id_usuario_creacion_archivo', $req->id_usuario)->first();
                if ($archi != '') {
                    $dato = array('archivo' => $imagenComoBase64, 'tipo_archivo' => $req->tipo_archivo, 'id_usuario_modificacion_archivo' => $req->id_usuario, 'ext_archivo' => '.' . $ext[1]);
                    Archivos::where('tipo_archivo', $req->tipo_archivo)->where('id_usuario_creacion_archivo', $req->id_usuario)->update($dato);
                } else {
                    $dato = array(
                        'archivo' => $imagenComoBase64,
                        'tipo_archivo' => $req->tipo_archivo,
                        'id_usuario_creacion_archivo' => $req->id_usuario,
                        'created_at_archivo' => date('Y-m-d H:s:i'),
                        'ext_archivo' => '.' . $ext[1],
                        'id_empresa_archivo' => session('idEmpresa')
                    );
                    Archivos::insert($dato);
                }
                $Base64Img = $imagenComoBase64;
                return 'ok|<img src="data:image/jpeg;base64,' . $Base64Img . '  alt="usuarioFoto_' . $req->id_usuario . '.png"  width="200px" />';
            } else {
                return 'no|El archivo debe ser (png, jpg)';
            }
        } elseif ($req->tipo_archivo == 'productoImagen') {
            $archivo = '';
            foreach ($_FILES as $tex => $val) {
                $archivo = $tex;
            }
            $ext = explode('.', $_FILES[$archivo]['name']);
            if ($_FILES[$archivo]['type'] == 'image/png' || $_FILES[$archivo]['type'] == 'image/jpg' || $_FILES[$archivo]['type'] == 'image/jpeg') {
                $contenidoBinario = file_get_contents($_FILES[$archivo]['tmp_name']);
                $imagenComoBase64 = base64_encode($contenidoBinario);
                $archi = Archivos::where('tipo_archivo', $req->tipo_archivo)
                    ->where('id_usuario_creacion_archivo', $req->id_usuario)
                    ->where('id_producto_archivo', $req->id_producto_archivo)
                    ->where('orden_archivo', $req->fila)
                    ->first();
                if ($archi != '') {
                    $dato = array(
                        'archivo' => $imagenComoBase64,
                        'tipo_archivo' => $req->tipo_archivo,
                        'id_usuario_modificacion_archivo' => $req->id_usuario,
                        'ext_archivo' => '.' . $ext[1],
                        'orden_archivo' => $req->fila
                    );
                    Archivos::where('tipo_archivo', $req->tipo_archivo)
                        ->where('id_usuario_creacion_archivo', $req->id_usuario)
                        ->where('id_empresa_archivo', session('idEmpresa'))
                        ->where('id_producto_archivo', $req->id_producto_archivo)
                        ->update($dato);
                } else {
                    $dato = array(
                        'archivo' => $imagenComoBase64,
                        'tipo_archivo' => $req->tipo_archivo,
                        'id_usuario_creacion_archivo' => $req->id_usuario,
                        'id_usuario_modificacion_archivo' => $req->id_usuario,
                        'created_at_archivo' => date('Y-m-d H:s:i'),
                        'ext_archivo' => '.' . $ext[1],
                        'id_empresa_archivo' => session('idEmpresa'),
                        'id_producto_archivo' => $req->id_producto_archivo,
                        'orden_archivo' => $req->fila
                    );
                    Archivos::insert($dato);
                }
                $Base64Img = $imagenComoBase64;
                return 'ok|<img src="data:image/' . str_replace('.', '', $ext[1]) . ';base64,' . $Base64Img . '"  alt="productoImagen_' . $req->id_usuario . '.png"  width="100px" />';
            } else {
                return 'no|El archivo debe ser (png, jpg)';
            }
        } elseif ($req->tipo_archivo == 'entidadFirma') {
            $archivo = $req->tipo_archivo;
            $ext = explode('.', $_FILES[$archivo]['name']);
            if ($_FILES[$archivo]['type'] == 'application/x-pkcs12') {
                $contenidoBinario = file_get_contents($_FILES[$archivo]['tmp_name']);
                $imagenComoBase64 = base64_encode($contenidoBinario);
                $archi = Archivos::where('tipo_archivo', $req->tipo_archivo)->where('id_empresa_archivo', $req->id_empresa)->first();
                if ($archi != '') {
                    $dato = array('id_empresa_archivo' => $req->id_empresa, 'archivo' => $imagenComoBase64, 'tipo_archivo' => $req->tipo_archivo, 'id_usuario_modificacion_archivo' => session('idUsuario'), 'ext_archivo' => '.' . $ext[1]);
                    Archivos::where('tipo_archivo', $req->tipo_archivo)->where('id_usuario_creacion_archivo', session('idUsuario'))->update($dato);
                } else {
                    $dato = array(
                        'archivo' => $imagenComoBase64,
                        'tipo_archivo' => $req->tipo_archivo,
                        'id_usuario_creacion_archivo' => session('idUsuario'),
                        'created_at_archivo' => date('Y-m-d H:s:i'),
                        'ext_archivo' => '.' . $ext[1],
                        'id_empresa_archivo' => $req->id_empresa
                    );
                    Archivos::insert($dato);
                }
                return 'ok|<label title="Subir firma digital" for="entidadFirma" class="btn btn-primary mb-1" style="cursor: pointer;"><input type="file" id="entidadFirma" name="entidadFirma" style="display:none;"><i class="fa fa-thumbs-up"></i>&nbsp;Firma encontrada</label>';
            } else {
                return 'no|El archivo debe ser (p12)';
            }
        } elseif ($req->tipo_archivo == 'entidadLogo') {
            $archivo = 'entidadLogo';
            $ext = explode('.', $_FILES[$archivo]['name']);
            if ($_FILES[$archivo]['type'] == 'image/png' || $_FILES[$archivo]['type'] == 'image/jpg' || $_FILES[$archivo]['type'] == 'image/jpeg') {
                $contenidoBinario = file_get_contents($_FILES[$archivo]['tmp_name']);
                $imagenComoBase64 = base64_encode($contenidoBinario);
                $archi = Archivos::where('tipo_archivo', $req->tipo_archivo)
                    ->where('id_empresa_archivo', session('idEmpresa'))
                    //->where('id_usuario_creacion_archivo', $req->id_usuario)
                    ->first();
                //dd($archi);
                if ($archi != '') {
                    $dato = array('archivo' => $imagenComoBase64, 'tipo_archivo' => $req->tipo_archivo, 'id_usuario_modificacion_archivo' => $req->id_usuario, 'ext_archivo' => '.' . $ext[1]);
                    Archivos::where('tipo_archivo', $req->tipo_archivo)
                        ->where('id_usuario_creacion_archivo', $req->id_usuario)
                        ->where('id_empresa_archivo', session('idEmpresa'))
                        ->update($dato);
                } else {
                    $dato = array(
                        'archivo' => $imagenComoBase64,
                        'tipo_archivo' => $req->tipo_archivo,
                        'id_usuario_creacion_archivo' => $req->id_usuario,
                        'created_at_archivo' => date('Y-m-d H:s:i'),
                        'ext_archivo' => '.' . $ext[1],
                        'id_empresa_archivo' => session('idEmpresa')
                    );
                    Archivos::insert($dato);
                }
                $Base64Img = $imagenComoBase64;
                //echo $Base64Img;exit;
                return 'ok|<img src="data:image/' . str_replace('.', '', $ext[1]) . ';base64,' . $Base64Img . '"  alt="usuarioFoto_' . $req->id_usuario . '.png"  width="200px" />';
            } else {
                return 'no|El archivo debe ser (png, jpg)';
            }
        } elseif ($req->tipo_archivo == 'clienteCedula') {
            $archivo = $req->tipo_archivo;
            $ext = explode('.', $_FILES[$archivo]['name']);
            if ($_FILES[$archivo]['type'] == 'application/pdf') {
                $contenidoBinario = file_get_contents($_FILES[$archivo]['tmp_name']);
                $imagenComoBase64 = base64_encode($contenidoBinario);
                $archi = Archivos::where('tipo_archivo', $req->tipo_archivo)
                    ->where('id_cliente_archivo', $req->id_cliente)
                    ->where('tipo_archivo', $req->tipo_archivo)
                    ->first();
                if ($archi != '') {
                    $dato = array('id_empresa_archivo' => $req->id_empresa, 'archivo' => $imagenComoBase64, 'tipo_archivo' => $req->tipo_archivo, 'id_usuario_modificacion_archivo' => session('idUsuario'), 'ext_archivo' => '.' . $ext[1]);
                    Archivos::where('tipo_archivo', $req->tipo_archivo)
                        ->where('id_usuario_creacion_archivo', session('idUsuario'))
                        ->where('id_cliente_archivo', $req->id_cliente)
                        ->update($dato);
                } else {
                    $dato = array(
                        'archivo' => $imagenComoBase64,
                        'tipo_archivo' => $req->tipo_archivo,
                        'id_usuario_creacion_archivo' => session('idUsuario'),
                        'created_at_archivo' => date('Y-m-d H:s:i'),
                        'ext_archivo' => '.' . $ext[1],
                        'id_cliente_archivo' => $req->id_cliente
                    );
                    Archivos::insert($dato);
                }
                if ($req->tipo_archivo == 'clienteCedula')
                    return 'ok|<a href="utilidades/viewPdf?id=' . $req->id_cliente . '&t=' . $req->tipo_archivo . '" target="new">&nbsp;<i class="fa fa-search"></i></a>';
                elseif ($req->tipo_archivo == 'entidadRegistroCias')
                    return 'ok|Registro CIAS: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadEstatutos')
                    return 'ok|Estatutos: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadCedula')
                    return 'ok|Cedula Representante: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadVotacion')
                    return 'ok|Certificado de Votación: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
            } else {
                return 'no|El archivo debe ser (pdf)';
            }
        } elseif ($req->tipo_archivo == 'entidadRuc' || $req->tipo_archivo == 'entidadRegistroCias' || $req->tipo_archivo == 'entidadEstatutos' || $req->tipo_archivo == 'entidadCedula' || $req->tipo_archivo == 'entidadVotacion' || $req->tipo_archivo == 'entidadActa') {
            $archivo = $req->tipo_archivo;
            $ext = explode('.', $_FILES[$archivo]['name']);
            if ($_FILES[$archivo]['type'] == 'application/pdf') {
                $contenidoBinario = file_get_contents($_FILES[$archivo]['tmp_name']);
                $imagenComoBase64 = base64_encode($contenidoBinario);
                $archi = Archivos::where('tipo_archivo', $req->tipo_archivo)
                    ->where('id_empresa_archivo', $req->id_empresa)
                    ->where('tipo_archivo', $req->tipo_archivo)
                    ->first();
                if ($archi != '') {
                    $dato = array('id_empresa_archivo' => $req->id_empresa, 'archivo' => $imagenComoBase64, 'tipo_archivo' => $req->tipo_archivo, 'id_usuario_modificacion_archivo' => session('idUsuario'), 'ext_archivo' => '.' . $ext[1]);
                    Archivos::where('tipo_archivo', $req->tipo_archivo)
                        ->where('id_usuario_creacion_archivo', session('idUsuario'))
                        ->where('id_empresa_archivo', $req->id_empresa)
                        ->update($dato);
                } else {
                    $dato = array(
                        'archivo' => $imagenComoBase64,
                        'tipo_archivo' => $req->tipo_archivo,
                        'id_usuario_creacion_archivo' => session('idUsuario'),
                        'created_at_archivo' => date('Y-m-d H:s:i'),
                        'ext_archivo' => '.' . $ext[1],
                        'id_empresa_archivo' => $req->id_empresa
                    );
                    Archivos::insert($dato);
                }
                if ($req->tipo_archivo == 'entidadRuc')
                    return 'ok|RUC: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadRegistroCias')
                    return 'ok|Registro CIAS: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadEstatutos')
                    return 'ok|Estatutos: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadCedula')
                    return 'ok|Cedula Representante: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadVotacion')
                    return 'ok|Certificado de Votación: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
                elseif ($req->tipo_archivo == 'entidadActa')
                    return 'ok|Acta de accionistas: <a href="utilidades/viewPdf?id=' . $req->id_empresa . '&t=' . $req->tipo_archivo . '" target="new">Ver</a>';
            } else {
                return 'no|El archivo debe ser (pdf)';
            }
        }
    }
    public static function getArchivo($tipo = '', $id_usuario = '', $class = '', $w = 0, $h = 0, $archivo = '', $id_empresa = 0, $id_producto = 0)
    {
        if ($id_empresa != 0) {
            $archi = Archivos::where('tipo_archivo', $tipo)
                ->where('id_empresa_archivo', $id_empresa)->first();
        } elseif ($id_producto != 0) {
            $archi = Archivos::where('tipo_archivo', $tipo)
                ->where('id_producto_archivo', $id_producto)
                ->orderBy('orden_archivo')
                ->get();
            $images = '';
            if (count($archi) > 0) {
                foreach ($archi as $row) {
                    //$images .= '<img src="data:image/' . str_replace($row->ext_archivo, '.', '') . ';base64,' . $row->archivo . '"  alt="' . $tipo . '_' . $id_usuario . $row->ext_archivo . '" ' . $class . ' width="100px" height="100px" />|';
                    $images .= '<tr id="tr' . $row->orden_archivo . '">
                    <td>
                        <div id="foto' . $row->orden_archivo . '"><img src="data:image/' . str_replace($row->ext_archivo, '.', '') . ';base64,' . $row->archivo . '"  alt="' . $tipo . '_' . $id_usuario . $row->ext_archivo . '" ' . $class . ' width="100px" /></div>
                    </td>
                    <td>
                        <input type="hidden" id="imagen_producto' . $row->orden_archivo . '" name="imagen_producto' . $row->orden_archivo . '" value="" />
                        <label title="Imagen del producto" for="imagen' . $row->orden_archivo . '" class="ladda-button ladda-button-demo-logo btn btn-primary" data-style="zoom-in" style="cursor:pointer;">
                            <input type="file" accept="image/*" id="imagen' . $row->orden_archivo . '" name="imagen' . $row->orden_archivo . '" onchange="subirImagen(\'' . $row->orden_archivo . '\')" style="display:none;">
                            <i class="fa fa-file-image-o"></i> Imágen
                        </label>
                        <div id="logo' . $row->orden_archivo . '"></div>
                    </td>
                    <td>
                       <a href="javascript:;" class="btn btn-primary" onclick="abrirCrop(\'' . $row->orden_archivo . '\')"><i class="fa fa-crop"></i></a>
                    </td>
                    <td>
                        <input type="text" class="form-control text-right" id="txtorden' . $row->orden_archivo . '" name="txtorden' . $row->orden_archivo . '" value="' . $row->orden_archivo . '">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" id="btneliminar' . $row->orden_archivo . '" onClick="eliminar(\'' . $row->orden_archivo . '\')" ><i class="fa fa-trash"></i> </button>
                    </td>
                    </tr>';
                }
                //$images = substr($images, 0, -1);
                return $images;
            }
        } else {
            $archi = Archivos::where('tipo_archivo', $tipo)
                ->where('id_usuario_creacion_archivo', $id_usuario)->first();
        }
        if ($archi != '') {
            if ($tipo == 'entidadRuc')
                return 'RUC: <a href="utilidades/viewPdf?id=' . $id_empresa . '&t=' . $tipo . '" target="new">Ver</a>';
            elseif ($tipo == 'entidadRegistroCias')
                return 'Registro CIAS: <a href="utilidades/viewPdf?id=' . $id_empresa . '&t=' . $tipo . '" target="new">Ver</a>';
            elseif ($tipo == 'entidadEstatutos')
                return 'Estatutos: <a href="utilidades/viewPdf?id=' . $id_empresa . '&t=' . $tipo . '" target="new">Ver</a>';
            elseif ($tipo == 'entidadCedula')
                return 'Cedula Representante: <a href="utilidades/viewPdf?id=' . $id_empresa . '&t=' . $tipo . '" target="new">Ver</a>';
            elseif ($tipo == 'entidadVotacion')
                return 'Certificado de Votación: <a href="utilidades/viewPdf?id=' . $id_empresa . '&t=' . $tipo . '" target="new">Ver</a>';
            elseif ($tipo == 'entidadFirma') {
                if ($archi != '') {
                    return "data:application/x-pkcs12;base64," . $archi->archivo;
                } else
                    return true;
            } else {
                return '<img src="data:image/' . str_replace('.', '', $archi->ext_archivo) . ';base64,' . $archi->archivo . '"  alt="' . $tipo . '_' . $id_usuario . $archi->ext_archivo . '" ' . $class . ' width="' . $w . 'px" height="' . $h . 'px" />';
            }
        } else {
            if ($tipo == 'entidadFirma') {
                return false;
            } else {
                if ($tipo == 'EntidadLogo')
                    return '<img src="public/img/sinfoto.png"' . $class . ' width="' . $w . 'px" />';
            }
        }
    }
    public static function getarchivoView($id, $tipo = '')
    {
        $buscar = "entidad";
        $resultado = strpos($tipo, $buscar);
        if ($resultado !== FALSE) {
            return Archivos::where('id_empresa_archivo', $id)->where('tipo_archivo', $tipo)->first(['archivo']);
        }
        $buscar = "cliente";
        $resultado = strpos($tipo, $buscar);
        if ($resultado !== FALSE) {
            return Archivos::where('id_cliente_archivo', $id)->where('tipo_archivo', $tipo)->first(['archivo']);
        }
    }
    public static function calcularTiempo()
    {
        $tiempo_inicial = microtime(true);
        for ($i = 0; $i < 100000000; $i++) {
            //
        }
        $tiempo_final = microtime(true);
        $tiempo = $tiempo_final - $tiempo_inicial;

        return number_format($tiempo, 2) . '.sec';
    }
    public static function crearCarpeta($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }
    public static function validarFirma($r)
    {
        try {
            $archivo = Utilidades::getArchivo('entidadFirma', '', '', 0, 0, true, session('idEmpresa'));
            Utilidades::crearCarpeta(base_path() . '/storage/token/' . session('idEmpresa') . '/');
            $f = fopen('storage/token/' . session('idEmpresa') . '/' . session('idEmpresa') . '_token.p12', 'w') or die("Unable to open file!");
            fwrite($f, base64_decode(explode(",", $archivo, 2)[1]));
            fclose($f);
            $clave = $r->clave;
            $config = array('firmar' => true, 'pass' => $clave, 'file' => 'storage/token/' . session('idEmpresa') . '/' . session('idEmpresa') . '_token.p12');
            $firmar = new FirmaElectronica($config);
            $resultado = [];
            openssl_pkcs12_read(file_get_contents($archivo), $resultado, $clave);
            if (count($resultado) > 0) {
                unlink('storage/token/' . session('idEmpresa') . '/' . session('idEmpresa') . '_token.p12');
                return 'Clave y firma correctas';
            } else {
                unlink('storage/token/' . session('idEmpresa') . '/' . session('idEmpresa') . '_token.p12');
                return 'Clave y/o firma incorrectas';
            }
        } catch (Exception $e) {
        }
    }
    public static function impuestoIrbpnr()
    {
        return TipoImpuesto::where("codigo_impuesto_tipo_impuesto", "5")
            ->orWhere("id_tipo_impuesto", "-1")
            ->get();
    }
    public static function impuestoIce()
    {
        return TipoImpuesto::where("tipo_impuesto", '<>', "3")
            ->where("codigo_impuesto_tipo_impuesto", "3")
            ->orWhere("id_tipo_impuesto", "-1")
            ->get();
    }
    public static function impuestoIva($id = '')
    {
        if ($id == '')
            return TipoImpuesto::where("codigo_impuesto_tipo_impuesto", "2")
                ->where("tipo_impuesto", '<>', "3")
                ->orWhere("id_tipo_impuesto", "-1")
                ->get();
        else
            return TipoImpuesto::where("codigo_impuesto_tipo_impuesto", "2")
                ->where("tipo_impuesto", '<>', "3")
                ->orWhere("id_tipo_impuesto", $id)
                ->first();
    }
    public static function TildesHtml($cadena)
    {
        return str_replace(array("á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ"), array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&ntilde;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;"), $cadena);
    }
}
