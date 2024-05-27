<?php
require __DIR__ . '/../vendor/autoload.php';

use sistema\CategoriaTransacao;
use sistema\Transacao;
use sistema\TipoTransacao;

function cadastrarTransacaoArquivoCSV() : string{
    try {
        $file = fopen('../local-db/csv/transacao.csv', 'r');
        $i = 0;
        
        fgetcsv($file); //Cabeçalho da tabela
        
        while (($line = fgetcsv($file)) !== false) {
            if (empty(end($line))) {
                array_pop($line); // Remove o último elemento vazio
            }
            $transacao = new Transacao(new TipoTransacao($line[0]), $line[1], $line[2], $line[3], new CategoriaTransacao($line[4]));
            $transacao->cadastrarTransacao();
            $i++;
        }
        fclose($file);

        return "{$i} Transações foram cadastradas com sucesso!";
    
    } catch (Exception $e) {
        return "Erro ao cadastrar valores do arquivo CSV: ". $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["processar_csv"])) {
    // Verificar se o botão foi clicado e processar o arquivo CSV
    $mensagem = cadastrarTransacaoArquivoCSV();
    echo "<p>$mensagem</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <button type="submit" name="processar_csv">Processar Arquivo CSV</button>
    </form>
</body>
</html>