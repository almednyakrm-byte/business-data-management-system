<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Client;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Driver\PDOConnection;

class Testالبيانات_التسويقية extends TestCase
{
    private $client;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->pdoMock = $this->createMock(PDOConnection::class);
    }

    public function testGetAll(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM البيانات_التسويقية')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->willReturn([]);

        $this->pdoMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $this->client->request('GET', '/بيانات_التسويقية');
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM البيانات_التسويقية WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id])
            ->willReturn([]);

        $this->pdoMock->expects($this->once())
            ->method('fetch')
            ->willReturn([]);

        $this->client->request('GET', '/بيانات_التسويقية/' . $id);
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $data = ['name' => 'test', 'description' => 'test'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO البيانات_التسويقية (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $this->client->request('POST', '/بيانات_التسويقية', [], [], [], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'test', 'description' => 'test'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE البيانات_التسويقية SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(array_merge($data, ['id' => $id]))
            ->willReturn(true);

        $this->client->request('PUT', '/بيانات_التسويقية/' . $id, [], [], [], json_encode($data));
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM البيانات_التسويقية WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id])
            ->willReturn(true);

        $this->client->request('DELETE', '/بيانات_التسويقية/' . $id);
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1. `testGetAll`: Verifies that the `GET /بيانات_التسويقية` endpoint returns a list of all data.
2. `testGetById`: Verifies that the `GET /بيانات_التسويقية/{id}` endpoint returns a single data item by ID.
3. `testCreate`: Verifies that the `POST /بيانات_التسويقية` endpoint creates a new data item.
4. `testUpdate`: Verifies that the `PUT /بيانات_التسويقية/{id}` endpoint updates an existing data item.
5. `testDelete`: Verifies that the `DELETE /بيانات_التسويقية/{id}` endpoint deletes a data item by ID.

Each test method uses the `createMock` method to create a mock PDO object, which is then used to simulate the database interactions. The `expects` method is used to specify the expected behavior of the mock object. The `once` method is used to specify that the expectation should only be met once. The `willReturn` method is used to specify the return value of the mock object.