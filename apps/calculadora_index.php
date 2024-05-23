<?php
    function getNomeArquivo() : string {
        return 'json/teste.json';
    }

    function getJsonInfo($json) : Array {       
        if (file_exists($json)) {
    
            $conteudo = file_get_contents($json);
    
            $dados = json_decode($conteudo, true);
    
            if ($dados === null) {
                echo "Erro ao decodificar o JSON";
            }
            return $dados;
        }
    }

    function calcular($data) : float {       
        $x = $data['calculo']['variavel-1'];
        $y = $data['calculo']['variavel-2'];
        
        $simbolo = $data['calculo']['operacao'];

        switch ($simbolo) {
            case '+':
                return $x + $y;
            case '-':
                return $x - $y;
            case '*':
                return $x * $y;
            case '/':
                if ($y == 0) {
                    throw new Exception("Divisão por zero não é permitida");
                }
                return $x / $y;
            case '**':
                return $x ** $y;
            default:
                throw new Exception("Operação indefinida: $simbolo");
        }
    }

    function formatDinamicDecimal(float $numero, string $pont, int $max) : string {
        if (strpos($numero, "E+") !== false) {
            return $numero;
        }

        $partes = explode('.', $numero); // Divide o número em partes inteiras e decimais

        if (count($partes) > 1) {
            $digitos = str_split($partes[1]);
            
            $parte_decimal = '';
            $i = 0;
            
            foreach ($digitos as $digito) {
                if ($i >= $max) {
                    break;
                }
                $parte_decimal .= $digito;
                $i++;
            }
            return $partes[0] . $pont .$parte_decimal;
        } else {
            return $partes[0];
        }
    }

    function render() : string {
        $dados_render = getJsonInfo(getNomeArquivo())['calculo'];
        try {
            return "O Resultado de {$dados_render['variavel-1']} {$dados_render['operacao']} {$dados_render['variavel-2']} é " . formatDinamicDecimal(calcular(getJsonInfo(getNomeArquivo())), ',', 12);
        
        } catch (Exception $e) {
            return 'Erro ao calcular: '. $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculo Json</title>
    <style>
        div.classe {
            width: 100%;
            height: 300px;
            
            display: flex;
            align-items: center;
            justify-content: center;

            background-color: #FFFFF2;
        }
    </style>
</head>
<body>
    <div class="classe"><p><?= render() ?></p></div>
</body>
</html>