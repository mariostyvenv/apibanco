<?php
    require_once("./db/Conexion.php");
    class Cliente extends Conexion
    {
        function __construct()
        {
            parent:: __construct();
        }
        
        function acceso($email ,$passw)
        {
            $sql = "SELECT c.ident, c.nombres, c.email, IFNULL(cu.nrocuenta,'error') AS nrocuenta 
            FROM cliente c 
            LEFT JOIN cuenta cu ON c.ident = cu.ident
            WHERE email = '$email' AND clave='$passw'";

            if($receive = $this->conexion->query($sql))
            {
                if($receive->num_rows > 0)
                {
                    $fila = $receive->fetch_array();

                    $data['ident'] = $fila['ident'];
                    $data['nombres'] = $fila['nombres'];
                    $data['email'] = $fila['email'];
                    $data['nrocuenta'] = $fila['nrocuenta'];

                    $estado['estatus'] = 'ok';
                    $estado['message'] = 'acceso Correcto';
                    $estado['user'] = $data;

                    return $estado;
                }                    
                else
                {
                    $estado['estatus'] = 'error';
                    $estado['message'] = 'email o contraseña incorrectos';
                    $estado['user'] = [];
                    return $estado;
                }
            }
            else
            {
                $estado['status'] = 'error';
                $estado['message'] = 'consulta incorrecta';
                return $estado;
            }
        }

        function insertarCliente($ident, $nombres, $email, $clave)
        {
            $sql = "INSERT INTO cliente (ident, nombres, email, clave) VALUES ('$ident','$nombres', '$email', '$clave')";
            if($this->conexion->query($sql))
            {
                $estado['status'] = 'ok';
                $estado['message'] = 'cliente Insertado';
                return $estado;
            }
            else
            {
                $estado['status'] = 'error';
                $estado['message'] = 'error al insertar cliente';
                return $estado;
            }
        }

        function actualizarCliente($ident, $nombres, $email, $clave)
        {
            $sql = "UPDATE cliente SET nombres = '$nombres', email ='$email', clave = '$clave' WHERE ident = '$ident'";
            if($this->conexion->query($sql))
            {
                $estado['status'] = 'ok';
                $estado['message'] = 'cliente actualizado';
                return $estado;
            }
            else
            {
                $estado['status'] = 'error';
                $estado['message'] = 'error al actualizar cliente';
                return $estado;
            }
        }

        function eliminarCliente($ident)
        {
            $sql = "DELETE FROM cuenta WHERE ident = '$ident'";
            if($this->conexion->query($sql))
            {
                $sql = "DELETE FROM cliente WHERE ident = '$ident'";
                if($this->conexion->query($sql))
                {
                    $estado['status'] = 'ok';
                    $estado['message'] = 'cliente eliminado';
                    return $estado;
                }
                else
                {
                    $estado['status'] = 'error';
                    $estado['message'] = 'error al eliminar cliente';
                    return $estado;
                }
            }
            else
            {
                $estado['status'] = 'error';
                $estado['message'] = 'error al eliminar cliente';
                return $estado;
            }


        }

        function listarClientes()
        {
            $sql = "SELECT ident, nombres, email, clave FROM cliente";
            $users = array();

            if($receive = $this->conexion->query($sql))
            {
                if($receive->num_rows > 0)
                {
                    $estado['status'] = 'ok';
                    $estado['message'] = 'Clientes encontrados';

                    while($fila = $receive->fetch_array())
                    {
                        $data['ident'] = $fila['ident'];
                        $data['nombres'] = $fila['nombres'];
                        $data['email'] = $fila['email'];
                        $data['clave'] = $fila['clave'];
                        $users[] = $data;
                    }
                    $estado['users'] = $users;
                    return $estado;
                }
                else
                {
                    $estado['status'] = 'ok';
                    $estado['mensaje'] = 'Clientes no encontrados';
                    $estado['users'] = [];
                    return $estado;
                }
            }
        }
        function consultarCliente($ident)
        {
            $sql = "SELECT cl.ident, cl.nombres, cl.email, cl.clave,
            IFNULL(cu.nrocuenta, 'error') AS nrocuenta, 
            IFNULL(cu.saldo, 'error') AS saldo 
            FROM cliente AS cl 
            LEFT JOIN cuenta cu ON cl.ident = cu.ident 
            WHERE cl.ident = '$ident'";

            if($receive = $this->conexion->query($sql))
            {
                if($receive->num_rows > 0)
                {
                    $data = $receive->fetch_array();
                    $usuario['ident'] = $data['ident'];
                    $usuario['nombres'] = $data['nombres'];
                    $usuario['email'] = $data['email'];
                    $usuario['clave'] = $data['clave'];
                    $usuario['nrocuenta'] = $data['nrocuenta'];
                    $usuario['saldo'] = $data['saldo'];
    
                    $estado['status'] = 'ok';
                    $estado['message'] = 'cliente encontrado';
                    $estado['user'] = $usuario;
    
                    return $estado;
                }
                else
                {
                    $estado['status'] = 'error';
                    $estado['message'] = 'cliente no encontrado';
    
                    return $estado;
                }

            }
            else
            {
                $estado['status'] = 'error';
                $estado['message'] = 'error en la consulta';
                $estado['sql'] = base64_encode($sql);
                return $estado;
            }
        }
    }
?>