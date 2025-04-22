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
        $this->db = "bdanyjob";
        $this->host = "localhost";
        $this->user = "root";
        $this->pass = null;
        $this->port = 3306;
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

    public function login($corr, $password) //ADAPTADO PARA EL PROYECTO
    {
        $link = $this->open();

        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $query = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($query, "s", $corr);
        mysqli_stmt_execute($query);

        $resultado = mysqli_stmt_get_result($query);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            // Verificar la contraseña
            if (password_verify($password, $fila['password'])) {
                // Login exitoso: puedes devolver info del usuario (sin la contraseña)
                unset($fila['password']); // Oculta el hash
                print "SI SE PUDO";
                return $fila;
            } else {
                return ["error" => "Contraseña incorrecta"];
            }
        } else {
            return ["error" => "Correo no encontrado"];
        }

        $this->close($link);
    }

    public function addUser(Usuario $usuario) //ADAPTADO PARA EL PROYECTO
    {
        $link = $this->open();

        $sql = "INSERT INTO usuarios VALUES(NULL, ?, ?, ?, ?)";

        // Prepara la consulta
        $query = mysqli_prepare($link, $sql);

        // Enlaza los parametros (reemplaza comodines)
        // Tipos: i para enteros, s para string, d para double y b para blob

        //print $usuario->nombre;
        //print $usuario->correo;
        print $usuario->password;
        //print $usuario->numerotel;

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
