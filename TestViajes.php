<?php
include 'Viaje.php';
include 'Responsable.php';
include 'Pasajero.php';
include 'Empresa.php';
include 'BaseDeDatos.php';
/**
 * Implementar dentro de la clase TestViajes una operación que permita ingresar, modificar
 *y eliminar la información de la empresa de viajes.
 */
$base = new BaseDatos();

/**
 * Funcion que borra todos los datos de la BD
 */
function limpiarBD($base)
{

    if ($base->Iniciar()) {
        if ($base->Ejecutar("DELETE FROM viaje")) {
            echo "Se limpió tabla Viaje.\n";
        } else {
            echo "No se se limpió tabla viaje.\n";
        }
        if ($base->Ejecutar("DELETE FROM empresa")) {
            echo "Se limpió tabla empresa.\n";
        } else {
            echo "No se limpió tabla empresa.\n";
        }
        if ($base->Ejecutar("DELETE FROM responsable")) {
            echo "Se limpió tabla responsable.\n";
        } else {
            echo "No se limpió tabla responsable.\n";
        }
    } else {
        echo "No se pudo borrar ninguna tabla.\n";
    }
}

/**
 * Funcion que recibe un array y la muestra por pantalla.
 * @param Array $array
 */
function listarArray($array)
{
    $texto = "\n-------------------\n";
    foreach ($array as $item) {
        $texto = $texto . $item->__toString() . "\n";
    }
    echo $texto;
}

//------------------------------------------EMPRESA------------------------------------------//

/**
 * Funcion que pide los datos para crear y cargar una empresa
 */
function insertarEmpresa()
{
    $empresa = new Empresa();
    echo "Ingrese los datos de la empresa:\nNombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Direccion: ";
    $direccion = trim(fgets(STDIN));
    $empresa->cargar($nombre, $direccion);
    if ($empresa->insertar()) {
        echo "Se inserto la empresa.\n";
    } else {
        echo "No se insertó la empresa.\n";
        echo $empresa->getMensaje();
    };
    return $empresa;
}

/**
 * Funcion que recibe una empresa y la modifica en la base de datos
 * @param Empresa $empresa
 */
function modificarEmpresa($empresa)
{
    if ($empresa->modificar()) {
        echo "Se realizo la modificacion con exito.\n";
    } else {
        echo "No se pudo realizar la modificacion.\n";
        $empresa->getMensaje();
    }
}

/**
 * Funcion que recibe una empresa y la elimina de la base de datos
 */
function eliminarEmpresa($empresa)
{
    if ($empresa->eliminar()) {
        echo "Se elimino la empresa con exito.\n";
    } else {
        echo "No se pudo eliminar la empresa.\n";
        echo $empresa->getMensaje();
    }
}

/**
 * Funcion que retorna true o false segun si hay empresas cargadas en la bd
 * @return Array
 * */
function existenEmpresas()
{
    $empresa = new Empresa();
    $empresas = $empresa->listar();
    $hayEmpresasCargadas = sizeof($empresas) > 0;
    return $hayEmpresasCargadas;
}

/**
 * Funcion que retorna los viajes pertenecientes a la empresa que recibe por parametro
 * @param Empresa $empresa
 * @return Array
 */
function viajesDeEmpresa($empresa)
{
    $viaje = new Viaje();
    $condicion = "idempresa = '{$empresa->getIdempresa()}'";
    $viajesDeEmpresa = $viaje->listar($condicion);
    return $viajesDeEmpresa;
}

/**
 * Funcion que elimina todos los viajes de una empresa y sus pasajeros.
 * @param Empresa $empresa
 */
function eliminarViajesEnEmpresa($empresa)
{
    $viaje = new Viaje();
    $condicion = 'idempresa = ' . $empresa->getIdempresa();
    $viajes = $viaje->listar($condicion);
    foreach ($viajes as $itemViaje) {
        eliminarPasajerosEnViaje($itemViaje);
        eliminarViaje($itemViaje);
    }
}

/**
 * Funcion que muestra las opciones disponibles para la tabla empresa
 */
