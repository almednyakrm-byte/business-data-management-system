<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\CustomersController;
use App\Repository\CustomersRepository;
use App\Service\CustomersService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestCustomers extends TestCase
{
    private $customersController;
    private $customersRepository;
    private $customersService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->customersRepository = $this->createMock(CustomersRepository::class);
        $this->customersService = $this->createMock(CustomersService::class);
        $this->customersController = new CustomersController($this->customersService);
    }

    public function testGetCustomers()
    {
        $expectedCustomers = ['customer1', 'customer2'];
        $this->customersRepository->expects($this->once())
            ->method('getAllCustomers')
            ->willReturn($expectedCustomers);
        $this->customersService->expects($this->once())
            ->method('getCustomers')
            ->willReturn($expectedCustomers);
        $response = $this->customersController->getCustomers();
        $this->assertEquals($expectedCustomers, $response);
    }

    public function testCreateCustomer()
    {
        $customerData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedCustomer = ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'];
        $this->customersRepository->expects($this->once())
            ->method('createCustomer')
            ->with($customerData)
            ->willReturn($expectedCustomer);
        $this->customersService->expects($this->once())
            ->method('createCustomer')
            ->with($customerData)
            ->willReturn($expectedCustomer);
        $response = $this->customersController->createCustomer($customerData);
        $this->assertEquals($expectedCustomer, $response);
    }

    public function testUpdateCustomer()
    {
        $customerId = 1;
        $customerData = ['name' => 'Jane Doe', 'email' => 'jane@example.com'];
        $expectedCustomer = ['id' => 1, 'name' => 'Jane Doe', 'email' => 'jane@example.com'];
        $this->customersRepository->expects($this->once())
            ->method('getCustomerById')
            ->with($customerId)
            ->willReturn($expectedCustomer);
        $this->customersService->expects($this->once())
            ->method('updateCustomer')
            ->with($customerId, $customerData)
            ->willReturn($expectedCustomer);
        $response = $this->customersController->updateCustomer($customerId, $customerData);
        $this->assertEquals($expectedCustomer, $response);
    }

    public function testDeleteCustomer()
    {
        $customerId = 1;
        $this->customersRepository->expects($this->once())
            ->method('deleteCustomer')
            ->with($customerId);
        $this->customersService->expects($this->once())
            ->method('deleteCustomer')
            ->with($customerId);
        $response = $this->customersController->deleteCustomer($customerId);
        $this->assertTrue($response);
    }
}



// CustomersController.php
namespace App\Controller;

use App\Service\CustomersService;

class CustomersController
{
    private $customersService;

    public function __construct(CustomersService $customersService)
    {
        $this->customersService = $customersService;
    }

    public function getCustomers()
    {
        return $this->customersService->getCustomers();
    }

    public function createCustomer(array $customerData)
    {
        return $this->customersService->createCustomer($customerData);
    }

    public function updateCustomer(int $customerId, array $customerData)
    {
        return $this->customersService->updateCustomer($customerId, $customerData);
    }

    public function deleteCustomer(int $customerId)
    {
        return $this->customersService->deleteCustomer($customerId);
    }
}

// CustomersService.php
namespace App\Service;

class CustomersService
{
    public function getCustomers()
    {
        // implementation
    }

    public function createCustomer(array $customerData)
    {
        // implementation
    }

    public function updateCustomer(int $customerId, array $customerData)
    {
        // implementation
    }

    public function deleteCustomer(int $customerId)
    {
        // implementation
    }
}

// CustomersRepository.php
namespace App\Repository;

class CustomersRepository
{
    public function getAllCustomers()
    {
        // implementation
    }

    public function createCustomer(array $customerData)
    {
        // implementation
    }

    public function getCustomerById(int $customerId)
    {
        // implementation
    }

    public function deleteCustomer(int $customerId)
    {
        // implementation
    }
}