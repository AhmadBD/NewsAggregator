<?php

namespace App\NewsSources;


use App\Jobs\FetchPageJob;

trait FetchesNews
{
    protected $maxPageSize = 10;

    public function fetch(string|null $countryCode, string|null $categoryName, string $date,$limit=100): void
    {
        $pages=intval($limit/ static::getMaxPageSize());
        $pageSize= min($limit, $this->getMaxPageSize());
        $page=1;
        //date must be in iso 8601 format (use whole month)
        $dateFrom = date('Y-m-d', strtotime($date.' -1 month'));
        $dateTo = date('Y-m-d', strtotime($date . ' +1 day'));
        $country = $countryCode?\App\Models\Country::where('code',$countryCode)->first():null;
        $category = $categoryName?\App\Models\Category::firstWhere(['name' => $categoryName]):null;

        $totalResults= $this->fetchPage($page, $dateFrom, $dateTo, $pageSize, $country, $category);
        $page++;
        while (($totalResults > $pageSize * $page)&&($page<$pages)){
            //create job for each page
            (new FetchPageJob($this,$page, $dateFrom, $dateTo, $pageSize, $country, $category))
                ->dispatch($this,$page, $dateFrom, $dateTo, $pageSize, $country, $category);
            $page++;
        }
    }
    abstract protected function fetchPage(int $page, string $dateFrom, string $dateTo, mixed $pageSize, $country, $category): int;
    public function getMaxPageSize(): int
    {
        return $this->maxPageSize;
    }
}
