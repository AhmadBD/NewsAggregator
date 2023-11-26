<?php

namespace App\NewsSources;

use Carbon\Carbon;

class NewsApiOrgHelper implements FetchesNewsInterface
{
    use FetchesNews;

    /**
     * @param int $page
     * @param string $dateFrom
     * @param string $dateTo
     * @param mixed $pageSize
     * @param $country
     * @param $category
     * @return int
     */
    public function fetchPage(int $page,  string $dateFrom, string $dateTo, mixed $pageSize, $country, $category): int
    {
        $url = "https://newsapi.org/v2/top-headlines?from=$dateFrom&to=$dateTo&page=$page&pageSize=$pageSize&apiKey=" . config('news_sources.newsapiorg.api_key');
        if($country){
            $url.="&country=".$country->code;
        }
        if ($category) {
            $url.="&category=".$category->name;
        }
        $response = \Http::get($url);
        if($response->json()['status']=='ok'){
            $articles = $response->json()['articles'];
            $totalResults = $response->json()['totalResults'];
            foreach ($articles as $article) {
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
                        'country_id' => $country?->id,
                        'category_id' => $category?->id,
                    ]
                );
            }
        }
        else{
            if ($response->json()['status']=='error') {
                \Log::error($response->json()['message']);
                if (env('APP_DEBUG')) {
                    dd($response->json()['message']);
                }
            }
            $totalResults=0;
        }
        return $totalResults;
    }
}