function opcionesEmpresa()
{
    do {
        $empresa = new Empresa();
        echo "\n---------------------------OPCIONES EMPRESA----------------------------
            1) Insertar empresa.
            2) Modificar empresa.
            3) Eliminar empresa.
            4) Listar empresas.
            5) Listar viajes de una empresa.
            0) Volver atras.
            Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                insertarEmpresa();
                break;
            case 2:
                if (existenEmpresas()) {
                    echo "Ingrese el ID de la empresa a modificar: ";
                    $id = trim(fgets(STDIN));
                    if ($empresa->buscar($id)) {
                        opcionesModificarEmpresa($empresa);
                    } else {
                        echo "No existe empresa con el ID ingresado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Ingrese una empresa para continuar.\n";
                }
                break;
            case 3:
                if (existenEmpresas()) {
                    echo "Ingrese el ID de la empresa a eliminar: ";
                    $id = trim(fgets(STDIN));
                    if ($empresa->buscar($id)) {
                        $viajesDeEmpresa = new Viaje();
                        $condicion = 'idempresa = ' . $id;
                        $viajesDeEmpresa = $viajesDeEmpresa->listar($condicion);
                        if (sizeof($viajesDeEmpresa) > 0) {
                            echo "La empresa tiene viajes, desea borrar la empresa, todos sus viajes y pasajeros?(si/no): ";
                            $eleccion = trim(fgets(STDIN));
                            if ($eleccion == 'si') {
                                eliminarViajesEnEmpresa($empresa);
                                eliminarEmpresa($empresa);
                            }
                        } else {
                            eliminarEmpresa($empresa);
                        }
                    } else {
                        echo "No existe empresa con el ID ingresado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Ingrese una empresa para continuar.\n";
                }
                break;
            case 4:
                $empresas = $empresa->listar();
                if (sizeof($empresas) > 0) {
                    listarArray($empresas);
                } else {
                    echo "No hay empresas cargadas.\n";
                }
                break;
            case 5:
                echo "Ingrese el ID de las empresa de la que desea ver sus viajes: ";
                $idEmpresa = trim(fgets(STDIN));
                if ($empresa->buscar($idEmpresa)) {
                    $viajesDeEmpresa = viajesDeEmpresa($empresa);
                    listarArray($viajesDeEmpresa);
                } else {
                    echo "No existe una empresa con el ID solicitado.\n";
                }
                break;
            case 0:
                break;
            default:
                echo "Opcion incorrecta";
        }
    } while ($opcion != 0);
}

/**
 * Funcion que recibe una empresa y muestra las opciones para modificarla
 * @param Empresa $empresa
 */
function opcionesModificarEmpresa($empresa)
{
    do {
        echo "\n------------------MODIFICACIONES EMPRESA--------------------
        1) Nombre.
        2) Direccion. 
        0) Volver atras. \n";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                echo "Ingrese el nuevo nombre: ";
                $nuevo = trim(fgets(STDIN));
                $empresa->setEnombre($nuevo);
                modificarEmpresa($empresa);
                break;
            case 2:
                echo "Ingrese la nueva direccion: ";
                $nuevo = trim(fgets(STDIN));
                $empresa->setEdireccion($nuevo);
                modificarEmpresa($empresa);
                break;
            case 0:
                break;
            default:
                "Opcion incorrecta.\n";
        }
    } while ($opcion != 0);
}
//------------------------------------------VIAJES------------------------------------------//

/**
 * Funcion que pide los datos para crear un viaje, verifica que sean validos 
 * (que no exista otro al mismo destino, que existan la empresa y el responsable) y lo inserta a la bd
 * @return Viaje
 */
