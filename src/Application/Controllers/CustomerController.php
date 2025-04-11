<?php

namespace App\Application\Controllers;

use App\Application\Services\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerController
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request, Response $response): Response
    {
        
        $customers = $this->customerService->getAllCustomers();
        
        $customersArray = array_map(function ($customer) {
            return $customer->toArray();
        }, $customers);

        $response->getBody()->write(json_encode(['data' => $customersArray]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function store()
    {

    }

    public function destroy()
    {

    }
}
