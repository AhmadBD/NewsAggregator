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
                        'source' => $article['source']['name']??null,
                        'author' => $article['author']??null,
                        'title' => $article['title']??null,
                        'description' => $article['description']??null,
                        'url' => $article['url']??null,
                        'image_url' => $article['urlToImage']??null,
                        'content' => $article['content']??null,
                        'published_at' => isset($article['publishedAt'])?Carbon::createFromDate($article['publishedAt']):null,
                        'country_id' => $country?->id,
                        'category_id' => $category?->id,
                    ]
                );
            }
        }
        else{
            if (isset($response->json()['status']) && $response->json()['status'] == 'error') {
                \Log::error($response->json()['message']??'unknown error');
                if (env('APP_DEBUG')) {
                    dd($response->json()['message']??'unknown error');
                }
            }
            $totalResults=0;
        }
        return $totalResults;
    }
}
