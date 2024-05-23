<?php 
require __DIR__ . '../vendor/autoload.php';

use sistema\MercadoSAT;

//$ress = MercadoSAT::getMercadoByFileHtmSAT('./cupom-fiscal/arquivo.htm');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        #reader {
            min-width: 750px;
            height: 650px;
            padding: 10px;
        }
        #result {
            text-align: center;
            font-size: 1rem;
            width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body>
    <hr>
    <button id="meuBotao">Clique Aqui</button>
    <main>
        <div id="reader"></div>
        <div id="result"></div>
    </main>
    <script>
        const meuBotao = document.getElementById('meuBotao');
        meuBotao.addEventListener('click', function() {
            const scanner = new Html5QrcodeScanner('reader', {
                qrbox: {
                    width: 250,
                    height: 250,
                },
                fps: 20,
            });

            let timeoutReached = false;

            // Definir um limite de tempo de 1 minuto (60000 milissegundos)
            const timeout = setTimeout(() => {
                timeoutReached = true;
                scanner.clear();
                document.getElementById('reader').remove();
                document.getElementById('result').innerText = "Tempo limite atingido. Nenhum QR Code encontrado.";
            }, 60000);

            scanner.render(success, error);

            function success(result) {
                if (timeoutReached) {
                    return;
                }

                clearTimeout(timeout); // Cancelar o timeout quando o QR Code for encontrado

                document.getElementById('result').innerText = result;

                // Enviar o resultado para o servidor via fetch
                fetch('process_qrcode.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ qrcode: result })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // Exibir a resposta do servidor, se necessário
                    document.getElementById('result').innerText += '\n' + JSON.stringify(data);
                })
                .catch((error) => {
                    console.error(err);
                });

                scanner.clear();
                document.getElementById('reader').remove();
            }
            function error(err) {
            }
        });
    </script>
</body>
</html>