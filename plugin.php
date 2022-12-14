<?php

declare(strict_types=1);

/**
 * Copyright (c) Sergey Romanenko (https://awilum.github.io)
 *
 * Licensed under The MIT License.
 *
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 */

namespace Flextype\Plugin\Indexnow;

use Flextype\Plugin\Indexnow\Console\Commands\Indexnow\IndexnowCommand;
use function Flextype\console;
use function Flextype\registry;
use function Flextype\emitter;
use function Flextype\entries;
use function Flextype\fetch;

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
 * @param string $id Entry ID.
 */
function indexnow(string $id) {
    $settings = registry()->get('plugins.indexnow.settings');

    foreach ($settings['engines'] as $name => $engine) {
        $result[$name] = fetch($engine['url'] . '?url=' . $settings['site_url'] . '/' . $id . (($settings['trailing_slash'] == true) ? '/' : '') . '&key=' . $settings['key']);
    }

    return $result;
}

/** 
 * Add events listeners
 */
emitter()->addListener('onEntriesCreate', fn () => indexnow(entries()->registry()->get('methods.create.params.id')));
emitter()->addListener('onEntriesDelete', fn () => indexnow(entries()->registry()->get('methods.delete.params.id')));
emitter()->addListener('onEntriesCopy', fn () => indexnow(entries()->registry()->get('methods.copy.params.id')));
emitter()->addListener('onEntriesMove', fn () => indexnow(entries()->registry()->get('methods.move.params.id')));
emitter()->addListener('onEntriesUpdate', fn () => indexnow(entries()->registry()->get('methods.update.params.id')));