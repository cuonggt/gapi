<?php

class ApiResponseTest extends TestCase
{
    /** @test */
    public function it_can_returns_with_an_array()
    {
        $response = $this->apiResponse->json(['foo' => 'bar']);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(['foo' => 'bar'], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_an_error()
    {
        $response = $this->apiResponse->withError('test', 'GEN-CUSTOM-ERROR-CODE', 422);

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-CUSTOM-ERROR-CODE',
                'http_code' => 422,
                'message' => 'test',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_a_bad_request_error()
    {
        $response = $this->apiResponse->errorBadRequest('bad request message');

        $this->assertEquals(400, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-BAD-REQUEST',
                'http_code' => 400,
                'message' => 'bad request message',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_an_unauthorized_error()
    {
        $response = $this->apiResponse->errorUnauthorized('unauthorized message');

        $this->assertEquals(401, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-UNAUTHORIZED',
                'http_code' => 401,
                'message' => 'unauthorized message',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_a_forbidden_error()
    {
        $response = $this->apiResponse->errorForbidden('forbidden message');

        $this->assertEquals(403, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-FORBIDDEN',
                'http_code' => 403,
                'message' => 'forbidden message',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_a_not_found_error()
    {
        $response = $this->apiResponse->errorNotFound('not found message');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-NOT-FOUND',
                'http_code' => 404,
                'message' => 'not found message',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_a_method_not_allowed_error()
    {
        $response = $this->apiResponse->errorMethodNotAllowed('method not allowed message');

        $this->assertEquals(405, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-METHOD-NOT-ALLOWED',
                'http_code' => 405,
                'message' => 'method not allowed message',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_a_gone_error()
    {
        $response = $this->apiResponse->errorGone('gone message');

        $this->assertEquals(410, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-GONE',
                'http_code' => 410,
                'message' => 'gone message',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_a_unprocessable_entity_error()
    {
        $response = $this->apiResponse->errorUnprocessableEntity('unprocessable entity message');

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-UNPROCESSABLE-ENTITY',
                'http_code' => 422,
                'message' => 'unprocessable entity message',
            ],
        ], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_a_internal_error()
    {
        $response = $this->apiResponse->errorInternalError('internal error message');

        $this->assertEquals(500, $response->getStatusCode());

        $this->assertEquals([
            'error' => [
                'code' => 'GEN-INTERNAL-ERROR',
                'http_code' => 500,
                'message' => 'internal error message',
            ],
        ], json_decode($response->getContent(), true));
    }
}
