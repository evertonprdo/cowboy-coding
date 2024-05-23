<?php 
require __DIR__ . '/../vendor/autoload.php';

use sistema\DataBase;

function listarResultado() : string {
    $render = '';
    if($url_get = $_GET['menor'] ?? null) {
        $dbh = DataBase::getInstance()->getConnection();

        $like = "%$url_get%";
        
        $stmt = $dbh->prepare("SELECT * FROM transacoes WHERE descricao LIKE ? AND tipo_transacao_id = 2");
        $stmt->execute([$like]);

        $ress = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($ress as $row) {
            $dia_mes = date_format(new DateTime($row['data_hora']), 'd/m/y');
            $render .= "<tr><td class=\"texto\">{$row['descricao']}</td><td class = \"valor\">{$row['valor']}</td><td class = \"data\">{$dia_mes}</td></tr>";
        }    
    }
    return $render;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td.valor::before {
            content: 'R$ ';
        }
        td.valor {
            text-align: center;
        }

        table tr:nth-child(2n) {
            background-color: #eee;
        }
        td.data {
            text-align: center;
        }
        td, th {
            border: 1px solid black;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <form action="" method="get">
        <label for="menor">Procurar: </label>
        <input type="text" name="menor" id="menor" value="<?= $_GET['menor']??''?>">

        <button type="submit">Pesquisar</button>
    </form>

    <div>
        <h2>Lista de Resultados Para: <?= $_GET['menor']?? ''?></h2>
        <table>
            <thead>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data Pagamento</th>
            </thead>
            <tbody>
                <?= listarResultado() ?>
            </tbody>
        </table>
    </div>
</body>
</html>