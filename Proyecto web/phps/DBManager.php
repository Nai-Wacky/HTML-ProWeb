<?php

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

    public function show($p = null) //FUNCIONA
    {
        $link = $this->open();

        $sql = "SELECT * FROM usuarios ";
        if ($p) {
            $p = "'%$p%'";
            $sql .= " WHERE nombre_user LIKE $p";
        }

        $result = mysqli_query($link, $sql, MYSQLI_ASSOC) or die('Error query');

        $rows = [];
        while ($columns = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $rows[] = $columns;
        }

        $this->close($link);

        return $rows;
    }

    public function findCorreo($corr, $pass) //FUNCIONA
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

    public function editDay($id, $day) //FUNCIONA
    {
        $link = $this->open();

        $sql = "UPDATE usuarios SET avance = ? WHERE id_usuario = ?";

        $query = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param(
            $query,
            "ss",
            $day,
            $id
        );

        $resultado = mysqli_stmt_execute($query) or die('Error update');

        $this->close($link);

        return $resultado;
    }

    public function reset($id, $day)
    {
        $day = "0";

        $link = $this->open();

        $sql = "UPDATE productos SET avance = ? WHERE id_usuario = ?";

        $query = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param(
            $query,
            "ss",
            $day,
            $id
        );

        $resultado = mysqli_stmt_execute($query) or die('Error update');

        $this->close($link);

        return $resultado;
    }

    //-------------------------------------------------------------------direcciones-------------------------------------------------------------------





    //-------------------------------------------------------------------productos-------------------------------------------------------------------


}
