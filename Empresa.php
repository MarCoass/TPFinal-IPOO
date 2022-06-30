<?php
class Empresa
{
    private $idEmpresa;
    private $enombre;
    private $edireccion;
    private $mensaje;

    public function __construct()
    {
        $this->idEmpresa = '';
        $this->enombre = '';
        $this->edireccion = '';
        $this->mensaje = '';
    }

    public function cargar($enombre, $edireccion)
    {
        //$this->setIdEmpresa(($idEmpresa));
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }

    //Para la funcion listar
    public function cargar2($idEmpresa, $enombre, $edireccion)
    {
        $this->setIdEmpresa(($idEmpresa));
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }


    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }

    public function getEnombre()
    {
        return $this->enombre;
    }

    public function setEnombre($enombre)
    {
        $this->enombre = $enombre;
    }

    public function getEdireccion()
    {
        return $this->edireccion;
    }

    public function setEdireccion($edireccion)
    {
        $this->edireccion = $edireccion;
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
        return "ID Empresa: " . $this->getIdEmpresa() .
            "\nNombre: " . $this->getEnombre() .
            "\nDireccion: " . $this->getEdireccion() . "\n";
    }

    //funciones bd

    public function buscar($id){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM empresa WHERE idempresa= " . $id;
        $rta = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                if($row2 = $base->Registro()){
                    $this->setIdempresa($row2['idempresa']);
                    $this->setEnombre($row2['enombre']);
                    $this->setEdireccion($row2['edireccion']);
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
        $consulta = "SELECT * FROM empresa";
        if($condicion != ''){
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $array = array();
                while($row2 = $base->Registro()){
                    $idempresa = $row2['idempresa'];
                    $enombre = $row2['enombre'];
                    $edireccion = $row2['edireccion'];
                    $empresa = new Empresa();
                    $empresa->cargar2($idempresa, $enombre, $edireccion);
                    $array[] = $empresa;
                }
            }else{
                Empresa::setMensaje($base->getError());
            }
        }else{
            Empresa::setMensaje($base->getError());
        }
        return $array;
    }

    public function insertar(){
        $base = new BaseDatos();
        $rta = false;
        $consulta = "INSERT INTO empresa(enombre, edireccion) VALUES('{$this->getEnombre()}', '{$this->getEdireccion()}')";
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
        $consulta = "UPDATE empresa SET enombre = '{$this->geteNombre()}', edireccion = '{$this->getEdireccion()}' WHERE idempresa = {$this->getIdempresa()}";
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
        $consulta = "DELETE FROM empresa WHERE idempresa = " . $this->getIdempresa();
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
