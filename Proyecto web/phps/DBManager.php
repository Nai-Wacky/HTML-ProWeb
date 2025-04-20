<?php

require "Usuarios.php";

class DBManager
{
    private $db;
    private $host;
    private $user;
    private $pass;
    private $port;

    //mysql://root:YySIikZhGCZVOphoMdqGCzoccVokWHUs@shinkansen.proxy.rlwy.net:27717/railway

    public function __construct()
    {
        $this->db = "anyjob";
        $this->host = "shinkansen.proxy.rlwy.net";
        $this->user = "root";
        $this->pass = "YySIikZhGCZVOphoMdqGCzoccVokWHUs";
        $this->port = 27717;
    }

    private function open()
    {
        $link = mysqli_connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->db,
            $this->port
        ) or die('Error al abrir conexion');

        return $link;
    }

    private function close($link)
    {
        mysqli_close($link);
    }

    //-------------------------------------------------------------------USUARIO-------------------------------------------------------------------

    public function login($corr, $pass) //ADAPTADO PARA EL PROYECTO
    {
        $link = $this->open();

        $sql = "SELECT * FROM usuarios WHERE correo='$corr' AND password='$pass'";

        $result = mysqli_query($link, $sql, MYSQLI_ASSOC) or die('Error query');

        $rows = [];
        while ($columns = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $rows[] = $columns;
        }

        $this->close($link);

        return $rows;
    }

    public function addUser(Usuario $usuario) //ADAPTADO PARA EL PROYECTO
    {
        $link = $this->open();

        $sql = "INSERT INTO usuarios VALUES(NULL, ?, ?, ?, ?)";

        // Prepara la consulta
        $query = mysqli_prepare($link, $sql);

        // Enlaza los parametros (reemplaza comodines)
        // Tipos: i para enteros, s para string, d para double y b para blob

        print $usuario->nombre;

        mysqli_stmt_bind_param(
            $query,
            "ssss",
            $usuario->nombre,
            $usuario->correo,
            $usuario->password,
            $usuario->numerotel
        );

        // Ejecuta la query
        $resultado = mysqli_stmt_execute($query) or die('Error insert');

        $this->close($link);

        return $resultado;
    }

    //-------------------------------------------------------------------direcciones-------------------------------------------------------------------





    //-------------------------------------------------------------------productos-------------------------------------------------------------------


}
