<?php

require_once '../models/Locacao.php';

class Reserva
{
    private $conn;
    private $table_name = "reservas";

    public $id;
    public $usuario;
    public $carro;
    public $inicio;
    public $devolucao;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $this->conn->beginTransaction();

        $query = "INSERT INTO " . $this->table_name . " 
                  SET usuario=:usuario, carro=:carro, inicio=:inicio, devolucao=:devolucao";

        $stmt = $this->conn->prepare($query);

        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->carro = htmlspecialchars(strip_tags($this->carro));
        $this->inicio = htmlspecialchars(strip_tags($this->inicio));
        $this->devolucao = htmlspecialchars(strip_tags($this->devolucao));

        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":carro", $this->carro);
        $stmt->bindParam(":inicio", $this->inicio);
        $stmt->bindParam(":devolucao", $this->devolucao);

        // Executa a inserção da reserva
        if ($stmt->execute()) {
            // Pegando o ID da reserva recém-criada
            $reserva_id = $this->conn->lastInsertId();

            // Calculando a diferença de dias entre início e devolução
            $inicio = new DateTime($this->inicio);
            $devolucao = new DateTime($this->devolucao);
            $interval = $inicio->diff($devolucao);
            $dias = $interval->days;

            // Exemplo de cálculo de valor: R$ 100,00 por dia
            $valor_total = $dias * 100.00;

            // Gerando um código de Nota Fiscal (NF) aleatório
            $nf = $this->generateRandomNF();

            // Criando a locação usando a model Locacao
            $locacao = new Locacao($this->conn);
            $locacao->nf = $nf;
            $locacao->valor = $valor_total;
            $locacao->pagamento = 'Aguardando';
            $locacao->usuario = $this->usuario;
            $locacao->carro = $this->carro;
            $locacao->reserva = $reserva_id;

            // Executa a inserção da locação
            if ($locacao->create()) {
                // Confirma a transação
                $this->conn->commit();
                return true;
            } else {
                // Rollback se a locação falhar
                $this->conn->rollBack();
                return false;
            }
        }

        // Rollback se a reserva falhar
        $this->conn->rollBack();
        return false;
    }

    public function getAllByUser($user_id)
    {
        $query = "SELECT r.*, c.modelo, l.pagamento AS locacao_status 
                  FROM " . $this->table_name . " r
                  JOIN locacao l ON r.id = l.reserva
                  JOIN carros c ON c.id = r.carro
                  WHERE r.usuario = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getById($id)
    {
        $query = "SELECT r.*, c.modelo, c.status, l.nf, l.valor, l.pagamento FROM " . $this->table_name . " r
                  JOIN carros c ON r.carro = c.id
                  JOIN locacao l ON r.id = l.reserva
                  WHERE r.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteById($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    private function generateRandomNF()
    {
        $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2);
        $numbers = substr(str_shuffle("0123456789"), 0, 4);
        return $letters . $numbers;
    }
}
