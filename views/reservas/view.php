<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Reserva | RENTAL</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/logo_tigrinho.png" type="image/png">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: "Poppins", sans-serif;
        }

        .fixed-logo {
            position: fixed;
            left: 0;
            top: 0;
            height: 100px;
            display: flex;
            align-items: center;
            padding: 10px;
            z-index: 1000;
        }

        .fixed-logo img {
            height: 100px;
            margin-right: 10px;
        }

        .fixed-logo span {
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="fixed-logo">
        <img src="/logo_tigrinho.png" alt="Logo da RENTAL">
        <span style="color: #000000;">RENTAL</span>
    </div>

    <div class="container mt-5">
        <h2>Detalhes da Reserva</h2>

        <table class="table table-bordered">
            <tr>
                <th>ID da Reserva</th>
                <td><?php echo htmlspecialchars($reserva['id']); ?></td>
            </tr>
            <tr>
                <th>Carro</th>
                <td><?php echo htmlspecialchars($reserva['modelo']); ?></td>
            </tr>
            <tr>
                <th>Data de Início</th>
                <td><?php echo htmlspecialchars($reserva['inicio']); ?></td>
            </tr>
            <tr>
                <th>Data de Devolução</th>
                <td><?php echo htmlspecialchars($reserva['devolucao']); ?></td>
            </tr>
            <tr>
                <th>Nota Fiscal</th>
                <td><?php echo htmlspecialchars($reserva['nf']); ?></td>
            </tr>
            <tr>
                <th>Valor Total</th>
                <td><?php echo htmlspecialchars($reserva['valor']); ?></td>
            </tr>
            <tr>
                <th>Status Pagamento</th>
                <td><?php echo htmlspecialchars($reserva['pagamento']); ?></td>
            </tr>
        </table>

        <a href="/index.php?action=dashboard" class="btn btn-secondary mt-3">Voltar para Dashboard</a>

        <!-- Botão de Pagar: Só será exibido se o pagamento for "Aguardando" ou "Atrasado" -->
        <?php if ($reserva['pagamento'] == 'Aguardando' || $reserva['pagamento'] == 'Atrasado'): ?>
            <button id="payButton" class="btn btn-success mt-3 float-right"
                onclick="pagar(<?php echo $reserva['id']; ?>)">
                Pagar
            </button>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function pagar(reservaId) {
            // Faz uma requisição ao servidor para atualizar o status de pagamento para "PAGO"
            window.location.href = `/index.php?action=pagar_reserva&id=${reservaId}`;
        }
    </script>
</body>

</html>