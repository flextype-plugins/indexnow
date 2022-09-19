<?php

declare(strict_types=1);

namespace Flextype\Plugin\Indexnow;

use Flextype\Plugin\Indexnow\Console\Commands\Indexnow\IndexnowCommand;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function Flextype\console;
use function Flextype\registry;
use function Flextype\emitter;
use function Flextype\cache;
use function Flextype\app;
use function Flextype\fetch;
use function Glowy\Strings\strings;

/**
 * Ensure vendor libraries exist
 */
! is_file($indexnowAutoload = __DIR__ . '/vendor/autoload.php') and exit('Please run: <i>composer install</i> for indexnow plugin');

/**
 * Register The Auto Loader
 *
 * Composer provides a convenient, automatically generated class loader for
 * our application. We just need to utilize it! We'll simply require it
 * into the script here so that we don't have to worry about manual
 * loading any of our classes later on. It feels nice to relax.
 * Register The Auto Loader
 */
$indexnowLoader = require_once $indexnowAutoload;

// Add indexnow console command
console()->add(new IndexnowCommand());

/** 
 * Indexnow
 */
function indexnow(string $id) {
    $settings = registry()->get('plugins.indexnow.settings');

    foreach ($settings['engines'] as $name => $engine) {
        $result[$name] = fetch($engine['url'] . '?url=' . registry()->get('plugins.indexnow.settings.site_url') . '/' . $id . (registry()->get('plugins.indexnow.settings.trailing_slash') ? '/' : '') . '&key=' . $settings['key']);
    }

    return $result;
}


emitter()->addListener('onEntriesCreate', fn () => indexnow(entries()->registry()->get('methods.create.params.id')));
emitter()->addListener('onEntriesDelete', fn () => indexnow(entries()->registry()->get('methods.delete.params.id')));
emitter()->addListener('onEntriesCopy', fn () => indexnow(entries()->registry()->get('methods.copy.params.id')));
emitter()->addListener('onEntriesMove', fn () => indexnow(entries()->registry()->get('methods.move.params.id')));
emitter()->addListener('onEntriesUpdate', fn () => indexnow(entries()->registry()->get('methods.update.params.id')));