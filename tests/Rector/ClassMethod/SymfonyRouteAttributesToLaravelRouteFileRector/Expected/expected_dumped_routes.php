<?php

declare(strict_types=1);

\Illuminate\Support\Facades\Route::get('/some', \TomasVotruba\Laravelize\Tests\Rector\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector\Fixture\SomeController::class)->name('some');
