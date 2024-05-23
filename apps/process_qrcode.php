<?php
// Receber a requisição POST
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['qrcode'])) {
    $qrcode = $data['qrcode'];

    // Processar o QR Code conforme necessário
    // Por exemplo, separar os dados e armazená-los em um array associativo
    $parts = explode('|', $qrcode);
    if (count($parts) >= 3) {
        $response = array(
            "chave_consulta" => $parts[0],
            "data_emissao" => $parts[1],
            "valor_total" => floatval($parts[2])
        );
    } else {
        $response = array("error" => "Formato de QR Code inválido");
    }

    // Enviar resposta de volta ao cliente
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode(array("error" => "Nenhum QR Code fornecido"));
}
?>
