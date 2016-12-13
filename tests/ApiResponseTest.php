<?php

class ApiResponseTest extends TestCase
{
    /** @test */
    public function it_returns_with_an_array()
    {
        $response = $this->apiResponse->withArray(['foo' => 'bar']);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(['foo' => 'bar'], json_decode($response->getContent(), true));
    }
}