<?php

namespace App\NewsSources;


trait FetchesNews
{
    protected static $maxPageSize = 10;

    public static function fetch(string|null $countryCode, string|null $categoryName, string $date,$limit=100): void
    {
        $pages=intval($limit/ static::$maxPageSize);
        $pageSize= min($limit, static::$maxPageSize);
        $page=1;
        //date must be in iso 8601 format (use whole month)
        $dateFrom = date('Y-m-d', strtotime($date.' -1 month'));
        $dateTo = date('Y-m-d', strtotime($date . ' +1 day'));
        $country = $countryCode?\App\Models\Country::where('code',$countryCode)->first():null;
        $category = $categoryName?\App\Models\Category::firstWhere(['name' => $categoryName]):null;

        $totalResults= static::fetchPage($page, $dateFrom, $dateTo, $pageSize, $country, $category);
        $page++;
        while (($totalResults > $pageSize * $page)&&($page<$pages)){
            //create job for each page
            dispatch(function () use ($page,$dateFrom, $dateTo, $pageSize, $country, $category) {
                static::fetchPage($page,  $dateFrom, $dateTo, $pageSize, $country, $category);
            });
            $page++;
        }
    }
    abstract protected static function fetchPage(int $page, string $dateFrom, string $dateTo, mixed $pageSize, $country, $category): int;
}
