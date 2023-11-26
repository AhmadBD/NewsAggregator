<?php

namespace Tests;

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DatabaseSeederForTesting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $seedDatabase = true;

    protected function setUp(): void
    {
        parent::setUp();
        if($this->seedDatabase)
            (new DatabaseSeederForTesting())->run();
    }

}