function insertarViaje()
{
    $viaje = new Viaje();
    echo "Ingrese los datos del viaje: \n";
    do {
        echo "Destino: ";
        $destino = trim(fgets(STDIN));
        $existeDestino = existeMismoDestino($destino);
        if ($existeDestino) {
            echo "Ya existe un viaje a " . $destino . ", ingrese otro destino.\n";
        }
    } while ($existeDestino);

    echo "Cantidad maxima de pasajeros: ";
    $cantMax = trim(fgets(STDIN));
    do {
        echo "ID de la empresa: ";
        $idEmpresa = trim(fgets(STDIN));
        $empresa = new Empresa();
        $existe = $empresa->buscar($idEmpresa);
        if (!$existe) {
            echo "El ID ingresado no existe.\n";
        }
    } while (!$existe);
    do {
        echo "Numero empleado responsable: ";
        $numresposable = trim(fgets(STDIN));
        $resposable = new Responsable();
        $existe = $resposable->buscar($numresposable);
        if (!$existe) {
            echo "El numero de empleado no existe.\n";
        }
    } while (!$existe);
    echo "Importe: ";
    $importe = trim(fgets(STDIN));
    echo "Tipo asiento: ";
    $tipoAsiento = trim(fgets(STDIN));
    echo "Tiene ida y vuelta: ";
    $idayvuelta = trim(fgets(STDIN));
    $viaje->cargar($destino, $cantMax, $empresa, $resposable, $importe, $tipoAsiento, $idayvuelta);
    if ($viaje->insertar()) {
        echo "Se inserto el viaje.\n";
    } else {
        echo $viaje->getMensaje();
    };
    return $viaje;
}

/**
 * Funcion que recibe un viaje y lo elimina de la bd
 */
function eliminarViaje($viaje)
{
    if ($viaje->eliminar()) {
        echo "Se eliminó el viaje.\n";
    } else {
        echo "No se eliminó el viaje.\n";
        echo $viaje->getMensajeOp();
    };
}

/**
 * Funcion que recibe un viajey lo modifica en la bd
 */
function modificarViaje($viaje)
{
    if ($viaje->modificar()) {
        echo "Se modificó el viaje.\n";
    } else {
        echo "No se modificó el viaje.\n";
        echo $viaje->getMensaje();
    };
}

/**
 * Funcion que recibe un viaje y elimina todos sus pasajeros
 * @param Viaje $viaje
 */
function eliminarPasajerosEnViaje($viaje)
{
    $pasajeros = listadoPasajerosEnViaje($viaje->getIdviaje());
    foreach ($pasajeros as $pasajero) {
        eliminarPasajero($pasajero);
    }
}

/**
 * Funcion que recibe un idViaje y retorna la lista de los pasajeros de este viaje
 * @param int $idViaje
 * @return Array
 */
function listadoPasajerosEnViaje($idViaje)
{
    $pasajero = new Pasajero();
    $condicion = 'idviaje = ' . $idViaje;
    $pasajeros = $pasajero->listar($condicion);
    return $pasajeros;
}

/**
 * Funcion que retorna un boolean segun si quedan asientos disponibles en el viaje que se recibe por parametro
 * @param int $idViaje
 * @return boolean
 */
function hayLugar($idViaje)
{
    $viaje = new Viaje();
    $viaje->buscar($idViaje);
    return sizeof(listadoPasajerosEnViaje($idViaje)) < $viaje->getVcantmaxpasajeros();
}

/**
 * Funcion que retorna un boolean segun si hay viajes cargados en la bd
 * @return boolean
 */
function existenViajes()
{
    $viaje = new Viaje();
    $viajes = $viaje->listar();
    $hayViajesCargados = sizeof($viajes) > 0;
    return $hayViajesCargados;
}

/**
 * Funcion que dado un destino retorna un boolean segun si ya existe otro 
 * @param String $destino
 * @return boolean
 */
function existeMismoDestino($destino)
{
    $viaje = new Viaje();
    $condicion = "vdestino = '{$destino}'";
    $viajesAlDestino = $viaje->listar($condicion);
    $existe = false;
    $i = 0;
    while ($i < sizeof($viajesAlDestino) && !$existe) {
        if ($viajesAlDestino[$i]) {
            $existe = true;
        }
        $i++;
    }
    return $existe;
}

/**
 * Funcion que muestra las opciones para la tabla viaje
 */
