<?php 

class QrCodeSAT {
    private int $code;
    private $data_hora;
    private float $total;

    public function __construct(string $qrcode)
    {
        $parts = explode('|', $qrcode);
        
        $this->code = $parts[0];
        $this->data_hora = $parts[1];
        $this->total = floatval($parts[2]);
    }

    // Métodos getters para acessar as propriedades privadas
    public function getCode(): string
    {
        return $this->code;
    }

    public function getDataHora(): string
    {
        return $this->data_hora;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}

?>