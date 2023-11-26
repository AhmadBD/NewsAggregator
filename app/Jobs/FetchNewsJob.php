<?php

namespace App\Jobs;

use App\NewsSources\FetchesNewsInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private FetchesNewsInterface $newsFetcher;
    private string|null $countryCode;
    private string|null $categoryName;
    private string $date;
    private int $limit;
    /**
     * Create a new job instance.
     */
    public function __construct($newsFetcher, $countryCode, $categoryName, $date, $limit)
    {
        $this->newsFetcher = $newsFetcher;
        $this->countryCode = $countryCode;
        $this->categoryName = $categoryName;
        $this->date = $date;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->newsFetcher->fetch($this->countryCode, $this->categoryName, $this->date, $this->limit);
    }
}
