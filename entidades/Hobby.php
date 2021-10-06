<?php

class Hobby
{
    private $idhobby;
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
        $sql = "SELECT idhobby, nombre FROM hobbies";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $hobbyAux = new Hobby();
                $hobbyAux->idhobby = $fila["idhobby"];
                $hobbyAux->nombre = $fila["nombre"];
                $aResultado[] = $hobbyAux;
            }
        }
        return $aResultado;
    }
}
