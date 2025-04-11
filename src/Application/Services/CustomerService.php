<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Repositories\CustomerRepository;

class CustomerService
{
    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomers(): array
    {
        return $this->customerRepository->findAll();
    }
}
