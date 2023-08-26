<?php

namespace App\Models;

use DOMDocument;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Comprobante
{
    private $_datos = null;
    private $tipoDocumento = null;
    private $_empresa = null;
    private $_cliente = null;
    public static $tiposDocumentos = array('01' => 'factura', '04' => 'notaCredito', '03' => 'notaDebito', '06' => 'guiaRemision', '07' => 'comprobanteRetencion');
    public function __construct($documento)
    {
        $this->_datos = $documento;
        $this->tipoDocumento = self::$tiposDocumentos[$documento['tipo_comprobante']];
    }
    function generarXML($reFactura = '')
    {
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $root = $xml->createElement($this->tipoDocumento);
        $root->setAttribute('id', 'comprobante');
        $root->setAttribute('version', '2.1.0');
        $infoTributaria = $this->getInfoTributaria($xml);
        $root->appendChild($infoTributaria);
        switch ($this->tipoDocumento) {
            case 'factura':
                $infoFactura = $this->getInfoFactura($xml);
                $root->appendChild($infoFactura);
                $detalles = $this->getDetalles($xml);
                $root->appendChild($detalles);
                $infoAdicional = $this->getInfoAdicional($xml);
                $root->appendChild($infoAdicional);
                break;
            case 'notaCredito':
                $infoFactura = $this->infoNotaCredito($xml);
                $root->appendChild($infoFactura);
                $detalles = $this->getDetalles($xml);
                $root->appendChild($detalles);
                $infoAdicional = $this->getInfoAdicional($xml);
                $root->appendChild($infoAdicional);
                break;
        }
        $xml->appendChild($root);
        $xml->formatOutput = true;
        $comprobante = $xml->saveXML();
        if ($this->tipoDocumento == 'factura') {
            $carpeta = base_path() . '/storage/FacturaE/' . str_replace('.', '', str_replace(' ', '', strtolower($this->_empresa->razon_social_empresa))) . '/generado/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $carpeta = $carpeta . 'Factura-' . $this->_datos['establecimiento'] . '-' . $this->_datos['emision'] . '-' . str_pad($this->_datos['num_factura'], 9, '0', STR_PAD_LEFT) . '.xml';
        } elseif ($this->tipoDocumento == 'notaCredito') {
            $carpeta =  base_path() . '/storage/NotaCreditoE/' . str_replace('.', '', str_replace(' ', '', strtolower($this->_empresa->razon_social_empresa))) . '/generado/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $carpeta = $carpeta . 'NotaCredito-' . $this->_datos['establecimiento'] . '-' . $this->_datos['emision'] . '-' . str_pad($this->_datos['num_factura'], 9, '0', STR_PAD_LEFT) . '.xml';
        } elseif ($this->tipoDocumento == 'guiaRemision') {
            $carpeta = 'GuiaRemisionE/' . str_replace('.', '', str_replace(' ', '', strtolower($this->empresa->empresa))) . '/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $carpeta = $carpeta . 'GuiaRemision-' . $this->_datos['estab'] . '-' . $this->_datos['ptoEmi'] . '-' . str_pad($this->_datos['secuencial'], 9, '0', STR_PAD_LEFT) . '.xml';
        } elseif ($this->tipoDocumento == 'comprobanteRetencion') {
            $carpeta = 'RetencionE/' . str_replace('.', '', str_replace(' ', '', strtolower($this->empresa->empresa))) . '/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $carpeta = $carpeta . 'Retencion-' . $this->_datos['estab'] . '-' . $this->_datos['ptoEmi'] . '-' . str_pad($this->_datos['secuencial'], 9, '0', STR_PAD_LEFT) . '.xml';
        }
        $flujo = fopen($carpeta, 'w');
        fputs($flujo, $comprobante);
        fclose($flujo);
        $xml->save($carpeta);
        /*echo '<pre>';
        print_r($comprobante);
        DB::rollback();
        exit;*/
        if ($reFactura == '') {
            $arrayDoc = [
                'uuid_archivo' => Uuid::uuid1(),
                'tipo_doc_archivo' => $this->tipoDocumento,
                'estab_archivo' => $this->_datos['establecimiento'],
                'emisi_archivo' => $this->_datos['emision'],
                'secuencial_archivo' => $this->_datos['num_factura'],
                'generado_archivo' => $comprobante,
                'fecha_generado_archivo' => date('Y-m-d H:i:s'),
                'clave_acceso_archivo' => $this->_datos['clave_acceso'],
                'created_at_archivo' => date('Y-m-d H:i:s'),
            ];
            ArchivosElectronicos::insert($arrayDoc);
        } else {
            $arrayDoc = [
                'tipo_doc_archivo' => $this->tipoDocumento,
                'generado_archivo' => $comprobante,
                'estado_archivo' => 'Generado'
            ];
            ArchivosElectronicos::where('clave_acceso_archivo', $this->_datos['clave_acceso'])->update($arrayDoc);
        }
        return $comprobante;
    }
    public function validarXmlToXsd($comprobante)
    {
        $result = array();
        $isValidoXsd = false;
        $mensajes = array();
        $xsd = "";
        switch ($this->tipoDocumento) {
            case 'factura': //'1.1.0'
                $xsd = "public/SriXsd/version1.1.0/factura_1_1_0.xsd";
                break;
            case 'comprobanteRetencion': //1.0.0
                $xsd = "SriXsd/version 1.0.0/comprobanteRetencion.xsd";
                break;
            case 'notaCredito': //1.1.0
                $xsd = "SriXsd/version 1.0.0/notaCredito.xsd";
                break;
            case 'notaDebito': //1.0.0
                $xsd = "\\helpers\\helper\\SriXsd\\version 1.0.0\\notaDebito.xsd";
                break;
            case 'guiaRemision': //1.1.0
                $xsd = "SriXsd/version 1.0.0/guiaRemision.xsd";
                break;
        }
        $doc = new DOMDocument();
        $isValidoXsd = $doc->loadXML($comprobante);
        libxml_clear_errors();
        libxml_use_internal_errors(true);
        $doc->schemaValidate($xsd);
        $errors = libxml_get_errors();
        if ($errors) {
            foreach ($errors as $error) {
                array_push($mensajes, trim("($error->code) $error->message"));
            }
        }
        $result["isValidoXsd"] = $isValidoXsd;
        $result["mensajes"] = $mensajes;
        return $result;
    }
    public function getInfoAdicional($xmlDocument)
    {
        $resolucion = 0;
        $detalles = array();
        if (isset($this->_cliente->nombre_persona))
            $cliente = $this->_cliente->nombre_persona . ' ' . $this->_cliente->apellido_persona;
        if (isset($campos['razonSocialSujetoRetenido']))
            $cliente = $campos['razonSocialSujetoRetenido'];
        if (isset($campos['razonSocialTransportista']))
            $cliente = $campos['razonSocialTransportista'];
        if ($this->_datos['tipo_comprobante'] == '07') {
            if ($cliente != "") array_push($detalles, array("nombre" => 'Proveedor', "valor" => $cliente));
            $direccion = strtolower($this->proveedor->direccion);
            $telefono = $this->proveedor->telefono;
            $telefono = $this->proveedor->telefono;
            $email = strtolower($this->proveedor->email);
        } else {
            $direccion = $this->_cliente->direccion_persona . ' ' . $this->_cliente->villa_persona;
            $telefono = $this->_cliente->telefono_persona;
            $celular = $this->_cliente->celular_persona;
            $email = strtolower($this->_cliente->email_persona);
            $resolucion = $this->_empresa->_empresa;
        }
        if ($email != "") array_push($detalles, array("nombre" => 'Email', "valor" => strtolower($email)));
        if ($telefono != "") array_push($detalles, array("nombre" => 'Teléfono', "valor" => str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $telefono)))) . ' - ' . str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $celular))))));
        if ($direccion != "") array_push($detalles, array("nombre" => 'Dirección', "valor" => $direccion));
        if ($resolucion != 0) array_push($detalles, array("nombre" => 'Agente Retención', "valor" => 'NAC-DNCRASC20-00000001'));
        if (isset($this->_datos['adicionales']['nombre']) && count($this->_datos['adicionales']['nombre']) > 0) {
            for ($i = 0; $i < count($this->_datos['adicionales']['nombre']); $i++) {
                array_push($detalles, ['nombre' => $this->_datos['adicionales']['nombre'][$i], 'valor' => $this->_datos['adicionales']['valor'][$i]]);
            }
        }
        $nodoDetalle = $xmlDocument->createElement('infoAdicional');
        $count = 0;
        foreach ($detalles as $item) {
            $count++;
            if ($count > 6) break;
            $campoAdicional = $xmlDocument->createElement('campoAdicional', isset($item["valor"]) ? str_replace("'", "", str_replace('&', '&amp;', $item["valor"])) : 'NN');
            $campoAdicional->setAttribute('nombre', $item["nombre"]);
            $nodoDetalle->appendChild($campoAdicional);
        }
        return $nodoDetalle;
    }
    public function getDetalles($xmlDocument)
    {
        $nodoDetalle = $xmlDocument->createElement('detalles');
        $detalle = $this->_datos["detalles"];
        $cont = 0;
        foreach ($detalle as $row) {
            $item = $xmlDocument->createElement('detalle');
            if ($this->_datos['codDoc'] == '04') {
                $item->appendChild($xmlDocument->createElement('codigoInterno', $row["codigo_producto"]));
                $item->appendChild($xmlDocument->createElement('codigoAdicional', $row["codigo_producto"]));
            } elseif ($this->_datos['codDoc'] == '01') {
                $item->appendChild($xmlDocument->createElement('codigoPrincipal', $row["codigo_producto"]));
                $item->appendChild($xmlDocument->createElement('codigoAuxiliar', $row["codigo_producto"]));
            }
            $item->appendChild($xmlDocument->createElement('descripcion', str_replace('&', 'y', $row["descripcion"])));
            $item->appendChild($xmlDocument->createElement('cantidad', $row["cantidad"]));
            $item->appendChild($xmlDocument->createElement('precioUnitario', $this->getDecimal($row["p_lista"])));
            $item->appendChild($xmlDocument->createElement('descuento', $this->getDecimal($row["descuento_individual"])));
            $totalSin = ($row['p_lista'] * $row['cantidad']) - $row['descuento_individual'];
            $item->appendChild($xmlDocument->createElement('precioTotalSinImpuesto', $this->getDecimal($totalSin)));
            $detallesAdicionales = $this->getDetallesAdicionales($xmlDocument, $row);
            $item->appendChild($detallesAdicionales);
            $dataImpuesto = [
                'id_producto' => $row['id_producto'],
                'codigo_producto' => $row['codigo_producto'],
                'descripcion' => $row['descripcion'],
                'p_lista' => $row['p_lista'],
                'iva' => $row['iva'],
                'cantidad' => $row['cantidad'],
                'descuento_individual' => $row['descuento_individual'],
                'total_individual' => $row['total_individual'],
                'id_impuesto_iva' => $row['id_impuesto_iva'],
                'id_impuesto_ice' => $row['id_impuesto_ice'],
                'id_impuesto_irbpnr' => $row['id_impuesto_irbpnr'],
                'datAdicional1' => ['datAdicional1'],
                'datAdicional2' => ['datAdicional2'],
                'datAdicional3' => ['datAdicional3']
            ];
            $impuestos = $this->getImpuestos($xmlDocument, $dataImpuesto);
            $item->appendChild($impuestos);
            $nodoDetalle->appendChild($item);
            $cont++;
        }
        return $nodoDetalle;
    }
    public function getImpuestos($xmlDocument, $datos)
    {
        $nodoDetalle = $xmlDocument->createElement('impuestos');
        $row = $datos;
        $item = $xmlDocument->createElement('impuesto');
        $valor = 0;
        $baseImponible = 0;
        $impuestoIva = Utilidades::impuestoIva($row['id_impuesto_iva']);
        $impuestoIce = Utilidades::impuestoIce($row['id_impuesto_ice']);
        $impuestoIrbpnr = Utilidades::impuestoIrbpnr($row['id_impuesto_irbpnr']);
        if ($impuestoIva != '') {
            $codigo = $impuestoIva->codigo_impuesto;
            $codigoPorcentaje = $impuestoIva->codigo;
            $tarifa = $impuestoIva->porcentaje;
            $valor = $row['iva'];
            $baseImponible = (($row['p_lista'] * $row['cantidad']) - $row['descuento_individual']);
        } elseif ($impuestoIce != '') {
            $codigo = $impuestoIva->codigo_impuesto;
            $codigoPorcentaje = $impuestoIva->codigo;
            $tarifa = $impuestoIva->porcentaje;
            $valor = ((($row['p_lista'] * $row['cantidad']) - $row['descuento_individual']) * $impuestoIva->porcentaje) / 100;
            $baseImponible = (($row['p_lista'] * $row['cantidad']) - $row['descuento_individual']);
        } elseif ($impuestoIrbpnr != '') {
            $codigo = $impuestoIva->codigo_impuesto;
            $codigoPorcentaje = $impuestoIva->codigo;
            $tarifa = $impuestoIva->porcentaje;
            $valor = ((($row['p_lista'] * $row['cantidad']) - $row['descuento_individual']) * $impuestoIva->porcentaje) / 100;
            $baseImponible = (($row['p_lista'] * $row['cantidad']) - $row['descuento_individual']);
        }
        $item->appendChild($xmlDocument->createElement('codigo', $codigo));
        $item->appendChild($xmlDocument->createElement('codigoPorcentaje', $codigoPorcentaje));
        $item->appendChild($xmlDocument->createElement('tarifa', $this->getDecimal(number_format($tarifa, 0))));
        $item->appendChild($xmlDocument->createElement('baseImponible', $this->getDecimal($baseImponible)));
        $item->appendChild($xmlDocument->createElement('valor', $this->getDecimal(number_format($valor, 2))));
        $nodoDetalle->appendChild($item);
        return $nodoDetalle;
    }
    public function getDetallesAdicionales($xmlDocument, $row)
    {
        $nodoDetalle = $xmlDocument->createElement('detallesAdicionales');
        for ($i = 1; $i < 4; $i++) {
            $item = $xmlDocument->createElement('detAdicional');
            $nombre = '';
            if (isset($row['datAdicional' . $i])) {
                if ($row['datAdicional' . $i] != '') {
                    $nombre = explode(':', $row['datAdicional' . $i]);
                    $item->setAttribute('nombre', $nombre[0]);
                    $item->setAttribute('valor', $nombre[1]);
                    $nodoDetalle->appendChild($item);
                }
            }
        }
        return $nodoDetalle;
    }
    public function getInfoFactura($xmlDocument)
    {
        $this->_cliente = Clientes::getClientes($this->_datos['id_cliente']);
        $campos['fechaEmision'] = date('d/m/Y', strtotime($this->_datos['fecha_emision']));
        $estab = Establecimientos::getDireccionEstablecimiento($this->_empresa['ruc_empresa'], $this->_datos['establecimiento'], $this->_datos['emision']);
        $campos['dirEstablecimiento'] = $estab->direccion_establecimiento;
        if ($this->_empresa->num_contribuyente_especial_empresa != '')
            $campos['contribuyenteEspecial'] = $this->_empresa->num_contribuyente_especial_empresa;
        if ($this->_empresa->contabilidad_empresa != 0)
            $campos['obligadoContabilidad'] = 'SI';
        else
            $campos['obligadoContabilidad'] = 'NO';
        $campos['tipoIdentificacionComprador'] = $this->_cliente->tipo_identificacion_persona;
        $campos['razonSocialComprador'] = $this->_cliente->nombre_persona . ' ' . $this->_cliente->apellido_persona;
        $campos['identificacionComprador'] = $this->_cliente->identificacion_persona;
        $campos['direccionComprador'] = $this->_cliente->direccion_persona != '' ? $this->_cliente->direccion_persona : 'NN';
        $campos['totalSinImpuestos'] = $this->getDecimal(number_format($this->_datos['total'], 2));
        $campos['totalDescuento'] = $this->_datos['descuento'];
        $campos_['totalConImpuestos'] = [];
        $totalConImpuestos = $xmlDocument->createElement('totalConImpuestos');
        $d = $this->_datos["totalConImpuestos"];
        $item = $xmlDocument->createElement('totalImpuesto');
        foreach ($d as $row) {
            $item = $xmlDocument->createElement('totalImpuesto');
            $impuestoIva = Utilidades::impuestoIva($row['id_impuesto_iva']);
            $item->appendChild($xmlDocument->createElement('codigo', $impuestoIva->codigo_impuesto));
            $item->appendChild($xmlDocument->createElement('codigoPorcentaje', number_format($impuestoIva->codigo, 0)));
            $item->appendChild($xmlDocument->createElement('descuentoAdicional', $row['descuento_adicional']));
            $item->appendChild($xmlDocument->createElement('baseImponible', $this->getDecimal($row['total'])));
            $item->appendChild($xmlDocument->createElement('valor', $this->getDecimal(number_format($row['valor'], 2))));
        }
        $totalConImpuestos->appendChild($item);
        $nodoDetalle = $xmlDocument->createElement('infoFactura');
        foreach ($campos as $tex => $val) {
            $nodoDetalle->appendChild($xmlDocument->createElement($tex, $val));
        }
        $nodoDetalle->appendChild($totalConImpuestos);
        $nodoDetalle->appendChild($xmlDocument->createElement('propina', $this->getDecimal($this->_datos['propina'])));
        $nodoDetalle->appendChild($xmlDocument->createElement('importeTotal', $this->getDecimal($this->_datos['total_pagar'])));
        $nodoDetalle->appendChild($xmlDocument->createElement('moneda', 'DOLAR'));
        $nodoDetalle->appendChild($this->formaPago($xmlDocument));
        return $nodoDetalle;
    }
    public function infoNotaCredito($xmlDocument)
    {
        $estab = Establecimientos::getDireccionEstablecimiento($this->_empresa['ruc_empresa'], $this->_datos['establecimiento'], $this->_datos['emision']);
        $this->_cliente = Clientes::getClientes($this->_datos['id_cliente']);
        $nodoDetalle = $xmlDocument->createElement('infoNotaCredito');
        $campos['fechaEmision'] = date('d/m/Y', strtotime($this->_datos['fecha_emision']));
        $campos['dirEstablecimiento'] = $estab->direccion_establecimiento;
        $campos['tipoIdentificacionComprador'] = $this->_cliente->tipo_identificacion_cliente;
        $campos['razonSocialComprador'] = $this->_cliente->nombre_cliente . ' ' . $this->_cliente->apellido_cliente;
        $campos['identificacionComprador'] = $this->_cliente->identificacion_cliente;
        if ($this->_empresa->num_contribuyente_especial_empresa != '')
            $campos['contribuyenteEspecial'] = $this->_empresa->num_contribuyente_especial_empresa;
        if ($this->_empresa->contabilidad_empresa != 0)
            $campos['obligadoContabilidad'] = 'SI';
        else
            $campos['obligadoContabilidad'] = 'NO';
        $campos['codDocModificado'] = '01';
        $campos['numDocModificado'] = $this->_datos['num_documento_modificado_ventash'];
        $campos['fechaEmisionDocSustento'] = date('d/m/Y', strtotime($this->_datos['fecha_emision_modificado_ventash']));
        $campos['totalSinImpuestos'] = $this->_datos['total'];
        $campos['valorModificacion'] = $this->_datos['total_pagar'];
        $campos['moneda'] = 'DOLAR';
        foreach ($campos as $tex => $val) {
            $nodoDetalle->appendChild($xmlDocument->createElement($tex, $val));
        }
        $campos_['totalConImpuestos'] = [];
        $totalConImpuestos = $xmlDocument->createElement('totalConImpuestos');
        $d = $this->_datos["totalConImpuestos"];
        $item = $xmlDocument->createElement('totalImpuesto');
        foreach ($d as $row) {
            $item = $xmlDocument->createElement('totalImpuesto');
            $impuestoIva = Utilidades::impuestoIva($row['id_impuesto_iva']);
            $item->appendChild($xmlDocument->createElement('codigo', $impuestoIva->codigo_impuesto));
            $item->appendChild($xmlDocument->createElement('codigoPorcentaje', number_format($impuestoIva->codigo, 0)));
            $item->appendChild($xmlDocument->createElement('baseImponible', $this->getDecimal($row['total'])));
            $item->appendChild($xmlDocument->createElement('valor', $this->getDecimal(number_format($row['valor'], 2))));
        }
        $totalConImpuestos->appendChild($item);
        $nodoDetalle->appendChild($totalConImpuestos);
        $nodoDetalle->appendChild($xmlDocument->createElement('motivo', $this->_datos['motivo']));
        return $nodoDetalle;
    }
    public function formaPago($xmlDocument)
    {
        $nodeFormaPago = $xmlDocument->createElement('pagos');
        $item = $xmlDocument->createElement('pago');
        $formaPago = $this->_datos['forma_pago'];
        for ($i = 0; $i < count($formaPago); $i++) {
            $item->appendChild($xmlDocument->createElement('formaPago', $formaPago[$i]['forma_pago']));
            $item->appendChild($xmlDocument->createElement('total', $this->getDecimal($this->_datos['total_pagar'])));
            $item->appendChild($xmlDocument->createElement('plazo', $this->_datos["tiempo_credito"]));
            $item->appendChild($xmlDocument->createElement('unidadTiempo', 'dias'));
            $nodeFormaPago->appendChild($item);
        }
        return $nodeFormaPago;
    }
    public function getInfoTributaria($xmlDocument)
    {
        $campos = [];
        $campos['ambiente'] = $this->_datos['ambiente'];
        $campos['tipoEmision'] = $this->_datos['tipo_emision'];
        $this->_empresa = Empresas::getEmpresasRuc($this->_datos['ruc']);
        $campos['razonSocial'] = $this->_empresa->razon_social_empresa;
        $campos['nombreComercial'] = $this->_empresa->nombre_comercial_empresa;
        $campos['ruc'] = $this->_empresa->ruc_empresa;
        $campos['claveAcceso'] = $this->_datos['clave_acceso'];
        $campos['codDoc'] = $this->_datos['tipo_comprobante'];
        $campos['estab'] = $this->_datos['establecimiento'];
        $campos['ptoEmi'] = $this->_datos['emision'];
        $campos['secuencial'] = str_pad($this->_datos['num_factura'], 9, "0", STR_PAD_LEFT);
        $campos['dirMatriz'] = $this->_empresa->direccion_matriz_empresa;
        $nodoDetalle = $xmlDocument->createElement('infoTributaria');
        foreach ($campos as $tex => $val) {
            $nodoDetalle->appendChild($xmlDocument->createElement($tex, $val));
        }
        if ($this->_empresa->agente_retencion_empresa == 1)
            $nodoDetalle->appendChild($xmlDocument->createElement('agenteRetencion', "201"));
        elseif ($this->_empresa->rimpe_emprendedor_empresa == 1)
            $nodoDetalle->appendChild($xmlDocument->createElement('contribuyenteRimpe', "CONTRIBUYENTE RÉGIMEN RIMPE EMPRENDEDOR"));
        elseif ($this->_empresa->rimpe_popular_empresa == 1)
            $nodoDetalle->appendChild($xmlDocument->createElement('contribuyenteRimpe', "CONTRIBUYENTE RÉGIMEN RIMPE NEGOCIO POPULAR"));
        return $nodoDetalle;
    }
    function htmlspecial($valor)
    {
        $result = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
        return $result;
    }
    function getDecimal($valor = 0, $digitos = 2, $formato = false)
    {
        if (trim($valor) == "") $valor = 0;
        $resultado = round($valor, $digitos, PHP_ROUND_HALF_UP);
        $resultado = number_format($resultado, $digitos, ".", $formato ? "," : "");
        return $resultado;
    }
    function getDate($valor)
    {
        if (trim($valor) == "") return "";
        $result = date('d/m/Y', strtotime($valor));
        return $result;
    }
    function getBool($valor)
    {
        $result = $valor == true || $valor == 1 || $valor == "1" ? "SI" : "NO";
        return $result;
    }
}
