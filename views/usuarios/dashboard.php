<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | RENTAL</title>
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
        <span style="color: #000000;">CAR RENTAL</span>
    </div>

    <div class="container mt-5">
        <h1 class="mb-4">Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>

        <div class="d-flex justify-content-between mb-4">
            <div class="d-flex">
                <a href="/index.php?action=edit_profile&id=<?php echo $_SESSION['user_id']; ?>" class="btn btn-warning mr-2">Meu perfil</a>
                <a href="/logout.php" class="btn btn-danger">Logout</a>
            </div>
            <a href="/index.php?action=create_reserva" class="btn btn-primary">Nova Reserva</a>
        </div>

        <h2 class="mb-4">Minhas Reservas</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Carro</th>
                    <th>Início</th>
                    <th>Devolução</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservas)): ?>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reserva['id']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['inicio']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['devolucao']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['locacao_status']); ?></td>

                            <td>
                                <a href="/index.php?action=view_reserva&id=<?php echo $reserva['id']; ?>" class="btn btn-info btn-sm">Ver</a>

                                <!-- Verifica se a locação está cancelada -->
                                <?php if ($reserva['locacao_status'] != 'Cancelado'): ?>
                                    <a href="/index.php?action=delete_reserva&id=<?php echo $reserva['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja cancelar esta reserva?');">Cancelar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhuma reserva encontrada.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>