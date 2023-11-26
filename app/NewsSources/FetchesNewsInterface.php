<?php

namespace App\NewsSources;


interface FetchesNewsInterface
{
    public static function fetch(string $countryCode, string $categoryName, string $date,$limit=100): void;
    public static function fetchPage(int $page, string $dateFrom, string $dateTo, mixed $pageSize, $country, $category): int;
}
