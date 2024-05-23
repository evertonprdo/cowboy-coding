<?php 
namespace sistema;

use sistema\DataBase;

use sistema\CategoriaTransacao;
use sistema\TipoTransacao;

use DateTime;
use Exception;
use PDO;

class Transacao {
    private ?int $id;
    private TipoTransacao $tipo;
    private string $descricao;
    private float $valor;
    private DateTime $data_hora;
    private CategoriaTransacao $categoria;

    public function __construct(TipoTransacao $tipo, string $descricao, $valor, $data_hora, CategoriaTransacao $categoria, ?int $id = null)
    {
        $this->setId($id);
        $this->setTipo($tipo);
        $this->setDescricao($descricao);
        $this->setValor($valor);
        $this->setData($data_hora);
        $this->setCategoria($categoria);
    }

    public function cadastrarTransacao() : void {
        $pdo = DataBase::getInstance()->getConnection();

        $sql = "INSERT INTO transacoes (tipo_transacao_id,	descricao,	valor,	data_hora,	categoria_transacao_id) VALUES (:tipo , :descricao , :valor , :data_hora , :categoria);";

        $valores = [
            'tipo' => $this->tipo->getId(),
            'descricao' => $this->descricao,
            'valor' => $this->valorToDecimal(),
            'data_hora' => $this->data_hora->format('Y-m-d H:i:s'),
            'categoria' => $this->categoria->getId()
        ];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($valores);
    }

    public function valorToDecimal() : string {
        return number_format($this->getValor(), 2, '.', "");
    }

    //Metodos Getters
    public function getId() {
        return $this->id;
    }
    public function getTipo() : TipoTransacao {
        return $this->tipo;
    }
    public function getDescricao() : string {
        return $this->descricao;
    }
    public function getValor() : float{
        return $this->valor;
    }
    public function getData() : DateTime{
        return $this->data_hora;
    }
   
    public function getCategoria() : CategoriaTransacao{
        return $this->categoria;
    }

    // Metodos Setters
    public function setId(?int $id) : void {
        $this->id = $id;
    }

    public function setTipo(TipoTransacao $tipo) : void  {
        $this->tipo = $tipo;
    }

    public function setDescricao(string $descricao) : void  {
        $this->descricao = $descricao;
    }


    public function setValor($valor) : void  {
        if (is_string($valor)) {
            // Remover caracteres não numéricos da string de valor
            $valor = preg_replace('/[^0-9\.,]/', '', $valor);

            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }

        // Converter para float
        $valorFloat = floatval($valor);

        $this->valor = $valorFloat;
    }

    public function setData($data_hora) : void  {
        try {
            if ($data_hora instanceof DateTime) {
                $this->data_hora = $data_hora;

            } elseif (is_string($data_hora)) {
                if (strpos($data_hora, '/') !== false) {
                    $this->data_hora = new DateTime(str_replace('/', '-', $data_hora));
                
                } else {
                    $this->data_hora = new DateTime($data_hora);
                }
            }
        } catch (Exception $e) {
            echo "Erro ao tentar Definir a Data da Transacao com o valor {$data_hora}: " . $e->getMessage();
        }
    }

    public function setCategoria(CategoriaTransacao $categoria) : void {
        $this->categoria = $categoria;
    }
}
?>