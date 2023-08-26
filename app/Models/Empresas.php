<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Empresas extends Model
{
    protected $table = 'db_empresas';
    protected $primaryKey  = 'id_empresa';
    const CREATED_AT = 'created_at_empresa';
    const UPDATED_AT = 'updated_at_empresa';

    private static $modelo = 'Empresas';
    public static function importarentidades()
    {
        DB::beginTransaction();
        try {
            $empresaImp = DB::connection('FAC')
                ->table('bm_entidad as e')
                ->get();
            $rutaToken = 'https://www.facturalgo.com/app/tokens/';
            $rutaLogo = 'https://www.facturalgo.com/app/images/entidad/';
            if (count($empresaImp) > 0) {
                foreach ($empresaImp as $row) {
                    $empresa = Empresas::where('ruc_empresa', $row->ruc_empresa)->first();
                    if ($empresa == '') {
                        $tipo_regimen = '';
                        if ($row->rimpe_emprendedor == 1) {
                            $catalogo = Catalogos::where('codigo_catalogo', 'rimpeemprendedor')->first();
                            $tipo_regimen = $catalogo->id_catalogo;
                        } elseif ($row->rimpe_popular == 1) {
                            $catalogo = Catalogos::where('codigo_catalogo', 'rimpepopular')->first();
                            $tipo_regimen = $catalogo->id_catalogo;
                        } else {
                            $tipo_regimen = 0;
                        }
                        $arrayEmpresa = [
                            'uuid_empresa' => Uuid::uuid1(),
                            'razon_social_empresa' => $row->razon_social == '' ? $row->nombre_comercial : $row->razon_social,
                            'nombre_comercial_empresa' => $row->nombre_comercial,
                            'ruc_empresa' => $row->ruc_empresa,
                            'direccion_matriz_empresa' => $row->direccion,
                            'telefono_empresa' => $row->telefono,
                            'celular_empresa' => $row->fax,
                            'id_tipo_empresa' => 7,
                            'id_tipo_negocio_empresa' => 120,
                            'email_empresa' => $row->email,
                            'representante_empresa' => $row->representante,
                            'identificacion_representante_empresa' => $row->ci_representante,
                            'contador_empresa' => $row->contador,
                            'identificacion_contador_empresa' => $row->ci_contador,
                            'estado_empresa' => $row->estado == 'T' ? 1 : 0,
                            'fecha_inicio_empresa' => $row->fecha_inicio,
                            'nombre_corto_empresa' => $row->nombre_corto,
                            'verificacion_empresa' => $row->verificado == 'T' ? 1 : 0,
                            'actividad_empresa' => $row->actividad,
                            'id_ambiente_empresa' => $row->id_tipo_ambiente,
                            'id_emision_empresa' => $row->id_tipo_emision,
                            'agente_retencion_empresa' => $row->agente_retencion,
                            'num_resolucion_empresa' => $row->num_resolucion,
                            'num_contribuyente_especial_empresa' => $row->num_contribuyente_especial,
                            'eliminado_empresa' => $row->eliminado,
                            'clave_token_empresa' => $row->clave_token,
                            'id_moneda_empresa' => 4,
                            'id_ciudad_empresa' => $row->id_ciudad,
                            'contabilidad_empresa' => $row->contabilidad == 'T' ? 1 : 0,
                            'id_tipo_regimen_empresa' => $tipo_regimen,
                            'id_usuario_creacion_empresa' => session('idUsuario'),
                            'id_usuario_modificacion_empresa' => session('idUsuario'),
                        ];
                        $idEmp = Empresas::insertGetId($arrayEmpresa);
                        try {
                            if ($row->ruta_token != '') {
                                $remote_file_url = $rutaToken . $row->ruta_token;
                                $contenidoBinario = file_get_contents($remote_file_url);
                                $imagenComoBase64 = base64_encode($contenidoBinario);
                                $ext = explode('.', $row->ruta_token);
                                $dato = array(
                                    'archivo' => $imagenComoBase64,
                                    'tipo_archivo' => 'entidadFirma',
                                    'id_usuario_creacion_archivo' => session('idUsuario'),
                                    'created_at_archivo' => date('Y-m-d H:s:i'),
                                    'ext_archivo' => '.' . $ext[1],
                                    'id_empresa_archivo' => $idEmp
                                );
                                Archivos::insert($dato);
                            }
                        } catch (Exception $e) {
                        }
                        //Imagen
                        try {
                            if ($row->imagen != '') {
                                $remote_file_url = $rutaLogo . $row->imagen;
                                $contenidoBinario = file_get_contents($remote_file_url);
                                $imagenComoBase64 = base64_encode($contenidoBinario);
                                $ext = explode('.', $row->imagen);
                                $dato = array(
                                    'archivo' => $imagenComoBase64,
                                    'tipo_archivo' => 'entidadLogo',
                                    'id_usuario_creacion_archivo' => session('idUsuario'),
                                    'created_at_archivo' => date('Y-m-d H:s:i'),
                                    'ext_archivo' => '.' . $ext[1],
                                    'id_empresa_archivo' => $idEmp
                                );
                                Archivos::insert($dato);
                            }
                        } catch (Exception $e) {
                        }

                        if ($idEmp != '') {
                            echo 'Importacion: Correcta</br>';
                            echo 'Ruc: ' . $row->ruc_empresa . '</br>';
                            echo 'Razon social: ' . $row->razon_social . '</br>';
                            echo 'Nombre comercial: ' . $row->nombre_comercial . '</br>';
                        } else {
                            echo 'Importacion: Con errores</br>';
                            echo 'Ruc: ' . $row->ruc_empresa . '</br>';
                            echo 'Razon social: ' . $row->razon_social . '</br>';
                            echo 'Nombre comercial: ' . $row->nombre_comercial . '</br>';
                        }
                    }
                }
                echo 'Fin de la importación';
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            echo 'Error: ' . $e->getMessage() . '<br/>';
            echo 'Línea: ' . $e->getLine() . '<br/>';
        }
    }
    public static function getDatosFirma($id)
    {
        $empresa = Empresas::getEmpresas($id);
        $archivo = Utilidades::getArchivo('entidadFirma', '', '', 0, 0, true, $empresa->id_empresa);
        $arrayDatos = [];
        if ($archivo != '') {
            Utilidades::crearCarpeta(base_path() . '/storage/token/' . $empresa->id_empresa . '/');
            $f = fopen('storage/token/' . $empresa->id_empresa . '/' . $empresa->id_empresa . '_token.p12', 'w') or die("Unable to open file!");
            fwrite($f, base64_decode(explode(",", $archivo, 2)[1]));
            fclose($f);
            $clave = $empresa->clave_token_empresa;
            $config = array('firmar' => true, 'pass' => $clave, 'file' => 'storage/token/' . $empresa->id_empresa . '/' . $empresa->id_empresa . '_token.p12');
            $firmar = new FirmaElectronica($config);
            $datos = $firmar->getData();
            $nombre = isset($datos['subject']['CN']) ? $datos['subject']['CN'] : '';
            $email = isset($datos['subject']['emailAddress']) ? str_replace('email:', '', $datos['subject']['emailAddress']) : (isset($datos['extensions']['subjectAltName']) ? str_replace('email:', '', $datos['extensions']['subjectAltName']) : '');
            $desde = isset($datos['validFrom_time_t']) ? date('Y-m-d H:i:s', $datos['validFrom_time_t']) : '';
            $desde_ = isset($datos['validFrom_time_t']) ? date('Y-m-d H:i:s', $datos['validFrom_time_t']) : '';
            $hasta = isset($datos['validTo_time_t']) ? date('Y-m-d H:i:s', $datos['validTo_time_t']) : '';
            $start = new \DateTime($desde);
            $end = new \DateTime($hasta);
            $diff = $start->diff($end);
            $dias = $diff->format('%a');
            $desde = date('Y-m-d H:i:s');
            $start = new \DateTime($desde);
            $diff = $start->diff($end);
            $expiracion = $diff->format('%a');
            $when = date('Y-m-d') . ' 00:00:00';
            $vigente = $hasta > $when;
            $emisior = isset($datos['issuer']['CN']) ? $datos['issuer']['CN'] : '';
            $arrayDatos = [
                'Nombre' => $nombre,
                'Email' => $email,
                'Desde' => $desde_,
                'Hasta' => $hasta,
                'Dias' => $dias,
                'Expiracion' => $vigente == 1 ? $expiracion : $expiracion * -1,
                'Vigente' => $vigente == 1 ? 'VIGENTE' : 'CADUCADO',
                'Emisor' => $emisior,
            ];
            try {
                unlink('storage/token/' . session('idEmpresa') . '/' . session('idEmpresa') . '_token.p12');
            } catch (Exception $e) {
            }
        } else {
            $arrayDatos = [
                'Nombre' => 'Sin Firma',
                'Email' => 'Sin Firma',
                'Desde' => 'Sin Firma',
                'Hasta' => 'Sin Firma',
                'Dias' => 'Sin Firma',
                'Expiracion' => 'Sin Firma',
                'Vigente' => 'Sin Firma',
                'Emisor' => 'Sin Firma',
            ];
        }
        return $arrayDatos;
    }
    public static function asignarEmpresas($r)
    {
        $arrayEmpresas = [
            'id_empresas_usuario' => json_encode($r->empresas)
        ];
        Usuarios::where('id_usuario', session('idUsuario'))
            ->update($arrayEmpresas);
        return 'ok';
    }
    public static function saveEmpresa($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            if ($datos['id_empresa'] != '') {
                $cont++;
                $emp = Empresas::where('id_empresa', $datos['id_empresa'])
                    ->first(['uuid_empresa']);
                if ($emp->uuid_empresa == '')
                    $datos['uuid_empresa'] = Uuid::uuid1();
                Empresas::where('id_empresa', $datos['id_empresa'])->update($datos);
                $r->c = 'entidad';
                $r->s = 'saveEntidad';
                $r->d = $origin['d'];
                $r->m = Empresas::$modelo;
                $r->o = 'Se actualizo le entidad No.: ' . $datos['id_empresa'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_empresa'] = Uuid::uuid1();
                Empresas::insert($datos);
                $id = Empresas::latest('id_empresa')->first('id_empresa');
                $r->c = 'entidad';
                $r->s = 'saveEntidad';
                $r->d = $origin['d'];
                $r->m = Empresas::$modelo;
                $r->o = 'Se creo una entidad';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $r->c = 'entidad';
            $r->s = 'saveEntidad';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = Empresas::$modelo;
            $r->o = 'Error al guardar la entidad: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage() . '|Linea: ' . $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function cambiarEstadoEmpresa($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $arrayEmpresa = [
                'estado_empresa' => $r->estado
            ];
            Empresas::where('id_empresa', $r->id)->update($arrayEmpresa);
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            DB::commit();
            $cont++;
        } catch (Exception $e) {
            DB::rollback();
            $r->c = 'empresas';
            $r->s = 'cambiarEstadoEmpresa';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = Empresas::$modelo;
            $r->o = 'Error al actualizar la empresa: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'no|' . $e->getMessage() . '|Linea: ' . $e->getLine()
            ];
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'ok|Datos guardados correctamente...'
            ];
            return json_encode($result);
        }
    }
    public static function getIdEmpresa($ruc)
    {
        $id = Empresas::where('ruc_empresa', $ruc)->first(['id_empresa']);
        return $id->id_empresa;
    }
    public static function setEmpresasAsignadas($r)
    {
        $arrayEmpresas = [
            'id_empresas_usuario' => json_encode($r->ids)
        ];
        Usuarios::where('id_usuario', session('idUsuario'))->update($arrayEmpresas);
    }
    public static function seleccionarEmpresa($r)
    {
        try {
            session([
                'idEmpresa' => $r->id,
            ]);
        } catch (Exception $e) {
            session([
                'idEmpresa' => '',
            ]);
        }
        return json_encode(['id' => session('idEmpresa')]);
    }
    public static function traerEmpresas($ids)
    {
        return Empresas::whereIn('id_empresa', json_decode($ids))->get([
            'id_empresa',
            'nombre_corto_empresa',
            'estado_empresa',
            'verificacion_empresa'
        ]);
    }
    public static function getEmpresas($id = '', $seleccionar = '', $id_empresas = null)
    {
        if ($id == '') {
            return Empresas::leftjoin('db_archivos as lo', function ($q) {
                $q->on('id_empresa', '=', 'lo.id_empresa_archivo')
                    ->where('lo.tipo_archivo', '=', 'entidadLogo');
            })
                ->where(function ($w) use ($seleccionar, $id_empresas) {
                    if ($seleccionar != '') {
                        $empresas = json_decode($id_empresas);
                        $w->whereIn('id_empresa', $empresas);
                    }
                })
                ->get([
                    'lo.archivo as entidadLogo',
                    'lo.ext_archivo as ext_archivo_empresa',
                    'id_empresa',
                    'razon_social_empresa',
                    'nombre_comercial_empresa',
                    'ruc_empresa',
                    'direccion_matriz_empresa',
                    'telefono_empresa',
                    'celular_empresa',
                    'estado_empresa',
                    'fecha_inicio_empresa',
                    'email_empresa',
                    'id_tipo_negocio_empresa',
                    'representante_empresa',
                    'identificacion_representante_empresa',
                    'contador_empresa',
                    'identificacion_contador_empresa',
                    'nombre_corto_empresa',
                    'id_ambiente_empresa',
                    'id_emision_empresa',
                    'num_resolucion_empresa',
                    'num_contribuyente_especial_empresa',
                    'actividad_empresa',
                    'contabilidad_empresa',
                    'id_moneda_empresa',
                    'id_tipo_empresa',
                    'verificacion_empresa'
                ]);
        } else {
            return Empresas::leftjoin('db_archivos as a', function ($q) {
                $q->on('id_empresa', '=', 'a.id_empresa_archivo')
                    ->where('a.tipo_archivo', '=', 'entidadFirma');
            })
                ->leftjoin('db_archivos as r', function ($q) {
                    $q->on('id_empresa', '=', 'r.id_empresa_archivo')
                        ->where('r.tipo_archivo', '=', 'entidadRuc');
                })
                ->leftjoin('db_archivos as rcia', function ($q) {
                    $q->on('id_empresa', '=', 'rcia.id_empresa_archivo')
                        ->where('rcia.tipo_archivo', '=', 'entidadRegistroCias');
                })
                ->leftjoin('db_archivos as es', function ($q) {
                    $q->on('id_empresa', '=', 'es.id_empresa_archivo')
                        ->where('es.tipo_archivo', '=', 'entidadEstatutos');
                })
                ->leftjoin('db_archivos as ce', function ($q) {
                    $q->on('id_empresa', '=', 'ce.id_empresa_archivo')
                        ->where('ce.tipo_archivo', '=', 'entidadCedula');
                })
                ->leftjoin('db_archivos as vo', function ($q) {
                    $q->on('id_empresa', '=', 'vo.id_empresa_archivo')
                        ->where('vo.tipo_archivo', '=', 'entidadVotacion');
                })
                ->leftjoin('db_archivos as lo', function ($q) {
                    $q->on('id_empresa', '=', 'lo.id_empresa_archivo')
                        ->where('lo.tipo_archivo', '=', 'entidadLogo');
                })
                ->leftjoin('db_archivos as ac', function ($q) {
                    $q->on('id_empresa', '=', 'ac.id_empresa_archivo')
                        ->where('ac.tipo_archivo', '=', 'entidadActa');
                })
                ->leftjoin('db_ciudades as c', 'db_empresas.id_ciudad_empresa', '=', 'c.id_ciudad')
                ->leftjoin('db_provincias as pr', 'c.id_provincia_ciudad', '=', 'pr.id_provincia')
                ->leftjoin('db_paises as pa', 'pr.id_pais_provincia', '=', 'pa.id_pais')
                ->leftjoin('db_catalogos as ca', 'db_empresas.id_tipo_negocio_empresa', '=', 'ca.id_catalogo')
                ->leftjoin('db_catalogos as tipoRegimen', 'db_empresas.id_tipo_regimen_empresa', '=', 'tipoRegimen.id_catalogo')
                ->where('id_empresa', $id)->first([
                    'id_empresa',
                    'uuid_empresa',
                    'razon_social_empresa',
                    'nombre_comercial_empresa',
                    'ruc_empresa',
                    'direccion_matriz_empresa',
                    'telefono_empresa',
                    'celular_empresa',
                    'ca.nombre_catalogo as tipo_negocio_empresa',
                    'estado_empresa',
                    'fecha_inicio_empresa',
                    'c.id_ciudad as id_ciudad_empresa',
                    'pr.id_provincia',
                    'pa.id_pais',
                    'email_empresa',
                    'id_tipo_negocio_empresa',
                    'representante_empresa',
                    'identificacion_representante_empresa',
                    'contador_empresa',
                    'identificacion_contador_empresa',
                    'nombre_corto_empresa',
                    'id_ambiente_empresa',
                    'id_emision_empresa',
                    'num_resolucion_empresa',
                    'num_contribuyente_especial_empresa',
                    'actividad_empresa',
                    'contabilidad_empresa',
                    'id_moneda_empresa',
                    'id_tipo_empresa',
                    'id_tipo_regimen_empresa',
                    'tipoRegimen.nombre_catalogo as tipoRegimen',
                    'id_usuario_creacion_empresa',
                    'id_usuario_modificacion_empresa',
                    'referencia_direccion_empresa',
                    'a.id_archivo as id_archivoFirma',
                    'r.id_archivo as id_archivoRuc',
                    'rcia.id_archivo as id_archivoRcia',
                    'es.id_archivo as id_archivoEstatuto',
                    'ce.id_archivo as id_archivoCedula',
                    'vo.id_archivo as id_archivoVotacion',
                    'ac.id_archivo as id_archivoActa',
                    'lo.archivo as entidadLogo',
                    'lo.ext_archivo as ext_archivo_empresa',
                    'clave_token_empresa',
                    'db_empresas.urbanizacion_empresa',
                    'db_empresas.etapa_empresa',
                    'db_empresas.mz_empresa',
                    'db_empresas.villa_empresa'
                ]);
        }
    }
    public function establecimientos()
    {
        return $this->hasMany(Establecimientos::class, 'id_empresa_establecimiento', 'id_empresa');
    }
    public static function getEmpresasRuc($ruc)
    {
        $empresa = Empresas::where('ruc_empresa', $ruc)->first();
        return $empresa;
    }
}
