<?php

use Kirby\Cms\App as Kirby;

if (is_file(__DIR__.'/vendor/autoload.php')) {
  require_once __DIR__.'/vendor/autoload.php';
}

// Load the guard lazily: it extends a Uniform class, which may not be loaded
// yet depending on the plugin folder name (plugins load alphabetically).
spl_autoload_register(function (string $class) {
  if ($class === 'Uniform\\Guards\\TurnstileGuard') {
    require_once __DIR__.'/src/Guards/TurnstileGuard.php';
  }
});

require_once __DIR__.'/src/helpers.php';

Kirby::plugin('anselmh/uniform-turnstile', [
  'options' => [
    'siteKey' => '',
    'secretKey' => '',
    'theme' => 'auto',
    'timeout' => 10,
  ],
  'translations' => [
    'en' => require __DIR__.'/i18n/en.php',
    'de' => require __DIR__.'/i18n/de.php',
    'fr' => require __DIR__.'/i18n/fr.php',
  ],
]);
