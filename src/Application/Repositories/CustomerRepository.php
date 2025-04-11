<?php

declare(strict_types=1);

namespace App\Application\Repositories;

use App\Application\Models\Customer;

class CustomerRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM clienti");
        $customers = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $customers[] = new Customer($row['id'], $row['nome'], $row['cognome']);
        }
        return $customers;
    }
}
