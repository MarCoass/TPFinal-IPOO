<?php

class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $idempresa; //OBJETO EMPRESA
    private $rnumeroempleado; // OBJETO RESPONSABLE
    private $vimporte;
    private $tipoAsiento;
    private $idayvuelta;
    private $mensaje;

    //constructor
    public function __construct()
    {
        $this->idviaje = '';
        $this->vdestino = '';
        $this->vcantmaxpasajeros = '';
        $this->vimporte = '';
        $this->tipoAsiento = '';
        $this->idaYvuelta = '';
        $this->mensaje = '';
    }

    public function cargar($vdestino, $vcantmaxpasajeros, $objempresa, $rnumeroempleado, $vimporte, $tipoAsiento, $idayvuelta)
    {
        //$this->setIdviaje($idviaje);
        $this->setVdestino($vdestino);
        $this->setVcantmaxpasajeros($vcantmaxpasajeros);
        $this->setObjempresa($objempresa);
        $this->setRnumeroempleado($rnumeroempleado);
        $this->setVimporte($vimporte);
        $this->setTipoAsiento($tipoAsiento);
        $this->setIdayvuelta($idayvuelta);
    }

    //metodos de acceso
    public function getIdviaje()
    {
        return $this->idviaje;
    }

    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }

    public function getVdestino()
    {
        return $this->vdestino;
    }

    public function setVdestino($vdestino)
    {
        $this->vdestino = $vdestino;
    }

    public function getVcantmaxpasajeros()
    {
        return $this->vcantmaxpasajeros;
    }

    public function setVcantmaxpasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }

    public function getObjempresa()
    {
        return $this->idempresa;
    }

    public function setObjempresa($idempresa)
    {
        $this->idempresa = $idempresa;
    }

    public function getRnumeroempleado()
    {
        return $this->rnumeroempleado;
    }

    public function setRnumeroempleado($rnumeroempleado)
    {
        $this->rnumeroempleado = $rnumeroempleado;
    }

    public function getVimporte()
    {
        return $this->vimporte;
    }

    public function setVimporte($vimporte)
    {
        $this->vimporte = $vimporte;
    }

    public function getTipoAsiento()
    {
        return $this->tipoAsiento;
    }

    public function setTipoAsiento($tipoAsiento)
    {
        $this->tipoAsiento = $tipoAsiento;
    }

    public function getIdayvuelta()
    {
        return $this->idayvuelta;
    }

    public function setIdayvuelta($idayvuelta)
    {
        $this->idayvuelta = $idayvuelta;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setMensaje($nuevo)
    {
        $this->mensaje = $nuevo;
    }

    //toString
    public function __toString()
    {
        return "----------------------------------
            ID: " . $this->getIdviaje() .
            "\nDestino: " . $this->getVdestino() .
            "\nCantidad maxima de pasajeros: " . $this->getVcantMaxPasajeros() .
            "\nEmpresa: \n" . $this->getObjempresa() .
            "\nEmpleado Responsable: \n" . $this->getRnumeroempleado() .
            "\nImporte: $" . $this->getVimporte() .
            "\nTipo de asiento: " . $this->getTipoAsiento() .
            "\nIda y vuelta: " . $this->getIdayvuelta() . "\n";
    }

    //funciones bd
    public function Buscar($id)
    {
        $base = new BaseDatos();
        $rta = false;
        $consulta = "SELECT * FROM Viaje WHERE idviaje=" . $id;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdviaje($row2['idviaje']);
                    $this->setVdestino($row2['vdestino']);
                    $this->setVcantMaxPasajeros($row2['vcantmaxpasajeros']);
                    $empresa = new Empresa();
                    $empresa->buscar($row2['idempresa']);
                    $this->setObjempresa($empresa);
                    //print_r($this->getObjempresa());
                    $responsable = new Responsable();
                    $responsable->buscar($row2['rnumeroempleado']);
                    $this->setRnumeroempleado($responsable);
                    $this->setVimporte($row2['vimporte']);
                    $this->setTipoAsiento($row2['tipoAsiento']);
                    $this->setIdayvuelta($row2['idayvuelta']);
                    $rta = true;
                }
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }

    public static function listar($condicion = '')
    {
        $array = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM viaje";
        if ($condicion != '') {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $array = array();
                while ($row2 = $base->Registro()) {
                    //$idViaje = $row2['idviaje'];
                    //$vdestino = $row2['vdestino'];
                   // $vcantmaxpasajeros = $row2['vcantmaxpasajeros'];
                   // $empresa = new Empresa();
                    //$empresa->buscar($row2['idempresa']);
                    //$idempresa = $empresa;
                    //$responsable = new Responsable();
                    //$responsable->buscar($row2['rnumeroempleado']);
                    //$rnumeroempleado = $responsable;
                    ///$vimporte = $row2['vimporte'];
                    //$tipoAsiento = $row2['tipoAsiento'];
                    //$idayvuelta = $row2['idayvuelta'];
                    //$viaje = new Viaje();
                    //$viaje->cargar2($idViaje, $vdestino, $vcantmaxpasajeros, $idempresa, $rnumeroempleado, $vimporte, $tipoAsiento, $idayvuelta);
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    $array[] = $objViaje;
                }
            } else {
                Viaje::setMensaje($base->getError());
            }
        } else {
            Viaje::setMensaje($base->getError());
        }
        return $array;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $rta = false;
        $empresa = $this->getObjempresa();
        $idEmpresa = $empresa->getIdEmpresa();
        $responsable = $this->getRnumeroempleado();
        $numResponsable = $responsable->getRnumeroempleado();
        $consulta = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) VALUES ('{$this->getVdestino()}', {$this->getVcantmaxpasajeros()}, {$idEmpresa}, {$numResponsable}, {$this->getVimporte()}, '{$this->getTipoasiento()}', '{$this->getIdayvuelta()}')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $rta = true;
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }

    public function modificar()
    {
        $rta = false;
        $base = new BaseDatos();
        $empresa = $this->getObjempresa();
        $idEmpresa = $empresa->getIdempresa();
        $responsable = $this->getRnumeroempleado();
        $numResponsable = $responsable->getRnumeroempleado();
        $consulta = "UPDATE viaje SET vdestino = '{$this->getVdestino()}', vcantmaxpasajeros = {$this->getVcantmaxpasajeros()}, idempresa = {$idEmpresa}, rnumeroempleado = {$numResponsable}, vimporte = {$this->getVimporte()}, tipoAsiento = '{$this->getTipoasiento()}', idayvuelta = '{$this->getIdayvuelta()}' WHERE idviaje = {$this->getIdviaje()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $rta = true;
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $rta = false;
        $consulta = "DELETE FROM viaje WHERE idviaje = " . $this->getIdviaje();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $rta = true;
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }
}
