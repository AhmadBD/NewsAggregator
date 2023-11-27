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
    public function testGetNews(): void
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
        $this->assertEquals(10, count($news));
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
    public function testSearchArticles(): void
    {
        $data=Article::factory()->count(11)->create([
            'country_id' => Country::whereCode('us')->first()->id,
            'category_id' => Category::whereName('business')->first()->id]);
        foreach ($data as $i=> $item)
            if($i%2==0) {
                $item->content .= 'article';
                $item->save();
            }
        Article::factory()->count(11)->create([
            'country_id' => Country::whereCode('us')->first()->id,
            'category_id' => Category::whereName('entertainment')->first()->id]);
        $response = $this->get('/api/news?country=us&category=business&page=1&pageSize=10&search=article');
        $response->assertStatus(200);
        $page = $response->json('current_page');
        $this->assertEquals(1, $page);
        $pageSize = $response->json('per_page');
        $this->assertEquals(10, $pageSize);
        $totalResults = $response->json('total');
        $this->assertEquals(6, $totalResults);
        $news = $response->json('data');
        $this->assertIsArray($news);
        $this->assertEquals(6, count($news));
        $this->assertArrayHasKey('title', $news[0]);
        $this->assertArrayHasKey('content', $news[0]);
        $this->assertStringContainsString('article', $news[0]['content']);
        $this->assertArrayHasKey('image_url', $news[0]);
        $this->assertArrayHasKey('description', $news[0]);
        $this->assertArrayHasKey('url', $news[0]);
        $this->assertArrayHasKey('published_at', $news[0]);
        $this->assertArrayHasKey('source', $news[0]);
        $this->assertArrayHasKey('author', $news[0]);
        $this->assertArrayHasKey('country_name', $news[0]);
        $this->assertArrayHasKey('category_name', $news[0]);

    }
    public function testGetCategories(): void
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
        $categories = $response->json();
        $this->assertIsArray($categories);
        $this->assertGreaterThanOrEqual(1, count($categories));
        $this->assertNotNull($categories[0]);
    }
    public function testGetCountries(): void
    {
        $response = $this->get('/api/countries');
        $response->assertStatus(200);
        $countries = $response->json();
        $this->assertIsArray($countries);
        $this->assertGreaterThanOrEqual(1, count($countries));
        $this->assertNotNull($countries[0]);
    }
}
