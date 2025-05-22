<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['nombre'])) {
    echo json_encode([
        'loggedin' => true,
        'nombre' => $_SESSION['nombre'],
        'idCliente' => $_SESSION['idCliente']
    ]);
} else {
    echo json_encode(['loggedin' => false]);
} 