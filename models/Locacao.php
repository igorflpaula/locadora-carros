<?php

class Locacao
{
    private $conn;
    private $table_name = "locacao";

    public $id;
    public $nf;
    public $valor;
    public $pagamento;
    public $usuario;
    public $carro;
    public $reserva;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para criar uma locação
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nf=:nf, valor=:valor, pagamento=:pagamento, usuario=:usuario, carro=:carro, reserva=:reserva";

        $stmt = $this->conn->prepare($query);

        // Limpeza de dados
        $this->nf = htmlspecialchars(strip_tags($this->nf));
        $this->valor = htmlspecialchars(strip_tags($this->valor));
        $this->pagamento = htmlspecialchars(strip_tags($this->pagamento));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->carro = htmlspecialchars(strip_tags($this->carro));
        $this->reserva = htmlspecialchars(strip_tags($this->reserva));

        // Bind de parâmetros
        $stmt->bindParam(":nf", $this->nf);
        $stmt->bindParam(":valor", $this->valor);
        $stmt->bindParam(":pagamento", $this->pagamento);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":carro", $this->carro);
        $stmt->bindParam(":reserva", $this->reserva);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function atualizarPagamento($reserva_id, $status_pagamento)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET pagamento = :pagamento 
                  WHERE reserva = :reserva";

        $stmt = $this->conn->prepare($query);

        // Bind de parâmetros
        $stmt->bindParam(":pagamento", $status_pagamento);
        $stmt->bindParam(":reserva", $reserva_id);

        return $stmt->execute();
    }

    public function cancelarPorReserva($reserva_id)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET pagamento = 'Cancelado'
                  WHERE reserva = :reserva";

        $stmt = $this->conn->prepare($query);

        // Bind de parâmetros
        $stmt->bindParam(":reserva", $reserva_id);

        return $stmt->execute();
    }
}
