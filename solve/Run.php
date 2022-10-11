<?php

namespace Solve;

class Run
{
    public static function run($env): void
    {
        Env::run($env);
        Error::run();
        Register::run();
        Route::run();
    }
}