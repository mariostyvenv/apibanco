<?php
    require_once("./models/Cliente.php");
    require_once("./models/Cuenta.php");
    require_once("./models/Transaccion.php");
    header('Content-Type: application/json');

    $cliente = new Cliente;
    $cuenta = new Cuenta;
    $transaccion = new Transaccion;

    $estado = array();

    if(isset($_POST))
    {
        switch ($_POST['tipo']) 
        {

            case 'acceso':

                $email = $_POST['email'];
                $passw = $_POST['password'];
                
                echo json_encode($cliente->acceso($email, $passw));
                break;
            
            case 'insertar':

                $ident = $_POST['ident'];
                $nombres = $_POST['nombres'];
                $email = $_POST['email'];
                $clave = $_POST['clave'];

                echo json_encode($cliente->insertarCliente($ident, $nombres, $email, $clave));
                break;
            
            case 'actualizar':

                $ident = $_POST['ident'];
                $nombres = $_POST['nombres'];
                $email = $_POST['email'];
                $clave = $_POST['clave'];

                echo json_encode($cliente->actualizarCliente($ident, $nombres, $email, $clave));
                break;
            
            case 'eliminar':
                $ident = $_POST['ident'];
                echo json_encode($cliente->eliminarCliente($ident));
                break;

            case 'listarClientes':
                echo json_encode($cliente->listarClientes());
                break;

            case 'crearCuenta':
                $ident = $_POST['ident'];
                $saldo = $_POST['saldo'];
                echo json_encode($cuenta->crearCuenta($ident, $saldo));
                break;

            case 'consultarCuenta':
                $ident = $_POST['ident'];
                echo json_encode($cliente->consultarCliente($ident));
                break;
            
            case 'realizarTransaccion':
                $cuentaOrigen = $_POST['cuentaOrigen'];
                $cuentaDestino = $_POST['cuentaDestino'];
                $valor = $_POST['valor'];
                echo json_encode($transaccion->realizarTransaccion($cuentaOrigen, $cuentaDestino, $valor));
                break;

            case 'consultarTransacciones':
                $ident = $_POST['ident'];
                echo json_encode($transaccion->consultarTransacciones($ident));
                break;
                
            default:
                $estado['status'] = 'error';
                $estado['message'] = 'Petición incorrecta';
                echo json_encode($estado);
                break;
        }
    }
?>