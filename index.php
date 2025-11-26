<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__.'/vendor/autoload.php';

@include_once __DIR__.'/src/Guards/TurnstileGuard.php';
@include_once __DIR__.'/src/helpers.php';

Kirby::plugin('anselmh/uniform-turnstile', [
  'options' => [
    'siteKey' => '',
    'secretKey' => '',
    'theme' => ''
  ],
  'translations' => [
    'en' => @include_once __DIR__.'/i18n/en.php',
    'de' => @include_once __DIR__.'/i18n/de.php',
  ],
]);