function opcionesViaje()
{
    do {
        $viaje = new Viaje();
        echo "\n---------------------------OPCIONES VIAJES----------------------------
            1) Insertar viaje.
            2) Modificar viaje.
            3) Eliminar viaje.
            4) Listar viajes.
            5) Listar Pasajeros de viaje.
            0) Volver atras. 
            Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                if (existenEmpresas() && existenResponsables()) {
                    insertarViaje();
                } else {
                    echo "Opcion no disponible. Inserte una empresa y/o un responsable para continuar.\n";
                }
                break;
            case 2:
                if (existenViajes()) {
                    echo "Ingrese el ID del viaje a modificar: ";
                    $id = trim(fgets(STDIN));
                    if ($viaje->buscar($id)) {
                        opcionesModificarViaje($viaje);
                    } else {
                        echo "No se encontro el viaje con el ID solicitado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Inserte un viaje para continuar.\n";
                }
                break;
            case 3:
                if (existenViajes()) {
                    echo "Ingrese el ID del viaje a eliminar: ";
                    $id = trim(fgets(STDIN));
                    if ($viaje->buscar($id)) {
                        if (sizeOf(listadoPasajerosEnViaje($id)) > 0) {
                            echo "El viaje contiene pasajeros, desea eliminarlo igual?(si/no): ";
                            $eleccion = trim(fgets(STDIN));
                            if ($eleccion == 'si') {
                                eliminarPasajerosEnViaje($viaje);
                                eliminarViaje($viaje);
                            } elseif ($eleccion != 'si' && $eleccion != 'no') {
                                echo "Opcion incorrecta.";
                            }
                        } else {
                            eliminarViaje($viaje);
                        }
                    } else {
                        echo "No se encontro el viaje con el ID solicitado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Inserte un viaje para continuar.\n";
                }
                break;
            case 4:
                $viajes = $viaje->listar();
                if (sizeof($viajes) > 0) {
                    listarArray($viajes);
                } else {
                    echo "No hay viajes cargados.\n";
                }
                break;
            case 5:
                if (existenViajes()) {
                    echo "Ingrese el ID del viaje que desea ver los pasajeros: ";
                    $idViaje = trim(fgets(STDIN));
                    if ($viaje->buscar($idViaje)) {
                        $pasajeros = listadoPasajerosEnViaje($idViaje);
                        if (sizeof($pasajeros) > 0) {
                            listarArray($pasajeros);
                        } else {
                            echo "El viaje no tiene pasajeros.\n";
                        }
                    } else {
                        echo "No se encontro el viaje con el ID solicitado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Inserte un viaje para continuar.\n";
                }
                break;
            case 0:
                break;
            default:
                echo "Opcion incorrecta";
        }
    } while ($opcion != 0);
}

/**
 * Funcion que recibe un viaje y muestra las opciones para modificarlo
 * @param Viaje $viaje
 */
function opcionesModificarViaje($viaje)
{
    do {

        echo "\n------------------MODIFICACIONES VIAJES--------------------
        1) Destino.
        2) Cantidad maxima de pasajeros. 
        3) Empresa.
        4) Responsable.
        5) Importe. 
        6) Tipo de asiento.
        7) Ida y vuelta.
        0) Volver atras.
        Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                echo "Ingrese el nuevo destino: ";
                $nuevo = trim(fgets(STDIN));
                if (!existeMismoDestino($nuevo)) {
                    $viaje->setVdestino($nuevo);
                    modificarViaje($viaje);
                } else {
                    echo "Ya existe un viaje a {$nuevo}\n";
                }

                break;
            case 2:
                echo "Ingrese la nueva cantidad de pasajeros: ";
                $nuevo = trim(fgets(STDIN));
                if (sizeof(listadoPasajerosEnViaje($viaje->getIdViaje())) > $nuevo) {
                    echo "El viaje supera la cantidad de pasajeros, no se realizo la modificacion.\n";
                } else {
                    $viaje->setVcantmaxpasajeros($nuevo);
                    modificarViaje($viaje);
                }
                break;
            case 3:
                echo "Ingrese el ID de la nueva empresa: ";
                $nuevo = trim(fgets(STDIN));
                $nuevaEmpresa = new Empresa();
                if ($nuevaEmpresa->buscar($nuevo)) {
                    $viaje->setObjempresa($nuevaEmpresa);
                    modificarViaje($viaje);
                } else {
                    echo "No se encontro una empresa con el ID buscado.\n";
                }
                break;
            case 4:
                echo "Ingrese el Num. Empleado del nuevo responsable: ";
                $nuevo = trim(fgets(STDIN));
                $nuevoResponsable = new Responsable();
                if ($nuevoResponsable->buscar($nuevo)) {
                    $viaje->setRnumeroempleado($nuevoResponsable);
                    modificarViaje($viaje);
                } else {
                    echo "No se encontro un responsbale con el Num. Empleado buscado.\n";
                }
                break;
            case 5:
                echo "Ingrese el nuevo importe: ";
                $nuevo = trim(fgets(STDIN));
                $viaje->setVimporte($nuevo);
                modificarViaje($viaje);
                break;
            case 6:
                echo "Ingrese el nuevo tipo de asiento: ";
                $nuevo = trim(fgets(STDIN));
                $viaje->setTipoAsiento($nuevo);
                modificarViaje($viaje);
                break;
            case 7:
                echo "Ingrese si tiene ida y vuelta (si/no): ";
                $nuevo = trim(fgets(STDIN));
                $viaje->setIdayvuelta($nuevo);
                modificarViaje($viaje);
                break;
            case 0:
                break;
            default:
                echo "Opcion incorrecta.\n";
        }
    } while ($opcion != 0);
}

