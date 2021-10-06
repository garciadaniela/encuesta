<?php

class Genero
{
    private $idgenero;
    private $nombre;


    public function __construct()
    {
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }


    public function obtenerTodos()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idgenero, nombre FROM generos";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $generoAux = new Genero();
                $generoAux->idgenero = $fila["idgenero"];
                $generoAux->nombre = $fila["nombre"];
                $aResultado[] = $generoAux;
            }
        }
        return $aResultado;
    }
}
