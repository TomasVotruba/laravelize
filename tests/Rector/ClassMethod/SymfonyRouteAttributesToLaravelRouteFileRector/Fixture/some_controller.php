<?php

namespace TomasVotruba\Laravelize\Tests\Rector\Rector\ClassMethod\SymfonyRouteAttributesToLaravelRouteFileRector\Fixture;

use Symfony\Component\Routing\Annotation\Route;

class SomeController
{
    #[Route(path: '/some', name: 'some')]
    public function __invoke()
    {
        $this->someMethod();
    }
}

?>
