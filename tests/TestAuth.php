<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Login;
use App\Models\Register;
use App\Models\DatabaseConnection;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

class TestAuth extends TestCase
{
    /**
     * @test
     */
    public function test_login_success()
    {
        // Mock database connection
        $dbConnection = Mockery::mock(DatabaseConnection::class);
        $dbConnection->shouldReceive('connect')->andReturn(true);
        $dbConnection->shouldReceive('query')->andReturn(true);

        // Mock login model
        $login = Mockery::mock(Login::class);
        $login->shouldReceive('checkCredentials')->andReturn(true);

        // Test login routine
        $result = $login->login('username', 'password', $dbConnection);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function test_login_failure()
    {
        // Mock database connection
        $dbConnection = Mockery::mock(DatabaseConnection::class);
        $dbConnection->shouldReceive('connect')->andReturn(true);
        $dbConnection->shouldReceive('query')->andReturn(false);

        // Mock login model
        $login = Mockery::mock(Login::class);
        $login->shouldReceive('checkCredentials')->andReturn(false);

        // Test login routine
        $result = $login->login('username', 'password', $dbConnection);
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function test_register_success()
    {
        // Mock database connection
        $dbConnection = Mockery::mock(DatabaseConnection::class);
        $dbConnection->shouldReceive('connect')->andReturn(true);
        $dbConnection->shouldReceive('query')->andReturn(true);

        // Mock register model
        $register = Mockery::mock(Register::class);
        $register->shouldReceive('registerUser')->andReturn(true);

        // Test register routine
        $result = $register->register('username', 'email', 'password', $dbConnection);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function test_register_failure()
    {
        // Mock database connection
        $dbConnection = Mockery::mock(DatabaseConnection::class);
        $dbConnection->shouldReceive('connect')->andReturn(true);
        $dbConnection->shouldReceive('query')->andReturn(false);

        // Mock register model
        $register = Mockery::mock(Register::class);
        $register->shouldReceive('registerUser')->andReturn(false);

        // Test register routine
        $result = $register->register('username', 'email', 'password', $dbConnection);
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function test_session_login_success()
    {
        // Mock database connection
        $dbConnection = Mockery::mock(DatabaseConnection::class);
        $dbConnection->shouldReceive('connect')->andReturn(true);
        $dbConnection->shouldReceive('query')->andReturn(true);

        // Mock login model
        $login = Mockery::mock(Login::class);
        $login->shouldReceive('checkCredentials')->andReturn(true);

        // Mock session
        $session = Mockery::mock('Illuminate\Session\Store');
        $session->shouldReceive('has')->andReturn(false);
        $session->shouldReceive('put')->andReturn(true);

        // Test session login routine
        $result = $login->sessionLogin('username', 'password', $dbConnection, $session);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function test_session_login_failure()
    {
        // Mock database connection
        $dbConnection = Mockery::mock(DatabaseConnection::class);
        $dbConnection->shouldReceive('connect')->andReturn(true);
        $dbConnection->shouldReceive('query')->andReturn(false);

        // Mock login model
        $login = Mockery::mock(Login::class);
        $login->shouldReceive('checkCredentials')->andReturn(false);

        // Mock session
        $session = Mockery::mock('Illuminate\Session\Store');
        $session->shouldReceive('has')->andReturn(false);
        $session->shouldReceive('put')->andReturn(false);

        // Test session login routine
        $result = $login->sessionLogin('username', 'password', $dbConnection, $session);
        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- Successful login
- Failed login
- Successful registration
- Failed registration
- Successful session login
- Failed session login

Each test case uses Mockery to mock the database connection and the login/register models. The test cases then assert the expected results from the login/register routines.