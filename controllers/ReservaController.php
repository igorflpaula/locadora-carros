<?php
require_once '../models/Reserva.php';
require_once '../models/Carro.php';
require_once '../models/Locacao.php';

class ReservaController
{
    private $db;
    private $reservaModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->reservaModel = new Reserva($this->db);
    }

    public function list()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /index.php?action=login");
            exit();
        }

        $reserva = new Reserva($this->db);
        $reservas = $reserva->getAllByUser($_SESSION['user_id']);
        include '../views/reservas/list.php';
    }

    public function create()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /index.php?action=login");
            exit();
        }

        // Verifique se o método de solicitação é POST para processar o formulário
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $carroId = $_POST['carro'];
            $inicio = $_POST['inicio'];
            $devolucao = $_POST['devolucao'];

            // Valide se todos os campos estão preenchidos
            if (empty($carroId) || empty($inicio) || empty($devolucao)) {
                $mensagem = "Todos os campos são obrigatórios.";
                include '../views/reservas/create.php';
                return;
            }

            // Crie a reserva
            $reserva = new Reserva($this->db);
            $reserva->usuario = $_SESSION['user_id'];
            $reserva->carro = $carroId;
            $reserva->inicio = $inicio;
            $reserva->devolucao = $devolucao;
            $reserva->create();

            // Redirecione após a criação
            header("Location: /index.php?action=dashboard");
            exit();
        }

        // Busque todos os carros disponíveis e passe para a view
        $carro = new Carro($this->db);
        $carrosDisponiveis = $carro->getDisponiveis();

        include '../views/reservas/create.php';
    }

    public function view($id)
    {
        $reserva = $this->reservaModel->getById($id);
        if ($reserva) {
            include '../views/reservas/view.php';
        } else {
            header("Location: /index.php?action=dashboard");
        }
    }

    public function delete($id)
    {
        $locacao = new Locacao($this->db);

        // Atualizar o status da locação para 'Cancelado' em vez de deletar a reserva
        if ($locacao->cancelarPorReserva($id)) {
            header("Location: /index.php?action=dashboard&msg=Reserva cancelada com sucesso");
        } else {
            header("Location: /index.php?action=dashboard&msg=Erro ao cancelar a reserva");
        }
    }


    public function pagar($id)
    {
        $locacao = new Locacao($this->db);

        // Chame um método para atualizar o status de pagamento para "PAGO"
        if ($locacao->atualizarPagamento($id, 'Pago')) {
            header("Location: /index.php?action=dashboard&msg=Pagamento realizado com sucesso");
        } else {
            header("Location: /index.php?action=dashboard&msg=Erro ao realizar o pagamento");
        }
    }
}