//------------------------------------------PASAJEROS------------------------------------------//

/**
 * Funcion que pide los datos para insertar un pasajero y verifica que sean datos validos (el viaje solicitado existe, el documento no se repite)
 */
function insertarPasajero()
{
    $pasajero = new Pasajero();
    echo "Ingrese los datos del pasajero: \n";
    do {
        echo "Documento: ";
        $documento = trim(fgets(STDIN));
        $existe = $pasajero->buscar($documento);
        if ($existe) {
            echo "El Documento ingresado ya existe.\n";
        }
    } while ($existe);
    echo "Nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Apellido: ";
    $apellido = trim(fgets(STDIN));
    echo "Telefono: ";
    $telefono = trim(fgets(STDIN));
    do {
        echo "ID del viaje: ";
        $idViaje = trim(fgets(STDIN));
        $viaje = new Viaje();
        $existe = $viaje->Buscar($idViaje);
        if (!$existe) {
            echo "El ID de viaje ingresado no existe.\n";
        } else {
            $pasajerosEnViaje = listadoPasajerosEnViaje($idViaje);
            $cantMax = $viaje->getVcantmaxpasajeros();
            if (sizeof($pasajerosEnViaje) >= $cantMax) {
                echo "El viaje esta lleno. Elija otro.\n";
                $existe = false;
            }
        }
    } while (!$existe);
    $pasajero->cargar($documento, $nombre, $apellido, $telefono, $viaje);
    if ($pasajero->insertar()) {
        echo "Se inserto el pasajero.\n";
    } else {
        echo "No se inserto el pasajero.\n";
        echo $pasajero->getMensaje() . "\n";
    }
}

/**
 * Funcion que recibe un pasajero y lo modifica en la bd
 * @param Pasajero $pasajero
 */
function modificarPasajero($pasajero)
{
    if ($pasajero->modificar()) {
        echo "Se realizo la modificacion\n";
    } else {
        echo "Ocurrio un error.\n";
        echo $pasajero->getMensaje();
    }
}

/**
 * Funcion que recibe un pasajero y lo elimina en la bd
 * @param Pasajero $pasajero
 */
function eliminarPasajero($pasajero)
{
    if ($pasajero->eliminar()) {
        echo "Pasajero eliminado con exito.\n";
    } else {
        echo "Ocurrio un error al eliminar el pasajero.\n";
        echo $pasajero->getMensaje() . "\n";
    }
}

/**
 * Funcion que retornta un boolean segun si existen pasajeros o no
 * @return boolean
 */
function existenPasajeros()
{
    $pasajero = new Pasajero();
    $pasajeros = $pasajero->listar();
    $hayPasajerosCargados = sizeof($pasajeros) > 0;
    return $hayPasajerosCargados;
}

/**
 * Funcion que muestra las opciones para la tabla pasajeros
 */
