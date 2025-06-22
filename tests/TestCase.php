<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // tell phpUnit to refresh database every single time it wants to run tests use  RefreshDatabase trait
    use CreatesApplication, RefreshDatabase;
}
