<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\SalesController;
use App\Repository\SalesRepository;
use App\Service\SalesService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestSales extends TestCase
{
    private $salesController;
    private $salesRepository;
    private $salesService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->salesRepository = $this->createMock(SalesRepository::class);
        $this->salesService = $this->createMock(SalesService::class);
        $this->salesController = new SalesController($this->salesService);
    }

    public function testGetSales(): void
    {
        $this->salesService->expects($this->once())
            ->method('getAllSales')
            ->willReturn([
                ['id' => 1, 'product_id' => 1, 'quantity' => 10],
                ['id' => 2, 'product_id' => 2, 'quantity' => 20],
            ]);

        $response = $this->salesController->getSales();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'product_id' => 1, 'quantity' => 10],
            ['id' => 2, 'product_id' => 2, 'quantity' => 20],
        ], json_decode($response->getBody()->getContents(), true));
    }

    public function testCreateSale(): void
    {
        $this->salesService->expects($this->once())
            ->method('createSale')
            ->with(['product_id' => 1, 'quantity' => 10])
            ->willReturn(['id' => 1, 'product_id' => 1, 'quantity' => 10]);

        $response = $this->salesController->createSale(['product_id' => 1, 'quantity' => 10]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'product_id' => 1, 'quantity' => 10], json_decode($response->getBody()->getContents(), true));
    }

    public function testUpdateSale(): void
    {
        $this->salesService->expects($this->once())
            ->method('updateSale')
            ->with(1, ['product_id' => 2, 'quantity' => 20])
            ->willReturn(['id' => 1, 'product_id' => 2, 'quantity' => 20]);

        $response = $this->salesController->updateSale(1, ['product_id' => 2, 'quantity' => 20]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'product_id' => 2, 'quantity' => 20], json_decode($response->getBody()->getContents(), true));
    }

    public function testDeleteSale(): void
    {
        $this->salesService->expects($this->once())
            ->method('deleteSale')
            ->with(1)
            ->willReturn(true);

        $response = $this->salesController->deleteSale(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(json_decode($response->getBody()->getContents(), true));
    }
}



// SalesController.php
namespace App\Controller;

use App\Service\SalesService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesController
{
    private $salesService;

    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    public function getSales(): Response
    {
        return new JsonResponse($this->salesService->getAllSales());
    }

    public function createSale(Request $request): Response
    {
        $sale = $request->request->all();
        return new JsonResponse($this->salesService->createSale($sale));
    }

    public function updateSale(int $id, Request $request): Response
    {
        $sale = $request->request->all();
        return new JsonResponse($this->salesService->updateSale($id, $sale));
    }

    public function deleteSale(int $id): Response
    {
        return new JsonResponse($this->salesService->deleteSale($id));
    }
}



// SalesService.php
namespace App\Service;

use App\Repository\SalesRepository;

class SalesService
{
    private $salesRepository;

    public function __construct(SalesRepository $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    public function getAllSales(): array
    {
        return $this->salesRepository->findAll();
    }

    public function createSale(array $sale): array
    {
        // Create a new sale
        return $sale;
    }

    public function updateSale(int $id, array $sale): array
    {
        // Update a sale
        return $sale;
    }

    public function deleteSale(int $id): bool
    {
        // Delete a sale
        return true;
    }
}