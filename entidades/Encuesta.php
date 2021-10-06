<?php
class Encuesta
{
    private $idencuesta;
    public $nombre;
    private $fk_idgenero;
    private $fk_idhobby;
    private $dedicacion_hobby;


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
        $sql = "SELECT idencuesta, nombre, fk_idgenero, fk_idhobby, dedicacion_hobby FROM encuestas";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $encuestaAux = new Encuesta();
                $encuestaAux->idencuesta = $fila["idencuesta"];
                $encuestaAux->nombre = $fila["nombre"];
                $encuestaAux->fk_idgenero = $fila["fk_idgenero"];
                $encuestaAux->fk_idhobby = $fila["fk_idhobby"];
                $encuestaAux->dedicacion_hobby = $fila["dedicacion_hobby"];
                $aResultado[] = $encuestaAux;
            }
        }
        return $aResultado;
    }

    public function estadisticasNombres()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT A.nombre, COUNT(A.nombre) AS cantidad FROM encuestas A 
        GROUP BY A.nombre";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $encuestaAux = new Encuesta();
                $encuestaAux->nombre = $fila["nombre"];
                $encuestaAux->cantidad = $fila["cantidad"];
                $aResultado[] = $encuestaAux;
            }
        }
        return $aResultado;
    }

    public function estadisticasHobby()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT B.nombre, COUNT(A.fk_idhobby) AS cantidad
        FROM encuestas A
        LEFT JOIN hobbies B ON B.idhobby = A.fk_idhobby
        GROUP BY B.nombre";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $encuestaAux = new Encuesta();
                $encuestaAux->nombre = $fila["nombre"];
                $encuestaAux->cantidad = $fila["cantidad"];
                $aResultado[] = $encuestaAux;
            }
        }
        return $aResultado;
    }
    public function estadisticasDedicacion()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT B.nombre, SUM(A.dedicacion_hobby) AS cantidad
        FROM encuestas A
        LEFT JOIN hobbies B ON B.idhobby = A.fk_idhobby
        GROUP BY B.nombre";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $encuestaAux = new Encuesta();
                $encuestaAux->nombre = $fila["nombre"];
                $encuestaAux->cantidad = $fila["cantidad"];
                $aResultado[] = $encuestaAux;
            }
        }
        return $aResultado;
    }

    public function obtenerGrilla($inicio, $registros_por_pagina)
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'A.nombre',
            1 => 'B.nombre',
            2 => 'C.nombre',
            3 => 'A.dedicacion_hobby'
        );
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT DISTINCTROW
                A.idencuesta, 
                A.nombre, 
                A.fk_idgenero, 
                A.fk_idhobby, 
                A.dedicacion_hobby,
                B.nombre as hobby,
                C.nombre as genero
                FROM encuestas A
                LEFT JOIN hobbies B ON B.idhobby = A.fk_idhobby
                LEFT JOIN generos C ON C.idgenero = A.fk_idgenero
                WHERE 1=1";
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( A.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR B.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR C.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR A.dedicacion_hobby LIKE '%" . $request['search']['value'] . "%' )";
        }


        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];
        $sql .= " LIMIT " . $inicio . "," . $registros_por_pagina;
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $encuestaAux = new Encuesta();
                $encuestaAux->nombre = $fila["nombre"];
                $encuestaAux->genero = $fila["genero"];
                $encuestaAux->hobby = $fila["hobby"];
                $encuestaAux->dedicacion_hobby = $fila["dedicacion_hobby"];
                $aResultado[] = $encuestaAux;
            }
        }
        return $aResultado;
    }
    public function cantidadTotalPorGenero($id)
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT COUNT(A.fk_idgenero) AS cantidad FROM encuestas A 
        WHERE A.fk_idgenero = $id";
        $resultado = $mysqli->query($sql);

        //Convierte el resultado
        if ($fila = $resultado->fetch_assoc()) {
            $this->cantidad = $fila["cantidad"];
        }
        $mysqli->close();
    }


    public function cargarRequest($request)
    {
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->fk_idgenero = isset($request["lstGenero"]) ? $request["lstGenero"] : "";
        $this->fk_idhobby = isset($request["lstHobby"]) ? $request["lstHobby"] : "";
        $this->dedicacion_hobby = isset($request["lstDedicacion"]) ? $request["lstDedicacion"] : "";
    }

    public function insertar()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "INSERT INTO encuestas (nombre, fk_idgenero, fk_idhobby, dedicacion_hobby) VALUES ('" . $this->nombre . "', '" . $this->fk_idgenero . "', '" . $this->fk_idhobby . "', '" . $this->dedicacion_hobby . "');";
        $mysqli->query($sql);
        $this->idgenero = $mysqli->insert_id;
        $mysqli->close();
    }
}