function opcionesPasajeros()
{
    do {
        $pasajero = new Pasajero();
        echo "---------------------------OPCIONES PASAJERO----------------------------
            1) Insertar pasajero.
            2) Modificar pasajero.
            3) Eliminar pasajero.
            4) Listar pasajeros.
            0) Salir. 
            Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                if (existenViajes()) {
                    insertarPasajero();
                } else {
                    echo "Opcion no disponible. Inserte un viaje para continuar.\n";
                }
                break;
            case 2:
                if (existenPasajeros()) {
                    echo "Ingrese el documento del pasajero a modificar: ";
                    $documento = trim(fgets(STDIN));
                    if ($pasajero->buscar($documento)) {
                        opcionesModificarPasajero($documento);
                    } else {
                        echo "No se encontro el pasajero con el documento solicitado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Inserte un pasajero para continuar.\n";
                }
                break;
            case 3:
                if (existenPasajeros()) {
                    echo "Ingrese el documento del pasajero a eliminar: ";
                    $documento = trim(fgets(STDIN));
                    if ($pasajero->buscar($documento)) {
                        eliminarPasajero($pasajero);
                    } else {
                        echo "No se encontro el pasajero con el documento solicitado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Inserte un pasajero para continuar.\n";
                }
                break;
            case 4:
                if (existenPasajeros()) {
                    $pasajeros = $pasajero->listar();
                    listarArray($pasajeros);
                } else {
                    echo "No hay pasajeros cargados.\n";
                }
                break;
            case 0:
                break;
            default:
                echo "Opcion incorrecta";
        }
    } while ($opcion != 0);
}

/**
 * Funcion que recibe un dni de un pasajero y muestra las opciones para modificarlo
 * @return int $documento
 */
function opcionesModificarPasajero($documento)
{
    $pasajero = new Pasajero();
    do {
        $pasajero->buscar($documento);
        echo "------------------MODIFICACIONES PASAJERO-------------------- 
            1) Nombre.
            2) Apellido.
            3) Telefono.
            4) Viaje. 
            0) Volver atras.
            Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                echo "Ingrese nuevo nombre: ";
                $nuevoNombre = trim(fgets(STDIN));
                $pasajero->setPnombre($nuevoNombre);
                modificarPasajero($pasajero);
                break;
            case 2:
                echo "Ingrese nuevo apellido: ";
                $nuevoApellido = trim(fgets(STDIN));
                $pasajero->setPapellido($nuevoApellido);
                modificarPasajero($pasajero);
                break;
            case 3:
                echo "Ingrese nuevo telefono: ";
                $nuevoTelefono = trim(fgets(STDIN));
                $pasajero->setPtelefono($nuevoTelefono);
                modificarPasajero($pasajero);
                break;
            case 4:
                echo "Ingrese nuevo ID viaje: ";
                $nuevoID = trim(fgets(STDIN));
                $viaje = new Viaje();
                if ($viaje->buscar($nuevoID)) {
                    if (hayLugar($nuevoID)) {
                        $pasajero->setIdviaje($viaje);
                        modificarPasajero($pasajero);
                    } else {
                        echo "No hay lugar disponible en el viaje solicitado.\n";
                    }
                } else {
                    echo "No existe el viaje con el ID ingresado.\n";
                }
                break;
            case 0:
                break;
            default:
                echo "Opcion incorrecta.\n";
        }
    } while ($opcion != 0);
}

//------------------------------------------RESPONSABLE------------------------------------------//

/**
 * Funcion que pide los datos de un responsable y lo inserta en la bd
 */
function insertarResponsable()
{
    $resposable = new Responsable();
    echo "Ingrese los datos del responsable. \n";
    echo "Numero licencia: ";
    $numLicencia = trim(fgets(STDIN));
    echo "Nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Apellido: ";
    $apellido = trim(fgets(STDIN));
    $resposable->cargar($numLicencia, $nombre, $apellido);
    if ($resposable->insertar()) {
        echo "Responsable insertado con exito.\n";
    } else {
        echo "No se inserto el responsable.\n";
        echo $resposable->getMensaje();
    }
}

/**
 * Funcion que recibe un responsable y lo modifica en la bd
 */
function modificarResponsable($resposable)
{
    if ($resposable->modificar()) {
        echo "Responsable modificado con exito.\n";
    } else {
        echo "No se pudo modificar el responsable.\n";
    }
}

/**
 * Funcion que retorna un boolean segun si hay responsables cargados o no
 * @return boolean
 */
function existenResponsables()
{
    $responsable = new Responsable();
    $responsables = $responsable->listar();
    $hayResponsablesCargados = sizeof($responsables) > 0;
    return $hayResponsablesCargados;
}

