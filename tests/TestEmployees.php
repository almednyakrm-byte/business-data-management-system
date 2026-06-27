<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\EmployeeController;
use App\Repository\EmployeeRepository;
use App\Service\EmployeeService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestEmployees extends TestCase
{
    private $employeeController;
    private $employeeRepository;
    private $employeeService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->employeeRepository = $this->createMock(EmployeeRepository::class);
        $this->employeeService = $this->createMock(EmployeeService::class);
        $this->employeeController = new EmployeeController($this->employeeRepository, $this->employeeService);
    }

    public function testGetEmployees()
    {
        $this->employeeRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $request = new Request();
        $response = $this->employeeController->getEmployees($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['employees' => [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ]], $response->getContent());
    }

    public function testGetEmployee()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $request = new Request();
        $response = $this->employeeController->getEmployee(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['employee' => ['id' => 1, 'name' => 'John Doe']], $response->getContent());
    }

    public function testGetEmployeeNotFound()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->employeeController->getEmployee(1, $request);
    }

    public function testCreateEmployee()
    {
        $this->employeeService->expects($this->once())
            ->method('create')
            ->with(['name' => 'John Doe'])
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $response = $this->employeeController->createEmployee($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['employee' => ['id' => 1, 'name' => 'John Doe']], $response->getContent());
    }

    public function testUpdateEmployee()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->employeeService->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Jane Doe'])
            ->willReturn(['id' => 1, 'name' => 'Jane Doe']);

        $request = new Request();
        $request->request->set('name', 'Jane Doe');
        $response = $this->employeeController->updateEmployee(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['employee' => ['id' => 1, 'name' => 'Jane Doe']], $response->getContent());
    }

    public function testUpdateEmployeeNotFound()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->employeeController->updateEmployee(1, $request);
    }

    public function testDeleteEmployee()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->employeeService->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->employeeController->deleteEmployee(1, $request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteEmployeeNotFound()
    {
        $this->employeeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->employeeController->deleteEmployee(1, $request);
    }
}