<?php 
namespace sistema;

use sistema\DataBase;
use PDO;

class TipoTransacao {
    private int $id;
    private string $nome;
    private string $descricao;

    public function __construct(string $nome, ?string $descricao = null, ?int $id = null)
    {
        if ($id === null) {
            $this->setId(self::IdTipoByName($nome));
        } else {
            $this->setId($id);
        }

        $this->setNome($nome);

        if ($id === null) {
            $this->setDescricao(self::descricaoById(self::IdTipoByName($nome)));
        } else {
            $this->setDescricao($descricao);
        }
    }

    public static function IdTipoByName(string $string) {
        $pdo = DataBase::getInstance()->getConnection();
        
        $sql = "SELECT id FROM tipos_transacao WHERE nome = :string ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':string', $string, PDO::PARAM_STR);
        $stmt->execute();
        $ress = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ress['id'];
    }

    public static function descricaoById(int $id) {
        $pdo = DataBase::getInstance()->getConnection();
        
        $sql = "SELECT descricao FROM tipos_transacao WHERE id = :valor ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':valor', $id, PDO::PARAM_STR);
        $stmt->execute();
        $ress = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ress['descricao'];
    }

    // Getters e Setters
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }
}
?>