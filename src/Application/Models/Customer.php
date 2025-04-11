<?php

namespace App\Application\Models;

class Customer
{
    private int $id;
    private string $nome;
    private string $cognome;

    public function __construct(?int $id, string $nome, string $cognome)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->cognome = $cognome;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCognome(): string
    {
        return $this->cognome;
    }

    public function setCognome(string $cognome): void
    {
        $this->cognome = $cognome;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cognome' => $this->cognome,
        ];
    }
}
