<?php

use Pasajero as GlobalPasajero;

class Pasajero
{
    private $rdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $idviaje;
    private $mensaje;

    public function __construct()
    {
        $this->rdocumento = '';
        $this->pnombre = '';
        $this->papellido = '';
        $this->ptelefono = '';
        $this->mensaje = '';
    }

    public function cargar($rdocumento, $pnombre, $papellido, $ptelefono, $idviaje)
    {
        $this->rdocumento = $rdocumento;
        $this->pnombre = $pnombre;
        $this->papellido = $papellido;
        $this->ptelefono = $ptelefono;
        $this->idviaje = $idviaje;
    }



    public function getRdocumento()
    {
        return $this->rdocumento;
    }

    public function setRdocumento($rdocumento)
    {
        $this->rdocumento = $rdocumento;
    }

    public function getPnombre()
    {
        return $this->pnombre;
    }

    public function setPnombre($pnombre)
    {
        $this->pnombre = $pnombre;
    }

    public function getPapellido()
    {
        return $this->papellido;
    }

    public function setPapellido($papellido)
    {
        $this->papellido = $papellido;
    }

    public function getPtelefono()
    {
        return $this->ptelefono;
    }

    public function setPtelefono($ptelefono)
    {
        $this->ptelefono = $ptelefono;
    }

    public function getIdviaje()
    {
        return $this->idviaje;
    }

    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }
    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }
    public function __toString()
    {
        return "Nro Documento: " . $this->getRdocumento() .
            "\nNombre: " . $this->getPnombre() .
            "\nApellido: " . $this->getPapellido() .
            "\nTelefono: " . $this->getPtelefono() .
            "\nID Viaje: " . $this->getIdviaje() . "\n";
    }

    //funciones bd
    public function buscar($id){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajero WHERE pdocumento= " . $id;
        $rta = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                if($row2 = $base->Registro()){
                    $this->setRdocumento($row2['pdocumento']);
                    $this->setPnombre($row2['pnombre']);
                    $this->setPapellido($row2['papellido']);
                    $this->setPtelefono($row2['ptelefono']);
                    $viaje = new Viaje();
                    $viaje->buscar($row2['idviaje']);
                    $this->setIdviaje($viaje);
                    $rta = true;
                }
            }else{
                $this->setMensaje($base->getError());
            }
        }else{
            $this->setMensaje($base->getError());
        }
        return $rta;
    }

    public static function listar($condicion = ''){
        $array = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajero";
        if($condicion != ''){
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $array = array();
                while($row2 = $base->Registro()){
                    $rdocumento = $row2['pdocumento'];
                    $pnombre = $row2['pnombre'];
                    $papellido = $row2['papellido'];
                    $ptelefono = $row2['ptelefono'];
                    $idviaje = $row2['idviaje'];

                    $pasajero = new Pasajero();
                    $pasajero->cargar($rdocumento, $pnombre, $papellido, $ptelefono, $idviaje);
                    $array[] = $pasajero;
                }
            }else{
                Pasajero::setMensaje($base->getError());
            }
        }else{
            Pasajero::setMensaje($base->getError());
        }
        return $array;
    }

    public function insertar(){
        $base = new BaseDatos();
        $rta = false;
        $objViaje = $this->getIdviaje();
        $idviaje = $objViaje->getIdviaje();
        $consulta = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) VALUES( '{$this->getRdocumento()}' , '{$this->getPnombre()}' ,'{$this->getPapellido()}' , '{$this->getPtelefono()}' , '{$idviaje}')";
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $rta = true;
            }else{
                $this->setMensaje($base->getError());    
            }
        }else{
            $this->setMensaje($base->getError());
        }
        return $rta;
    }

    public function modificar(){
        $rta = false;
        $base = new BaseDatos();
        $objViaje = $this->getIdviaje();
        $idViaje = $objViaje->getIdviaje();
        $consulta = "UPDATE pasajero SET pnombre = '{$this->getPnombre()}', papellido = '{$this->getPapellido()}', ptelefono = '{$this->getPtelefono()}', idviaje = '{$idViaje}' WHERE pdocumento =  '{$this->getRdocumento()}'";
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $rta = true;
            }else{
                $this->setMensaje($base->getError());
            }
        }else{
            $this->setMensaje($base->getError());
        }
        return $rta;
    }

    public function eliminar(){
        $base = new BaseDatos();
        $rta = false;
        $consulta = "DELETE FROM pasajero WHERE pdocumento = " . $this->getRdocumento();
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $rta = true;
            }else{
                $this->setMensaje($base->getError());
            }
        }else{
            $this->setMensaje($base->getError());
        }
        return $rta;
    }


}