/**
 * Funcion que muestra las opciones para la tabla responsable
 */
function opcionesResponsable()
{

    $resposable = new Responsable();
    do {
        echo "------------------OPCIONES RESPONSABLEV--------------------
        1) Insertar responsable.
        2) Modificar responsable. 
        3) Eliminar responsable. 
        4) Listar responssables. 
        0) Volver atras.
        Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                insertarResponsable();
                break;
            case 2:
                if (existenResponsables()) {
                    echo "Ingrese el Num. Empleado del responsable a modificar: ";
                    $numEmpleado = trim(fgets(STDIN));
                    if ($resposable->buscar($numEmpleado)) {
                        opcionesModificarResponsable($numEmpleado);
                    } else {
                        echo "No existe el Num. Empleado solicitado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Inserte un responsable para continuar.\n";
                }

                break;
            case 3:
                if (existenResponsables()) {
                    echo "Ingrese el Num. Empleado del responsable a eliminar: ";
                    $numEmpleado = trim(fgets(STDIN));
                    if ($resposable->buscar($numEmpleado)) {
                        $viaje = new Viaje();
                        $condicion = 'rnumeroempleado = ' . $numEmpleado;
                        $viajesDeResponsable = $viaje->listar($condicion);
                        if (sizeOf($viajesDeResponsable) == 0) {
                            if ($resposable->eliminar()) {
                                echo "Responsable eliminado con exito.\n";
                            } else {
                                echo "No se pudo eliminar el responsable.\n";
                                echo $resposable->getMensaje();
                            }
                        } else {
                            echo "No se puede eliminar un responsable a cargo de viajes.\n";
                        }
                    } else {
                        echo "No existe el Num. Empleado solicitado.\n";
                    }
                } else {
                    echo "Opcion no disponible. Inserte un responsable para continuar.\n";
                }

                break;
            case 4:
                if (existenResponsables()) {
                    $responsables = $resposable->listar();
                    listarArray($responsables);
                } else {
                    echo "No hay responsables cargados.\n";
                }
                break;
            case 0:
                break;
            default:
                echo "Opcion incorrecta.\n";
        }
    } while ($opcion != 0);
}

/**
 * Funcion que recibe el num empleado de un responsable y muestra las opciones para modificar dicho responsable
 * @param int $numEmpleado
 */
function opcionesModificarResponsable($numEmpleado)
{
    $resposable = new Responsable();
    do {
        $resposable->buscar($numEmpleado);
        echo "------------------MODIFICACIONES RESPONSABLEV--------------------
        1) Numero licencia.
        2) Nombre.
        3) Apellido.
        0) Volver atras.
        Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                echo "Nuevo numero licencia: ";
                $nuevo = trim(fgets(STDIN));
                $resposable->setRnumerolicencia($nuevo);
                modificarResponsable($resposable);
                break;
            case 2:
                echo "Nuevo nombre: ";
                $nuevo = trim(fgets(STDIN));
                $resposable->setRnombre($nuevo);
                modificarResponsable($resposable);
                break;
            case 3:
                echo "Nuevo apellido: ";
                $nuevo = trim(fgets(STDIN));
                $resposable->setRapellido($nuevo);
                modificarResponsable($resposable);
                break;
            case 0:
                break;
            default:
                echo "Opcion incorrecta.\n";
        }
    } while ($opcion != 0);
}

//------------------------------------------PROGRAMA PRINCIPAL------------------------------------------//

do {

    echo "---------------------------OPCIONES GENERALES----------------------------
        1) Acceder a tabla Empresas.
        2) Acceder a tabla Viajes.
        3) Acceder a tabla Pasajeros.
        4) Acceder a tabla Responsables.
        5) Vaciar Base de Datos.
        0) Salir.
        Opcion: ";
    $opcion = trim(fgets(STDIN));
    switch ($opcion) {
        case 1:
            opcionesEmpresa();
            break;
        case 2:
            opcionesViaje();
            break;
        case 3:
            opcionesPasajeros();
            break;
        case 4:
            opcionesResponsable();
            break;
        case 5:
            $base = new BaseDatos();
            limpiarBD($base);
            break;
        case 0:
            break;
        default:
            echo "Valor ingresado incorrecto.\n";
    }
} while ($opcion != 0);
