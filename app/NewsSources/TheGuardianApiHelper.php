<?php

namespace App\NewsSources;

use App\Models\Category;
use Carbon\Carbon;

class TheGuardianApiHelper implements FetchesNewsInterface
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
    public function fetchPage(int $page, string $dateFrom, string $dateTo, mixed $pageSize, $country, $category): int
    {
        $url = "https://content.guardianapis.com/search?from-date=$dateFrom&to-date=$dateTo&currentPage=$page&pageSize=$pageSize&show-fields=thumbnail,body&api-key=" . config('news_sources.guardian.api_key');
        if($country){
            $url.="&q=".$country->name;
        }
        if($category){
            //no specific category in the guardian api
            if (env('APP_DEBUG')) {
                dd('no specific category in the guardian api');
            }
        }
        $response = \Http::get($url);
        $data = $response->json()['response'];
        if($data['status']=='ok'){
            $articles = $data['results'];
            $totalResults = $data['total'];
            foreach ($articles as $article) {
                $detectedCategory = $this->getCategory($article['sectionId']);
                \App\Models\Article::firstOrCreate(
                    [
                        'source' => 'The Guardian',
//                        'author' => $article['author'],
                        'title' => $article['webTitle'],
//                        'description' => $article['description'], no description in the guardian api
                        'url' => $article['webUrl'],
                        'image_url' => $article['fields']['thumbnail'],
                        'content' => $article['fields']['body'],
                        'published_at' => Carbon::createFromDate($article['webPublicationDate']),
                        'country_id' => $country?->id,
                        'category_id' => $detectedCategory?Category::query()->where('name', $detectedCategory)->first()?->id:null,
                        'sub_category'=>$article['sectionId']
                    ]
                );
            }
        }
        else{
            if ($data['status']=='error') {
                \Log::error($data['message']);
                if (env('APP_DEBUG')) {
                    dd($data['message']);
                }
            }
            $totalResults=0;
        }
        return $totalResults;
    }
    private static $categoryMap=[
        'business'=>['business','better-business','business-to-business','small-business-network'],
        'entertainment'=>['culture','film','games','music','stage','artanddesign','books','dance','fashion','food','media','money','travel','tv-and-radio','lifeandstyle'],
        'general'=>['news','politics','world','uk-news','us-news','australia-news','environment','education','society','global-development','inequality','cities','law','voluntary-sector-network'],
        'health'=>['lifeandstyle', 'healthcare-network','wellness'],
        'science'=>['science'],
        'sports'=>['sport','football'],
        'technology'=>['technology']
    ];
    private function getCategory($subCategory){
        foreach (static::$categoryMap as $category=>$subCategories){
            if(in_array($subCategory,$subCategories)){
                return $category;
            }
        }
        return null;
    }
    public function getMaxPageSize(): int
    {
        return 100;
    }

}
