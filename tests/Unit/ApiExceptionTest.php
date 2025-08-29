<?php

namespace Tests\Unit;

use App\Exceptions\ApiException;
use Tests\TestCase;

class ApiExceptionTest extends TestCase
{
    public function test_api_exception_has_correct_properties()
    {
        $exception = new ApiException(
            '/test/endpoint',
            'Test error message',
            'TestService',
            500
        );

        $this->assertEquals('/test/endpoint', $exception->getEndpoint());
        $this->assertEquals(500, $exception->getStatusCode());
        $this->assertEquals('TestService', $exception->getServiceName());
        $this->assertStringContainsString('TestService', $exception->getMessage());
        $this->assertStringContainsString('/test/endpoint', $exception->getMessage());
        $this->assertStringContainsString('Test error message', $exception->getMessage());
    }

    public function test_api_exception_without_status_code()
    {
        $exception = new ApiException(
            '/test/endpoint',
            'Test error message',
            'TestService'
        );

        $this->assertNull($exception->getStatusCode());
        $this->assertStringNotContainsString('Status code', $exception->getMessage());
    }

    public function test_api_exception_with_previous_exception()
    {
        $previousException = new \Exception('Previous error');
        $exception = new ApiException(
            '/test/endpoint',
            'Test error message',
            'TestService',
            500,
            $previousException
        );

        $this->assertEquals($previousException, $exception->getPrevious());
    }

    public function test_spoonacular_exception_factory_method()
    {
        $exception = ApiException::spoonacular(
            '/recipes/search',
            'API key invalid',
            401
        );

        $this->assertEquals('/recipes/search', $exception->getEndpoint());
        $this->assertEquals(401, $exception->getStatusCode());
        $this->assertEquals('Spoonacular', $exception->getServiceName());
        $this->assertStringContainsString('Spoonacular', $exception->getMessage());
        $this->assertStringContainsString('API key invalid', $exception->getMessage());
    }

    public function test_spoonacular_exception_without_status_code()
    {
        $exception = ApiException::spoonacular(
            '/recipes/search',
            'Network error'
        );

        $this->assertNull($exception->getStatusCode());
        $this->assertEquals('Spoonacular', $exception->getServiceName());
    }

    public function test_api_exception_message_format()
    {
        $exception = new ApiException(
            '/api/v1/users',
            'User not found',
            'UserService',
            404
        );

        $message = $exception->getMessage();
        
        $this->assertStringContainsString('API call failed to UserService service endpoint: /api/v1/users', $message);
        $this->assertStringContainsString('(Status code: 404)', $message);
        $this->assertStringContainsString('User not found', $message);
    }

    public function test_api_exception_with_default_values()
    {
        $exception = new ApiException('/test');

        $this->assertEquals('/test', $exception->getEndpoint());
        $this->assertStringContainsString('API request failed', $exception->getMessage());
        $this->assertEquals('unknown', $exception->getServiceName());
        $this->assertNull($exception->getStatusCode());
    }
}
