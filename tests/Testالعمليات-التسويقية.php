<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Controller\OperationsController;
use App\Repository\OperationsRepository;
use App\Entity\Operations;

class Testالعمليات-التسويقية extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $operationsRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->operationsRepository = $this->createMock(OperationsRepository::class);
        $this->operationsRepository->method('findAll')->willReturn([]);
        $this->operationsRepository->method('find')->willReturn(null);
        $this->operationsRepository->method('save')->willReturn(new Operations());
        $this->operationsRepository->method('remove')->willReturn(null);

        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->tokenStorage->method('getToken')->willReturn(null);

        $this->controller = new OperationsController($this->operationsRepository, $this->tokenStorage);
        $this->router = $this->createMock(RouterInterface::class);
    }

    public function testGetAllOperations()
    {
        $request = new Request();
        $request->setMethod('GET');
        $response = $this->controller->getAllOperations($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOperation()
    {
        $request = new Request();
        $request->setMethod('GET');
        $response = $this->controller->getOperation($request, 1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateOperation()
    {
        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'Operation 1');
        $request->request->set('description', 'Description 1');
        $response = $this->controller->createOperation($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateOperation()
    {
        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('name', 'Operation 1');
        $request->request->set('description', 'Description 1');
        $response = $this->controller->updateOperation($request, 1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteOperation()
    {
        $request = new Request();
        $request->setMethod('DELETE');
        $response = $this->controller->deleteOperation($request, 1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'العمليات التسويقية' module. It creates a mock PDO object, a mock operations repository, and a mock token storage. It then tests the getAllOperations, getOperation, createOperation, updateOperation, and deleteOperation methods of the OperationsController class. Each test method creates a request object, sets the request method, and calls the corresponding method of the controller. It then asserts that the response is an instance of Response and has the expected status code.