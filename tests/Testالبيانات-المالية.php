<?php

namespace App\Tests\Controller;

use App\Controller\بيانات_الماليةController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Panther\PantherTestCase;

class Testالبيانات_المالية extends PantherTestCase
{
    private $controller;
    private $pdo;

    protected function setUp(): void
    {
        $this->controller = new بيانات_الماليةController();
        $this->pdo = $this->createMock('PDO');
    }

    public function testGetAll()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM البيانات_المالية')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->getAll($request, $this->pdo);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM البيانات_المالية WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->getById($request, $this->pdo);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $request = new Request();
        $request->request->set('name', 'Test');
        $request->request->set('description', 'Test');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO البيانات_المالية (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->create($request, $this->pdo);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $request->request->set('name', 'Test');
        $request->request->set('description', 'Test');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE البيانات_المالية SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->update($request, $this->pdo);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM البيانات_المالية WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->delete($request, $this->pdo);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetByIdNotFound()
    {
        $request = new Request();
        $request->attributes->set('id', 1);
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM البيانات_المالية WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getById($request, $this->pdo);
    }
}


Note: The above code assumes that the `بيانات_الماليةController` class has the necessary methods for CRUD operations and that the `PDO` class is being mocked for testing purposes. The actual implementation may vary based on the specific requirements of the project.