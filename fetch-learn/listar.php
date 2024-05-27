<?php
require __DIR__ . '/../vendor/autoload.php';

use sistema\DataBase;

function getTransacoesJson() {
    $dbh = DataBase::getInstance()->getConnection();
    
    $stmt = $dbh->prepare(
        "SELECT
            transacoes.*, 
            categorias_transacao.id AS categoria_id, 
            categorias_transacao.nome AS categoria_nome,
            categorias_transacao.descricao AS categoria_descricao,
            tipos_transacao.id AS tipo_id,
            tipos_transacao.nome AS tipo_nome
        FROM 
            transacoes
        JOIN 
            categorias_transacao ON transacoes.categoria_transacao_id = categorias_transacao.id
        JOIN 
            tipos_transacao ON transacoes.tipo_transacao_id = tipos_transacao.id
        ORDER BY 
            transacoes.data_hora DESC;");
    $stmt->execute();

    $ress = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $transacoes = [];
  
    foreach ($ress as $row) {
        $transacoes[] = [
            'id' => $row['id'],
            'tipo' => [
                'id' => $row['tipo_id'],
                'nome' => $row['tipo_nome'],
                'desc' => $row['tipo_transacao_id']
            ],
            'descr' => $row['descricao'],
            'valor' => $row['valor'],
            'date' => $row['data_hora'],
            'categoria' => [
                'id' => $row['categoria_id'],
                'nome' => $row['categoria_nome'],
                'desc' => $row['categoria_descricao']
            ]
        ];
    }
    $parse_json = json_encode($transacoes, JSON_UNESCAPED_UNICODE);
    if (file_put_contents('../local-db/json/transacoes.json', $parse_json)){
        echo 'sucess';
    } else {
        echo 'error';
    }
    return $parse_json;

}
//sleep(1);
echo getTransacoesJson();