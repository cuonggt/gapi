<?php

class ApiResponseTest extends TestCase
{
    /** @test */
    public function it_can_returns_with_an_array()
    {
        $response = $this->apiResponse->withArray(['foo' => 'bar']);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(['foo' => 'bar'], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_can_returns_with_an_error()
    {
        $response = $this->apiResponse->withError('test', 'GEN-CUSTOM-ERROR-CODE');

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
}
