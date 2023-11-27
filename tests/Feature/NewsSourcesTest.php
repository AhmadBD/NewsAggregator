<?php

namespace Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use App\Models\Category;
use App\Models\Country;
use App\NewsSources\NewsApiOrgHelper;
use App\NewsSources\NyTimesApiHelper;
use App\NewsSources\TheGuardianApiHelper;
use Tests\TestCase;

class NewsSourcesTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testFetchFromNewsApiOrg(): void
    {
        (new NewsApiOrgHelper)->fetch('us', 'business',today(),10);
        $this->assertGreaterThanOrEqual(1, Article::count());
        $article = Article::first();
        $this->assertNotNull($article->title);
//        $this->assertNotNull($article->content);
        $this->assertEquals('us', $article->country->code);
        $this->assertEquals('business', $article->category->name);

    }
    public function testFetchFromNewsApiOrg1000(): void
    {
        (new NewsApiOrgHelper)->fetch('us', 'business',today(),40);
        $this->assertGreaterThanOrEqual(1, Article::count());
        $article = Article::first();
        $this->assertNotNull($article->title);
//        $this->assertNotNull($article->content);
        $this->assertEquals('us', $article->country->code);
        $this->assertEquals('business', $article->category->name);

    }
    public function testFetchFromTheGuardian(): void
    {
        (new TheGuardianApiHelper)->fetch('us', null,today(),10);
        $this->assertGreaterThanOrEqual(1, Article::count());
        $article = Article::first();
        $this->assertNotNull($article->title);
//        $this->assertNotNull($article->content);
        $this->assertEquals('us', $article->country->code);
        $this->assertNotNull($article->sub_category);

    }
    public function testFetchFromTheGuardian1000(): void
    {
        (new TheGuardianApiHelper)->fetch('us', null,today(),30);
        $this->assertGreaterThanOrEqual(1, Article::count());
        $article = Article::first();
        $this->assertNotNull($article->title);
//        $this->assertNotNull($article->content);
        $this->assertEquals('us', $article->country->code);
        $this->assertNotNull($article->sub_category);
    }
    public function testFetchFromNewYorkTimes(): void
    {
        (new NyTimesApiHelper())->fetch('us', null,today(),10);
        $this->assertGreaterThanOrEqual(1, Article::count());
        $article = Article::first();
        $this->assertNotNull($article->title);
//        $this->assertNotNull($article->content);
        $this->assertEquals('us', $article->country->code);
        $this->assertNotNull($article->sub_category);

    }
}
