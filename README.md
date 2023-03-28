# Laravelize - Make Symfony to Laravel migration faster and easier

## Install

```bash
composer require tomasvotruba/laravelize --dev
```

## Usage

### 1. Migrate PHP code with Rector

```php


```

Add migrate set to your `rector.php`:

```php
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        \TomasVotruba\Laravelize\Enum\SetList::SYMFONY_TO_LARAVEL
    ]);
};
```

Make use of config:

```bash
vendor/bin/rector process src
```

### 2. Migrate Twig to Blade with regexes

```bash
vendor/bin/laravelize twig-to-blade views
```

@todo
