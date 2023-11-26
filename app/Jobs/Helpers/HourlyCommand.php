<?php

namespace App\Jobs\Helpers;

use App\Jobs\FetchNewsJob;
use App\Models\Category;
use App\Models\Country;
use App\NewsSources\NewsApiOrgHelper;
use App\NewsSources\TheGuardianApiHelper;

class HourlyCommand
{
    public static function fetchNewsHourlyCommand($dataSize=1000): void
    {
        foreach (Country::query()->select('code')->get() as $country) {
            $theGuardianApiHelper = new TheGuardianApiHelper();
            (new FetchNewsJob($theGuardianApiHelper, $country->code, null, today(), $dataSize))
                ->dispatch($theGuardianApiHelper, $country->code, null, today(), $dataSize);
            foreach (Category::query()->select('name')->get() as $category) {
                $newsApiOrgHelper = new NewsApiOrgHelper();
                (new FetchNewsJob($newsApiOrgHelper, $country->code, $category->name, today(), $dataSize))
                    ->dispatch($newsApiOrgHelper, $country->code, $category->name, today(), $dataSize);
            }
        }
    }
}
