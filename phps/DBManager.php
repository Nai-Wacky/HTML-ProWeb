<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "Usuarios.php";
require "Direccion.php";

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
        $this->db = "dbanyjob";
        $this->host = "localhost";
        $this->user = "root";
        $this->pass = null;
        $this->port = 3306;
    }

    public function open()
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

    public function close($link)
    {
        mysqli_close($link);
    }

    //-------------------------------------------------------------------USUARIO-------------------------------------------------------------------

    public function login($corr, $password) //ADAPTADO PARA EL PROYECTO
    {
        $link = $this->open();

        $sql = "SELECT * FROM usuarios WHERE correo = ?";
        $query = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param(
            $query,
            "s",
            $corr
        );
        mysqli_stmt_execute($query);

        $resultado = mysqli_stmt_get_result($query);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            // Verificar la contraseña
            if (password_verify($password, $fila['password'])) {
                // Login exitoso: puedes devolver info del usuario (sin la contraseña)
                unset($fila['password']); // Oculta el hash
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

    public function editNombre($id_usuario, $nombre) //FUNCIONA
    {
        $link = $this->open();

        $sql = "UPDATE usuarios SET nombre = ? WHERE id_usuario = ?";

        $query = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param(
            $query,
            "si",
            $nombre,
            $id_usuario
        );

        $resultado = mysqli_stmt_execute($query) or die('Error update');

        $this->close($link);

        return $resultado;
    }

    public function editPassword($id_usuario, $password) //FUNCIONA
    {
        $link = $this->open();

        $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";

        $query = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param(
            $query,
            "si",
            $password,
            $id_usuario
        );

        $resultado = mysqli_stmt_execute($query) or die('Error update');

        $this->close($link);

        return $resultado;
    }

    public function editCorreo($id_usuario, $correo) //FUNCIONA
    {
        $link = $this->open();

        $sql = "UPDATE usuarios SET correo = ? WHERE id_usuario = ?";

        $query = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param(
            $query,
            "si",
            $correo,
            $id_usuario
        );

        $resultado = mysqli_stmt_execute($query) or die('Error update');

        $this->close($link);

        return $resultado;
    }



    //-------------------------------------------------------------------direcciones-------------------------------------------------------------------

    public function getDirecciones()
    {
        $link = $this->open();

        $sql = "SELECT * FROM direcciones"; // Ajusta el nombre de la tabla si es diferente
        $result = mysqli_query($link, $sql);

        $productos = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $productos[] = $row;
            }
        } else {
            return ["error" => "Error al obtener los productos"];
        }

        $this->close($link);

        return $productos;
    }


    public function addAddress(Direccion $direccion) //ADAPTADO PARA EL PROYECTO
    {
        $link = $this->open();

        $sql = "INSERT INTO direcciones VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)";

        // Prepara la consulta
        $query = mysqli_prepare($link, $sql);

        // Enlaza los parametros (reemplaza comodines)
        // Tipos: i para enteros, s para string, d para double y b para blob

        //print $usuario->nombre;

        mysqli_stmt_bind_param(
            $query,
            "issssss",
            $direccion->id_usuario,
            $direccion->estado,
            $direccion->municipio,
            $direccion->cod_postal,
            $direccion->calle,
            $direccion->colonia,
            $direccion->numero
        );

        // Ejecuta la query
        $resultado = mysqli_stmt_execute($query) or die('Error insert');

        $this->close($link);

        return $resultado;
    }

    public function deleteDireccion($id_direccion)
    {
        $link = $this->open();

        $sql = "DELETE FROM direcciones WHERE id_direccion = ?";

        $query = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param(
            $query,
            "i",
            $id_direccion
        );

        $resultado = mysqli_stmt_execute($query) or die('Error delete');

        $this->close($link);

        return $resultado;
    }

    public function editDireccion(Direccion $direccion) //FUNCIONA
    {
        $link = $this->open();

        $sql = "UPDATE direcciones SET id_usuario= ? ,estado= ? ,municipio= ? ,cod_postal= ? ,calle= ? ,colonia= ? ,numero= ? WHERE id_direccion = ?";

        $query = mysqli_prepare($link, $sql);

        mysqli_stmt_bind_param(
            $query,
            "issssssi",
            $direccion->id_usuario,
            $direccion->estado,
            $direccion->municipio,
            $direccion->cod_postal,
            $direccion->calle,
            $direccion->colonia,
            $direccion->numero,
            $direccion->id_direccion
        );

        $resultado = mysqli_stmt_execute($query) or die('Error update');

        $this->close($link);

        return $resultado;
    }


    //-------------------------------------------------------------------productos-------------------------------------------------------------------

    public function getProductos()
    {
        try {
            $link = $this->open();
            
            if (!$link) {
                throw new Exception("Error de conexión a la base de datos");
            }

            // Consulta simple a la tabla productos
            $sql = "SELECT * FROM productos";
                    
            $result = mysqli_query($link, $sql);

            if (!$result) {
                throw new Exception(mysqli_error($link));
            }

            $productos = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $productos[] = $row;
            }

            $this->close($link);
            return $productos;

        } catch (Exception $e) {
            if (isset($link)) {
                $this->close($link);
            }
            return ["error" => "Error al obtener los productos: " . $e->getMessage()];
        }
    }

    //-------------------------------------------------------------------carrito-------------------------------------------------------------------
    
    public function agregarAlCarrito($idProducto, $idCliente) {
        try {
            $link = $this->open();
            
            if (!$link) {
                throw new Exception("Error de conexión a la base de datos");
            }

            // Verificamos si el producto ya está en el carrito del usuario
            $sqlExiste = "SELECT * FROM carrito WHERE idproducto = ? AND idcliente = ?";
            $stmtExiste = mysqli_prepare($link, $sqlExiste);
            mysqli_stmt_bind_param($stmtExiste, "ii", $idProducto, $idCliente);
            mysqli_stmt_execute($stmtExiste);
            $resultExiste = mysqli_stmt_get_result($stmtExiste);

            if (mysqli_num_rows($resultExiste) > 0) {
                return ["error" => "Este producto ya está en tu carrito"];
            }

            // Si no existe, lo agregamos
            $sqlInsertar = "INSERT INTO carrito (idproducto, idcliente) VALUES (?, ?)";
            $stmtInsertar = mysqli_prepare($link, $sqlInsertar);
            
            if (!$stmtInsertar) {
                throw new Exception("Error al preparar la consulta: " . mysqli_error($link));
            }

            mysqli_stmt_bind_param($stmtInsertar, "ii", $idProducto, $idCliente);
            
            if (!mysqli_stmt_execute($stmtInsertar)) {
                throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmtInsertar));
            }

            $this->close($link);
            return ["success" => true];

        } catch (Exception $e) {
            if (isset($link)) {
                $this->close($link);
            }
            return ["error" => "Error al agregar al carrito: " . $e->getMessage()];
        }
    }
}
