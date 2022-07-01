<?php

class Responsable{
    private $rnumeroEmpleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido; 
    private $mensaje;

    public function __construct()
    {
        $this->rnumeroEmpleado = '';
        $this-> rnumerolicencia = '';
        $this->rnombre = '';
        $this->rapellido = '';
        $this->mensaje = '';
    }

    public function cargar($rnumerolicencia, $rnombre, $rapellido)
    {
        //$this->rnumeroEmpleado = $rnumeroEmpleado;
        $this->rnumerolicencia = $rnumerolicencia;
        $this->rnombre = $rnombre;
        $this->rapellido = $rapellido;
    }

    public function getRnumeroEmpleado(){
        return $this->rnumeroEmpleado;
    }

    public function setRnumeroEmpleado($rnumeroEmpleado){
        $this->rnumeroEmpleado = $rnumeroEmpleado;
    }

    public function getRnumerolicencia(){
        return $this->rnumerolicencia;
    }

    public function setRnumerolicencia($rnumerolicencia){
        $this->rnumerolicencia = $rnumerolicencia;
    }

    public function getRnombre(){
        return $this->rnombre;
    }

    public function setRnombre($rnombre){
        $this->rnombre = $rnombre;
    }

    public function getRapellido(){
        return $this->rapellido;
    }

    public function setRapellido($rapellido){
        $this->rapellido = $rapellido;
    }

    public function getMensaje(){
        return $this->mensaje;
    }

    public function setMensaje($mensaje){
        $this->mensaje = $mensaje;
    }

    public function __toString()
    {
        return "Numero Empleado: " . $this->getRnumeroEmpleado() . 
        "\nNumero Licencia: " . $this->getRnumerolicencia() . 
        "\nNombre: " . $this->getRnombre() . 
        "\nApellido: " . $this->getRapellido() . "\n";
    }

    //funciones bd
    public function buscar($id){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado= " . $id;
        $rta = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                if($row2 = $base->Registro()){
                    $this->setRnumeroEmpleado($row2['rnumeroempleado']);
                    $this->setRnumerolicencia($row2['rnumerolicencia']);
                    $this->setRnombre($row2['rnombre']);
                    $this->setRapellido($row2['rapellido']);
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
        $consulta = "SELECT * FROM responsable";
        if($condicion != ''){
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $array = array();
                while($row2 = $base->Registro()){
                    //$rnumeroEmpleado = $row2['rnumeroempleado'];
                    //$rnumerolicencia = $row2['rnumerolicencia'];
                    //$rnombre = $row2['rnombre'];
                    //$rapellido = $row2['rapellido'];
                    $responsable = new Responsable();
                    //$responsable->cargar2($rnumeroEmpleado, $rnumerolicencia, $rnombre, $rapellido);
                    $responsable->buscar($row2['rnumeroempleado']);
                    $array[] = $responsable;
                }
            }else{
                Responsable::setMensaje($base->getError());
            }
        }else{
            Responsable::setMensaje($base->getError());
        }
        return $array;
    }

    public function insertar(){
        $base = new BaseDatos();
        $rta = false;
        $consulta = "INSERT INTO responsable(rnumerolicencia, rnombre, rapellido) VALUES ('{$this->getRnumeroLicencia()}', '{$this->getrNombre()}', '{$this->getrApellido()}')";
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
        $consulta = "UPDATE responsable SET rnumerolicencia = {$this->getRnumeroLicencia()}, rnombre = '{$this->getRnombre()}', rapellido = '{$this->getRapellido()}' WHERE rnumeroempleado = {$this->getRnumeroEmpleado()}";
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
        $consulta = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getRnumeroEmpleado();
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