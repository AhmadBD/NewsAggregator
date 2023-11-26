<?php

namespace App\Jobs;

use App\NewsSources\FetchesNewsInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private FetchesNewsInterface $newsFetcher;
    private int $page;
    private string $dateFrom;
    private string $dateTo;
    private mixed $pageSize;
    private $country;
    private $category;


    /**
     * Create a new job instance.
     */
    public function __construct($newsFetcher,int $page,  string $dateFrom, string $dateTo, mixed $pageSize, $country, $category)
    {
        $this->newsFetcher = $newsFetcher;
        $this->page = $page;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->pageSize = $pageSize;
        $this->country = $country;
        $this->category = $category;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->newsFetcher->fetchPage($this->page, $this->dateFrom, $this->dateTo, $this->pageSize, $this->country, $this->category);
    }
}
