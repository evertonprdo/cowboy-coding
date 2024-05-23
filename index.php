<?php 
require __DIR__ . '/vendor/autoload.php';

use sistema\DataBase;

function listarResultado() : string {
    $render = '';
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

    foreach($ress as $row) {
        $tipo = $row['tipo_nome'];
        $descricao = $row['descricao'];
        $valor = number_format($row['valor'],2,',','.');
        $date = date_format(new DateTime($row['data_hora']), 'd/m/Y');
        $categoria = $row['categoria_nome'];

        $render .= "<div class='t-row'><div class='$tipo'>$tipo</div><div>$descricao</div><div>R$ $valor</div><div>$date</div><div>$categoria</div></div>";
    }
    return $render;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transações</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form id="transacao-form">
        <select name="tipo" id="tipo">
            <option value="1">Entrada</option>
            <option value="2">Saída</option>
        </select>
    
        <input type="text" name="descricao" id="descricao" placeholder="Descrição da Transação">
        <div class="value-row">
            <label for="valor">R$</label>
            <input type="number" name="valor" id="valor" placeholder="00,00" step="0.01" min="0">
        </div>
        <input type="date" name="date" id="date" value='<?php echo date("Y-m-d"); ?>'>
    
        <select name="categoria" id="categoria">
            <option value="1">Aquisição</option>
            <option value="2">Mercado</option>
            <option value="3">Determinado</option>
            <option value="4">Recorrente</option>
            <option value="5">Pontual</option>
            <option value="6">Dízimo</option>
            <option value="7">Reserva</option>
            <option value="8">Empréstimo</option>
            <option value="9">Crédito</option>
        </select>
        <button type="submit">Registrar Transação</button>
    </form>

    <section class="tabela">
        <div class="t-head">
            <div class="t-row">
                <div>Tipo</div>
                <div>Descrição</div>
                <div>Valor</div>
                <div>Data</div>
                <div>Categoria</div>
            </div>
        </div>
        <div class="t-body">
            <?= listarResultado() ?>
        </div>
    </section>

    <script>
        const form = document.getElementById('transacao-form');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm(this);
        });

        function submitForm(form) {
            const formData = new FormData(form);
            let data = {};
            
            for(const [name, value] of formData.entries()) {
                data[name] = value;
            }

            fetch('rest.php', {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text()) // Obter a resposta como texto
            .then(text => {
                console.log('Raw response:', text); // Logar a resposta bruta
                try {
                    const jsonData = JSON.parse(text); // Tentar converter para JSON
                    console.log('Parsed JSON:', jsonData); // Logar o JSON parseado
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            })
            .catch(error => console.error('Fetch error:', error))
        }
    </script>
</body>
</html>