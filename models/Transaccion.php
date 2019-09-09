<?php
    require_once("./db/Conexion.php");
    class Transaccion extends Conexion
    {
        function __construct()
        {
            parent:: __construct();
        }
        
        function realizarTransaccion($cuentaOrigen, $cuentaDestino, $valor)
        {
            $sql = "SELECT nrocuenta FROM cuenta";
            $receive = $this->conexion->query($sql);
            $cuentas = array();

            if($receive->num_rows > 0)
            {
                while($cuenta = $receive->fetch_array())
                {
                    $cuentas[] = $cuenta['nrocuenta'];
                }
                
                if(in_array($cuentaOrigen, $cuentas))
                {
                    if(in_array($cuentaDestino, $cuentas))
                    {
                        if($cuentaOrigen != $cuentaDestino)
                        {
                            $sql = "SELECT saldo FROM cuenta WHERE nrocuenta = $cuentaOrigen";
                            $r =  $this->conexion->query($sql);
                            $receive = $r->fetch_array();
                            $valorDB = $receive['saldo'];

                            if($valor > 0)
                            {
                                if($valor <= $valorDB)
                                {
                                    $sql = "UPDATE cuenta SET saldo = saldo - $valor WHERE nrocuenta = $cuentaOrigen";
                                    if($this->conexion->query($sql))
                                    {
                                        $sql = "UPDATE cuenta SET saldo = saldo + $valor WHERE nrocuenta = $cuentaDestino";
                                        if($this->conexion->query($sql))
                                        {
                                            $sql = "INSERT INTO transaccion (nrocuentaorigen, nrocuentadestino, fecha, hora, valor) VALUES ($cuentaOrigen, $cuentaDestino, CURDATE(), CURTIME(), $valor)";
                                            if($this->conexion->query($sql))
                                            {
                                                $estado['estatus'] = "ok";
                                                $estado['message'] = "transferencia exitosa";
                                                $estado['valor'] = $valor;
                                                return $estado;
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $estado['estatus'] = "error";
                                    $estado['message'] = "saldo insuficiente";
                                    return $estado;
                                }
                            }
                            else
                            {
                                $estado['estatus'] = "error";
                                $estado['message'] = "no puede enviar cantidades menores a 1";
                                return $estado;
                            }
                        }
                        else
                        {
                            $estado['status'] = "error";
                            $estado['message'] = "no puede enviarse dinero a si mismo";
                            return $estado;
                        }
                    }
                    else
                    {
                        $estado['status'] = "error";
                        $estado['message'] = "la cuenta de destino no existe";
                        return $estado;
                    }
                }
                else
                {
                    $estado['status'] = "error";
                    $estado['message'] = "la cuenta de origen no existe";
                    return $estado;
                }
            }
            else
            {
                $estado['status'] = "error";
                $estado['message'] = "no hay cuentas para transferir";
                return $estado;
            }
        }

        function consultarTransacciones($ident)
        {
            $sql = "SELECT t.nrotransacc, t.nrocuentaorigen, t.nrocuentadestino, t.fecha, t.hora, t.valor 
            FROM transaccion t 
            RIGHT JOIN cuenta c ON t.nrocuentaorigen = c.nrocuenta 
            WHERE c.ident = '$ident'";

            $data = array();

            if($receive = $this->conexion->query($sql))
            {
                while($fila = $receive->fetch_array())
                {
                    if($fila['nrotransacc'] == null)
                    {
                        $estado['status'] = "error";
                        $estado['message'] = "el cliente no tiene transacciones";
                        return $estado;
                    }
                    
                    $d['nrotransacc'] = $fila['nrotransacc'];
                    $d['nrocuentaorigen'] = $fila['nrocuentaorigen'];
                    $d['nrocuentadestino'] = $fila['nrocuentadestino'];
                    $d['fecha'] = $fila['fecha'];
                    $d['hora'] = $fila['hora'];
                    $d['valor'] = $fila['valor'];
                    $data[] = $d;
                }
                $estado['status'] = 'ok';
                $estado['message'] = "transacciones encontradas";
                $estado['transacciones'] = $data;
                return $estado;
            }
        }
    }
?>