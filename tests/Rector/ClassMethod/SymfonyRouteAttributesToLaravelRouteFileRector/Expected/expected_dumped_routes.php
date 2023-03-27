<?php

\Illuminate\Support\Facades\Route::get('/some', \TomasVotruba\Laravelize\Tests\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector\Fixture\SomeController::class)->name('some');
