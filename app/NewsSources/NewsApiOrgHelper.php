<?php

namespace App\NewsSources;

use Carbon\Carbon;

class NewsApiOrgHelper
{
    public static function fetch(string $countryCode, string $categoryName, string $date,$limit=100): void
    {
        $pages=intval($limit/100);
        $pageSize= min($limit, 100);
        $page=0;
        do {
            //date must be in iso 8601 format (use whole day)
            $dateFrom = date('Y-m-d', strtotime($date));
            $dateTo = date('Y-m-d', strtotime($date . ' +1 day'));
            $page++;
            $url = "https://newsapi.org/v2/top-headlines?country=$countryCode&category=$categoryName&from=$dateFrom&to=$dateTo&page=$page&pageSize=$pageSize&apiKey=" . config('news_sources.newsapiorg.api_key');
            $response = \Http::get($url);
            $articles = $response->json()['articles'];
            $totalResults = $response->json()['totalResults'];
            foreach ($articles as $article) {
                $country = \App\Models\Country::where('code',$countryCode)->firstOrFail();
                $category = \App\Models\Category::firstOrCreate(['name' => $categoryName]);
                \App\Models\Article::firstOrCreate(
                    [
                        'source' => $article['source']['name'],
                        'author' => $article['author'],
                        'title' => $article['title'],
                        'description' => $article['description'],
                        'url' => $article['url'],
                        'image_url' => $article['urlToImage'],
                        'content' => $article['content'],
                        'published_at' => Carbon::createFromDate($article['publishedAt']),
                        'country_id' => $country->id,
                        'category_id' => $category->id,
                    ]
                );
            }
            sleep(1);//to avoid rate limit
        }while (($totalResults > $pageSize * $page)&&($page<$pages));
    }
}
