<?php

namespace App\NewsSources;

use App\Models\Category;
use Carbon\Carbon;

class NyTimesApiHelper implements FetchesNewsInterface
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
        $dateFrom= Carbon::parse($dateFrom)->format('Ymd');
        $dateTo= Carbon::parse($dateTo)->format('Ymd');
        $pageFromZero=$page-1;
        $url = "https://api.nytimes.com/svc/search/v2/articlesearch.json?begin_date=$dateFrom&end_date=$dateTo&page=$pageFromZero&api-key=" . config('news_sources.ny_times.api_key');
        if($category){
           if (env('APP_DEBUG')) {
               dd('no specific category in the ny times api');
           }
        }
        if($country){
            if($category)
                $url.=" AND ";
            else
                $url.="&fq=";
            $url.='glocations:("'.$country->name.'")';
        }
        $response = \Http::get($url);
        $data = $response->json()['response'];
        if($response->json()['status']=='OK'){
            $articles = $data['docs'];
            $totalResults = $data['meta']['hits'];
            foreach ($articles as $article) {
                $detectedCategory = isset($article['section_name'])?$this->getCategory($article['section_name']):null;
                \App\Models\Article::firstOrCreate(
                    [
                        'source' => $article['source']??'The New York Times',
                        'author' => trim((($article['person'][0]['firstname']??'').' '.($article['person'][0]['lastname']??'')),' '),
                        'title' => $article['headline']['main']??null,
//                        'description' => $article['description'], no description in the guardian api
                        'url' => $article['web_url']??null,
                        'image_url' => $article['multimedia'][0]['url']??null,
                        'content' => $article['snippet']??null,
                        'published_at' => Carbon::createFromDate($article['pub_date']),
                        'country_id' => $country?->id,
                        'category_id' => $detectedCategory?Category::query()->where('name', $detectedCategory)->first()?->id:null,
                        'sub_category'=>$article['section_name']??null,
                    ]
                );
            }
        }
        else{
            if (isset($response->json()['status'])&&$response->json()['status']=='error') {
                \Log::error($response->json()['response']??'unknown error');
                if (env('APP_DEBUG')) {
                    dd($response->json()['response']??'unknown error');
                }
            }
            $totalResults=0;
        }
        return $totalResults;
    }
    private static $subCategories=[
        "Arts",
        "Automobiles",
        "Autos",
        "Blogs",
        "Books",
        "Booming",
        "Business",
        "Business Day",
        "Corrections",
        "Crosswords & Games",
        "Crosswords/Games",
        "Dining & Wine",
        "Dining and Wine",
        "Editors' Notes",
        "Education",
        "Fashion & Style",
        "Food",
        "Front Page",
        "Giving",
        "Global Home",
        "Great Homes & Destinations",
        "Great Homes and Destinations",
        "Health",
        "Home & Garden",
        "Home and Garden",
        "International Home",
        "Job Market",
        "Learning",
        "Magazine",
        "Movies",
        "Multimedia",
        "Multimedia/Photos",
        "N.Y. / Region",
        "N.Y./Region",
        "NYRegion",
        "NYT Now",
        "National",
        "New York",
        "New York and Region",
        "Obituaries",
        "Olympics",
        "Open",
        "Opinion",
        "Paid Death Notices",
        "Public Editor",
        "Real Estate",
        "Science",
        "Sports",
        "Style",
        "Sunday Magazine",
        "Sunday Review",
        "T Magazine",
        "T:Style",
        "Technology",
        "The Public Editor",
        "The Upshot",
        "Theater",
        "Times Topics",
        "TimesMachine",
        "Today's Headlines",
        "Topics",
        "Travel",
        "U.S.",
        "Universal",
        "UrbanEye",
        "Washington",
        "Week in Review",
        "World",
        "Your Money",
        ];
    private static $categoryMap=[
        'business'=> ['Business','Business Day','Economy','Energy & Environment','Media & Advertising','Media and Advertising','Small Business','Your Money'],
        'entertainment'=> ['Arts','Art & Design','Art and Design','Books','Books and Literature','Dance','Movies','Music','Television','Theater','Theatre'],
        'general'=> ['Blogs','Crosswords & Games','Crosswords/Games','Fashion & Style','Fashion and Style','Multimedia','Multimedia/Photos','N.Y. / Region','N.Y./Region','NYRegion','NYT Now','Obituaries','Open','Paid Death Notices','Public Editor','Sunday Magazine','Sunday Review','T Magazine','T:Style','The Public Editor','The Upshot','Times Topics','TimesMachine','Today\'s Headlines','Topics','Universal','UrbanEye','Week in Review','Your Money'],
        'health'=> ['Health','Research','Science','Well'],
        'science'=> ['Science','Environment','Space & Cosmos'],
        'sports'=> ['Sports','Baseball','College Basketball','College Football','Golf','Hockey','Pro Basketball','Pro Football','Soccer','Tennis'],
        'technology'=> ['Technology','Personal Tech','Personal Technology'],
    ];
    private function getCategory($subCategory){
        foreach (static::$categoryMap as $category=>$subCategories){
            if(in_array($subCategory,$subCategories)){
                return $category;
            }
        }
        return "general";
    }
    public function getMaxPageSize(): int
    {
        return 100;
    }

}
