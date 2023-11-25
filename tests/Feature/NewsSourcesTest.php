<?php

namespace Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use App\Models\Category;
use App\Models\Country;
use App\NewsSources\NewsApiOrgHelper;
use Tests\TestCase;

class NewsSourcesTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testFetchFromNewsApiOrg(): void
    {
        NewsApiOrgHelper::fetch('us', 'business',today(),10);
        $this->assertGreaterThanOrEqual(1, Article::count());
        $article = Article::first();
        $this->assertNotNull($article->title);
        $this->assertNotNull($article->content);
        $this->assertEquals('us', $article->country->code);
        $this->assertEquals('business', $article->category->name);

    }

}
