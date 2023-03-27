<?php

declare(strict_types=1);

use App\Console\ConsoleKernel;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Debug\ExceptionHandler;

$application = new Application($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));

$application->singleton(Illuminate\Contracts\Console\Kernel::class,ConsoleKernel::class);

$application->singleton(
    ExceptionHandler::class,
    \App\Exceptions\ExceptionHandler::class
);

return $application;
