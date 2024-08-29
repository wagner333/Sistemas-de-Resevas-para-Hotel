<?php
require '../vendor/autoload.php'; // Se você usa Composer

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $database = $client->selectDatabase('reservations_db');
    $reservationsCollection = $database->selectCollection('reservations');
} catch (Exception $e) {
    echo 'Erro ao conectar ao MongoDB: ', $e->getMessage(), "\n";
    exit();
}
