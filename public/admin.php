<?php
session_start();
require '../src/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $result = $reservationsCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    
    if ($result->getDeletedCount() > 0) {
        echo '<div class="alert alert-success" role="alert">Reserva excluída com sucesso!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Falha ao excluir a reserva.</div>';
    }
}

$reservations = $reservationsCollection->find([]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Administração de Reservas</title>
    <!-- Inclua o Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Administração de Reservas</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Número de Convidados</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo sprintf('%04d', $reservation->_id->getTimestamp()); ?></td>
                        <td><?php echo htmlspecialchars($reservation->name); ?></td>
                        <td><?php echo htmlspecialchars($reservation->email); ?></td>
                        <td><?php echo htmlspecialchars($reservation->date); ?></td>
                        <td><?php echo htmlspecialchars($reservation->time); ?></td>
                        <td><?php echo htmlspecialchars($reservation->guests); ?></td>
                        <td>
                            <a href="admin.php?delete=<?php echo $reservation->_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta reserva?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Inclua o Bootstrap JS e jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
