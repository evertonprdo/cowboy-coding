<?php
namespace sistema;

use sistema\DataBase;
use PDO;

class CategoriaTransacao {
    private int $id;
    private string $nome;
    private string $descricao;
    
    public function __construct(string $nome, ?string $descricao = null, ?int $id = null)
    {
        if ($id === null) {
            $this->setId(self::IdCategoriaByName($nome));
        } else {
            $this->setId($id);
        }
        
        $this->setNome($nome);
        
        if ($id === null) {
            $this->setDescricao(self::descricaoById(self::IdCategoriaByName($nome)));
        } else {
            $this->setDescricao($descricao);
        }
    }

    public static function IdCategoriaByName(string $string) {
        $pdo = DataBase::getInstance()->getConnection();
        
        $sql = "SELECT id FROM categorias_transacao WHERE nome = :string ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':string', $string, PDO::PARAM_STR);
        $stmt->execute();
        $ress = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ress['id'];
    }

    public static function descricaoById(int $id) {
        $pdo = DataBase::getInstance()->getConnection();
        
        $sql = "SELECT descricao FROM categorias_transacao WHERE id = :valor ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':valor', $id, PDO::PARAM_STR);
        $stmt->execute();
        $ress = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ress['descricao'];
    }

    // Metodos Getters
    public function getId() {
        return $this->id;
    }

    public function getNome() : string {
        return $this->nome;
    }

    public function getDescricao() : string {
        return $this->descricao;
    }

    // Metodos Setters
    public function setId(int $id) : void {
        $this->id = $id;
    }

    public function setNome(string $nome) : void {
        $this->nome = $nome;
    }

    public function setDescricao(string $descricao) : void {
        $this->descricao = $descricao;
    }
}
?>