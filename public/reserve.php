<?php
require '../src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $guests = (int)($_POST['guests'] ?? 0);

    if ($name && $email && $date && $time && $guests > 0) {
        $latestReservation = $reservationsCollection->findOne([], ['sort' => ['reservation_id' => -1]]);
        $nextId = $latestReservation ? $latestReservation->reservation_id + 1 : 1;
        $formattedId = sprintf('%04d', $nextId);

        $reservation = [
            'reservation_id' => $nextId,
            'formatted_id' => $formattedId,
            'name' => $name,
            'email' => $email,
            'date' => $date,
            'time' => $time,
            'guests' => $guests,
        ];

        $result = $reservationsCollection->insertOne($reservation);

        if ($result->getInsertedCount() > 0) {
            $message = '<div class="alert alert-success" role="alert">Reserva realizada com sucesso!</div>';
        } else {
            $message = '<div class="alert alert-danger" role="alert">Falha ao realizar a reserva.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning" role="alert">Todos os campos são obrigatórios e o número de convidados deve ser maior que zero.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fazer Reserva</title>
    <!-- Inclua o Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclua o jQuery UI CSS -->
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .form-control, .btn {
            border-radius: 0.25rem;
        }
        #date {
            z-index: 1000; /* Ajusta o calendário acima de outros elementos */
        }
        .ui-datepicker {
            font-size: 0.875rem; /* Reduz o tamanho da fonte do calendário */
            background: #ffffff; /* Define o fundo do calendário */
        }
        .ui-datepicker-header {
            background: #007bff; /* Define o fundo do cabeçalho do calendário */
            color: #ffffff; /* Define a cor do texto do cabeçalho */
        }
        .ui-state-active, .ui-state-highlight {
            background: #007bff; /* Define a cor de fundo dos dias selecionados */
            color: #ffffff; /* Define a cor do texto dos dias selecionados */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Fazer Reserva</h1>
        <?php if (isset($message)) echo $message; ?>
        <form action="reserve.php" method="post">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="date">Data:</label>
                <input type="text" id="date" name="date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="time">Hora:</label>
                <input type="time" id="time" name="time" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="guests">Número de Convidados:</label>
                <input type="number" id="guests" name="guests" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-danger">Reservar</button>
        </form>
    </div>

    <!-- Inclua o jQuery e jQuery UI JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Inclua o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Inclua o Locale em Português do jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/i18n/datepicker-pt-BR.js"></script>
    <script>
        $(function() {
            $("#date").datepicker({
                dateFormat: "yy-mm-dd", // Formato da data
                minDate: 0, // Não permite selecionar datas passadas
                showAnim: "fadeIn", // Animação ao mostrar o calendário
                regional: "pt-br" // Configura o calendário para português
            });
        });
    </script>
</body>
</html>
