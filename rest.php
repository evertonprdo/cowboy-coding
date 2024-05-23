<?php
$data = json_decode(file_get_contents('php://input'), true) ?? null;

if ($data === null) {
    $data = [];
}

$tipo = $data['tipo'] ?? '';
$descricao = $data['descricao'] ?? '';
$valor = $data['valor'] ?? '';
$date= $data['date'] ?? '';
$categoria = $data['categoria'] ?? '';

$response = [
    'tipo' => $tipo,
    'descricao' => $descricao,
    'valor' => $valor,
    'date' => $date,
    'categoria' => $categoria
];

header('Content-Type: application/json');

echo json_encode($response);
?>