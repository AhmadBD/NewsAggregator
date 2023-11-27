<?php

namespace App\Jobs\Helpers;

use App\Jobs\FetchNewsJob;
use App\Models\Category;
use App\Models\Country;
use App\NewsSources\NewsApiOrgHelper;
use App\NewsSources\NyTimesApiHelper;
use App\NewsSources\TheGuardianApiHelper;

class HourlyCommand
{
    public static function fetchNewsHourlyCommand($dataSize=100): void
    {
        $availableCountries = config('news_sources.available_countries', 'en,us');
        $availableCountries = explode(',', $availableCountries);
        foreach (Country::query()->select('code')->whereIn('code', $availableCountries)->get() as $country) {
            $theGuardianApiHelper = new TheGuardianApiHelper();
            (new FetchNewsJob($theGuardianApiHelper, $country->code, null, today(), $dataSize))
                ->dispatch($theGuardianApiHelper, $country->code, null, today(), $dataSize);
            $nyTimesHelper = new NyTimesApiHelper();
            (new FetchNewsJob($nyTimesHelper, $country->code, null, today(), $dataSize))
                ->dispatch($nyTimesHelper, $country->code, null, today(), $dataSize);
            foreach (Category::query()->select('name')->get() as $category) {
                $newsApiOrgHelper = new NewsApiOrgHelper();
                (new FetchNewsJob($newsApiOrgHelper, $country->code, $category->name, today(), $dataSize))
                    ->dispatch($newsApiOrgHelper, $country->code, $category->name, today(), $dataSize);
            }
        }
    }
}
