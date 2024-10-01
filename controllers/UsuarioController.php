<?php

require_once '../models/Usuario.php';
require_once '../models/Reserva.php';

class UsuarioController
{
    private $db;
    private $usuario;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function showLoginForm()
    {
        include '../views/usuarios/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            if (!empty($email) && !empty($senha)) {
                $usuario = new Usuario($this->db);
                $usuario->email = $email;

                $result = $usuario->readByEmail();

                if ($result && password_verify($senha, $result['senha'])) {
                    session_start();
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['nome'] = $result['nome'];
                    header("Location: /index.php?action=dashboard");
                    exit();
                } else {
                    $error = "Email ou senha inválidos!";
                    include '../views/usuarios/login.php';
                }
            } else {
                $error = "Todos os campos são obrigatórios!";
                include '../views/usuarios/login.php';
            }
        } else {
            include '../views/usuarios/login.php';
        }
    }

    public function dashboard()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /index.php?action=login");
            exit();
        }

        $reserva = new Reserva($this->db);
        $reservas = $reserva->getAllByUser($_SESSION['user_id']);

        include '../views/usuarios/dashboard.php';
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /index.php?action=login");
    }

    public function index()
    {
        $stmt = $this->usuario->readAll();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include '../views/usuarios/index.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $telefone = $_POST['telefone'];
            $cnh = $_POST['cnh'];
            $endereco = $_POST['endereco'] ?? '';

            if (!empty($nome) && !empty($email) && !empty($senha) && !empty($telefone) && !empty($cnh)) {
                $this->usuario->nome = $nome;
                $this->usuario->email = $email;
                $this->usuario->senha = password_hash($senha, PASSWORD_BCRYPT);
                $this->usuario->telefone = $telefone;
                $this->usuario->cnh = $cnh;
                $this->usuario->endereco = $endereco;

                if ($this->usuario->create()) {
                    header("Location: /index.php?action=login");
                    exit();
                } else {
                    $error = "Erro ao criar o usuário!";
                    include '../views/usuarios/register.php';
                }
            } else {
                $error = "Todos os campos obrigatórios devem ser preenchidos!";
                include '../views/usuarios/register.php';
            }
        } else {
            include '../views/usuarios/register.php';
        }
    }


    public function create()
    {
        if ($_POST) {
            $this->usuario->nome = $_POST['nome'];
            $this->usuario->email = $_POST['email'];
            $this->usuario->senha = $_POST['senha'];

            if ($this->usuario->create()) {
                header("Location: /index.php");
            }
        }
        include '../views/usuarios/create.php';
    }

    public function edit($id)
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /index.php?action=login");
            exit();
        }

        if ($_POST) {
            $this->usuario->id = $id;
            $this->usuario->nome = $_POST['nome'] ?? null;
            $this->usuario->email = $_POST['email'] ?? null;
            $this->usuario->telefone = $_POST['telefone'] ?? null;
            $this->usuario->cnh = $_POST['cnh'] ?? null;
            $this->usuario->endereco = $_POST['endereco'] ?? null;

            if ($this->usuario->update()) {
                header("Location: /index.php?action=dashboard");
                exit();
            } else {
                $error = "Erro ao atualizar o perfil!";
                echo "<p>$error</p>";
            }
        } else {
            $this->usuario->id = $id;
            $usuarioData = $this->usuario->readById();
            if ($usuarioData) {
                include '../views/usuarios/edit.php';
            } else {
                header("Location: /index.php?action=dashboard");
                exit();
            }
        }
    }

    public function delete($id)
    {
        $this->usuario->id = $id;
        if ($this->usuario->delete()) {
            header("Location: /index.php");
        }
    }
}
