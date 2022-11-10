<?php

use Uniform\Exceptions\Exception as UniformException;

if (!function_exists('turnstileField')) {
    function turnstileField(): string
    {
        $siteKey = option('anselmh.uniform-turnstile.siteKey');

        if (empty($siteKey)) {
            throw new UniformException('The Turnstile sitekey for Uniform is not configured');
        }

        return '<div class="cf-turnstile" data-sitekey="'.$siteKey.'" data-callback="javascriptCallback"></div>';
    }
}

if (!function_exists('turnstileScript')) {
    function turnstileScript(): string
    {
        return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    }
}


