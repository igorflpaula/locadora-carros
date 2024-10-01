<?php
require_once '../controllers/UsuarioController.php';
require_once '../controllers/ReservaController.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$usuarioController = new UsuarioController($db);
$reservaController = new ReservaController($db);

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {
    case 'login':
        $usuarioController->login();
        break;
    case 'register':
        $usuarioController->register();
        break;
    case 'dashboard':
        $usuarioController->dashboard();
        break;
    case 'edit_profile':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $usuarioController->edit($id);
        } else {
            header("Location: /index.php?action=dashboard");
            exit();
        }
        break;
    case 'create_reserva':
        $reservaController->create();
        break;

    case 'edit_reserva':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $reservaController->edit($id);
        break;

    case 'view_reserva':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $reservaController->view($id);
        break;

    case 'delete_reserva':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $reservaController->delete($id);
        break;
    case 'pagar_reserva':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $reservaController->pagar($id);
        } else {
            header("Location: /index.php?action=dashboard&msg=Erro ao processar pagamento");
        }
        break;
    default:
        $usuarioController->login();
        break;
}
