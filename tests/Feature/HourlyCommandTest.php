<?php

namespace Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\Helpers\HourlyCommand;
use App\Models\Article;
use App\Models\Category;
use App\Models\Country;
use App\NewsSources\NewsApiOrgHelper;
use App\NewsSources\TheGuardianApiHelper;
use Tests\TestCase;

class HourlyCommandTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testFetchNewsHourlyCommand(): void
    {
        HourlyCommand::fetchNewsHourlyCommand(10);
        //wait for all jobs to finish
        sleep(20);
        $this->assertGreaterThanOrEqual(10, Article::count());
    }

}
