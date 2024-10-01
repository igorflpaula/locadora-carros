-- Tabela Carro
CREATE TABLE carros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chassi VARCHAR(20),
    modelo VARCHAR(50),
    ano INT,
    combustivel VARCHAR(20),
    status ENUM('Disponível', 'Reservado', 'Em Revisão', 'Indisponível') NOT NULL DEFAULT 'disponivel'
);
ALTER TABLE carros
ADD COLUMN valor_diaria DECIMAL(10, 2) NOT NULL DEFAULT 0.00;
ALTER TABLE carros
MODIFY COLUMN status ENUM('Disponível', 'Reservado', 'Em Revisão', 'Indisponível') NOT NULL DEFAULT 'Disponível';


-- Inserir carros disponíveis
INSERT INTO carros (chassi, modelo, ano, combustivel, status, valor_diaria)
VALUES 
('ABC12345XYZ67890', 'Toyota Corolla', 2022, 'Gasolina', 'Disponível', 150.00),
('DEF67890XYZ12345', 'Honda Civic', 2023, 'Flex', 'Disponível', 180.00),
('GHI12345XYZ67890', 'Ford Ka', 2021, 'Álcool', 'Disponível', 120.00),
('JKL67890XYZ12345', 'Fiat Uno', 2020, 'Gasolina', 'Reservado', 100.00),
('MNO12345XYZ67890', 'Chevrolet Onix', 2019, 'Flex', 'Em Revisão', 130.00);


-- Tabela Reserva
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario int,
    carro int,
    inicio DATE,
    devolucao DATE,
    FOREIGN KEY (usuario) REFERENCES usuarios(id),
    FOREIGN KEY (carro) REFERENCES carros(id)
);

-- Tabela Locacao
CREATE TABLE locacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nf VARCHAR(15),
    valor DECIMAL(10, 2),
    pagamento ENUM('Pago', 'Aguardando', 'Atrasado', 'Cancelado') NOT NULL DEFAULT 'Aguardando',
    usuario int,
    carro int,
    reserva int,
    FOREIGN KEY (usuario) REFERENCES usuarios(id),
    FOREIGN KEY (carro) REFERENCES carros(id),
    FOREIGN KEY (reserva) REFERENCES reservas(id)
);