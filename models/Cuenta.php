<?php
    require_once("./db/Conexion.php");
    class Cuenta extends Conexion
    {
        function __construct()
        {
            parent:: __construct();
        }
        function crearCuenta($ident, $saldo)
        {
            $sql = "SELECT ident FROM cuenta WHERE ident = '$ident'";
            $r = $this->conexion->query($sql);
            if(!$r->num_rows > 0)
            {
                $numeroCuenta = random_int(100000000, 999999999);
                $sql = "INSERT INTO cuenta (nrocuenta, ident, fecha, saldo) VALUES ($numeroCuenta, '$ident', CURDATE(), $saldo)";
    
                if($this->conexion->query($sql))
                {
                    $usuario['ident'] = $ident;
                    $usuario['numeroCuenta'] = $numeroCuenta;
                    $usuario['saldo'] = $saldo;
    
                    $estado['status'] = 'ok';
                    $estado['message'] = 'se a creado la cuenta exitosamente';
                    $estado['user'] = $usuario;
    
                    return $estado;
                }
                else
                {
                    $estado['status'] = 'error';
                    $estado['message'] = 'consulta incorrecta';
                    return $estado;
                }
            }
            else
            {
                $estado['status'] = 'error';
                $estado['message'] = 'el usuario ya posee cuenta';

                return $estado;
            }

        }
    }
?>