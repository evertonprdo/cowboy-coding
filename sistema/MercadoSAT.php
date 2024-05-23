<?php
namespace sistema;

use voku\helper\HtmlDomParser as HDP;

class MercadoSAT {
    private string $descricao;
    private float $quant;
    private string $tipo;
    private float $preco;

    public function __construct($desc, $quant, $tipo, $preco)
    {
        $this->setDescricao($desc);
        $this->setQuant($quant);
        $this->setTipo($tipo);
        $this->setPreco($preco);
    }
    
    public static function getMercadoByFileHtmSAT(string $filePath) : array {
        $mhtmlContent = file_get_contents($filePath);
        
        $html = HDP::str_get_html($mhtmlContent);
        
        $table = $html->find('#tableItens', 0);
        
        if ($table) {
            // Conectar ao banco de dados
            //$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        
            // Iterar pelas linhas da tabela

            foreach ($table->find('tr') as $row) {
                $cells = $row->find('td') ?? null;
        
                if(!is_null($cells[2]->innerText ?? null)) {
                    //Remove todos os caracteres que não são numero execeto virgula
                    $unit_price = preg_replace('/[^\d,]/','',$cells[5]->innertext);
                    $unit_price = floatval(str_replace(",",".",$unit_price));
        
                    $total_price = floatval(str_replace(",",".",$cells[7]->innertext));
        
                    $quant = floatval(str_replace(',','.',$cells[3]->innertext));
                    $data[] = [
                        //"Remover comentários habilita resgatar os valores adicionais"
                        //'item_number' => $cells[0]->innertext,
                        //'code' => $cells[1]->innertext,
                        'desc' => $cells[2]->innertext,
                        'quant' => $quant,
                        'tipo' => $cells[4]->innertext,
                        //'unit_price' => $unit_price,
                        //'tibute_price' => $cells[6]->innertext,
                        'preco' => $total_price
                    ];
                }
            }
        return $data;
        } else {
            echo "Tabela não encontrada.";
        }
        
    }
    public function getDescricao(): string {
        return $this->descricao;
    }

    // Setter para $descricao
    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }

    // Getter para $quant
    public function getQuant(): float {
        return $this->quant;
    }

    // Setter para $quant
    public function setQuant(float $quant): void {
        $this->quant = $quant;
    }

    // Getter para $tipo
    public function getTipo(): string {
        return $this->tipo;
    }

    // Setter para $tipo
    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }

    // Getter para $preco
    public function getPreco(): float {
        return $this->preco;
    }

    // Setter para $preco
    public function setPreco(float $preco): void {
        $this->preco = $preco;
    }
}
?>