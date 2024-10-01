<?php

class Carro
{
    private $conn;
    private $table_name = "carros";

    public $id;
    public $chassi;
    public $modelo;
    public $ano;
    public $combustivel;
    public $status;
    public $valor_diaria;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para criar um carro
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET chassi=:chassi, modelo=:modelo, ano=:ano, combustivel=:combustivel, status=:status";

        $stmt = $this->conn->prepare($query);

        // Limpeza de dados
        $this->chassi = htmlspecialchars(strip_tags($this->chassi));
        $this->modelo = htmlspecialchars(strip_tags($this->modelo));
        $this->ano = htmlspecialchars(strip_tags($this->ano));
        $this->combustivel = htmlspecialchars(strip_tags($this->combustivel));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind de parâmetros
        $stmt->bindParam(":chassi", $this->chassi);
        $stmt->bindParam(":modelo", $this->modelo);
        $stmt->bindParam(":ano", $this->ano);
        $stmt->bindParam(":combustivel", $this->combustivel);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getDisponiveis()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 'Disponível'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os carros disponíveis como um array associativo
    }
}
