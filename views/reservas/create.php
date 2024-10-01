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
        <h1 class="mb-4">Criar Nova Reserva</h1>

        <!-- Mensagens de sucesso ou erro -->
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/index.php?action=create_reserva">
            <div class="form-group">
                <label for="carro">Selecione o Carro*:</label>
                <select id="carro" name="carro" class="form-control" required onchange="calculateTotal()">
                    <option value="">Selecione um carro</option>
                    <?php if (!empty($carrosDisponiveis)): ?>
                        <?php foreach ($carrosDisponiveis as $carro): ?>
                            <option value="<?= htmlspecialchars($carro['id']) ?>" data-diaria="<?= htmlspecialchars($carro['valor_diaria']) ?>">
                                <?= htmlspecialchars($carro['modelo']) . " (" . htmlspecialchars($carro['ano']) . ") - R$ " . htmlspecialchars($carro['valor_diaria']) . "/dia" ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Nenhum carro disponível</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="inicio">Data de Início*:</label>
                <input type="date" id="inicio" name="inicio" class="form-control" required onchange="validateDates()"> 
            </div>

            <div class="form-group">
                <label for="devolucao">Data de Devolução*:</label>
                <input type="date" id="devolucao" name="devolucao" class="form-control" required onchange="validateDates()">
            </div>

            <div class="form-group">
                <label>Valor Total da Reserva:</label>
                <input type="text" id="total" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Criar Reserva</button>
        </form>

        <a href="/index.php?action=dashboard" class="btn btn-secondary mt-3">Voltar para Dashboard</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function calculateTotal() {
            const carSelect = document.getElementById('carro');
            const selectedCar = carSelect.options[carSelect.selectedIndex];
            const dailyRate = parseFloat(selectedCar.getAttribute('data-diaria')) || 0;

            const startDate = new Date(document.getElementById('inicio').value);
            const endDate = new Date(document.getElementById('devolucao').value);
            const timeDiff = endDate - startDate;
            const days = timeDiff / (1000 * 60 * 60 * 24) + 1; // Include both start and end dates

            if (days > 0) {
                const total = days * dailyRate;
                document.getElementById('total').value = `R$ ${total.toFixed(2)}`;
            } else {
                document.getElementById('total').value = '';
            }
        }

        function validateDates() {
            calculateTotal();

            return;
            const startDate = new Date(document.getElementById('inicio').value);
            const endDate = new Date(document.getElementById('devolucao').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (startDate < today) {
                alert('A data de início não pode ser menor que o dia atual.');
                document.getElementById('inicio').value = '';
            }

            if (endDate <= startDate) {
                alert('A data de devolução precisa ser maior que a data de início.');
                document.getElementById('devolucao').value = '';
            }

            calculateTotal();
        }
    </script>
</body>

</html>
