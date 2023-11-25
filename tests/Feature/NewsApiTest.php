<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/news?country=us&category=business');
        $response->assertStatus(200);
        $news = $response->json();
        $this->assertArrayHasKey('status', $news);
        $this->assertArrayHasKey('articles', $news);
        $this->assertArrayHasKey('totalResults', $news);
        $this->assertArrayHasKey('page', $news);
        $this->assertArrayHasKey('pageSize', $news);

    }
}
