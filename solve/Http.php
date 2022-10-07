<?php

namespace Solve;

class Http extends \Exception
{
    public function run($envFile): void
    {
        try {
            Env::run($envFile);
            Error::debug();
            Register::run();
            Route::run();
        } catch (\Exception $e) {
            include 'solve/page/error.php';
        }
    }
}