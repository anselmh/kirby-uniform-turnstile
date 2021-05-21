<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__.'/vendor/autoload.php';

Kirby::plugin('leitsch/uniform-hcaptcha', [
  'options' => [
    'siteKey' => '',
    'secretKey' => '',
    'size' => 'normal',
  ],
  'translations' => [
    'en' => @include_once __DIR__.'/i18n/en.php',
    'de' => @include_once __DIR__.'/i18n/de.php',
  ],
]);
