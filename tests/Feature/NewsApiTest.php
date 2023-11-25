<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use App\Models\Category;
use App\Models\Country;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Article::factory()->count(11)->create([
            'country_id' => Country::whereCode('us')->first()->id,
            'category_id' => Category::whereName('business')->first()->id]);
        Article::factory()->count(11)->create([
            'country_id' => Country::whereCode('us')->first()->id,
            'category_id' => Category::whereName('entertainment')->first()->id]);
        $response = $this->get('/api/news?country=us&category=business&page=1&pageSize=10');
        $response->assertStatus(200);
        $page = $response->json('current_page');
        $this->assertEquals(1, $page);
        $pageSize = $response->json('per_page');
        $this->assertEquals(10, $pageSize);
        $totalResults = $response->json('total');
        $this->assertEquals(11, $totalResults);
        $news = $response->json('data');
        $this->assertIsArray($news);
        $this->assertGreaterThanOrEqual(10, count($news));
        $this->assertArrayHasKey('title', $news[0]);
        $this->assertArrayHasKey('content', $news[0]);
        $this->assertArrayHasKey('image_url', $news[0]);
        $this->assertArrayHasKey('description', $news[0]);
        $this->assertArrayHasKey('url', $news[0]);
        $this->assertArrayHasKey('published_at', $news[0]);
        $this->assertArrayHasKey('source', $news[0]);
        $this->assertArrayHasKey('author', $news[0]);
        $this->assertArrayHasKey('country_name', $news[0]);
        $this->assertArrayHasKey('category_name', $news[0]);

    }
}
