<?php

use Uniform\Exceptions\Exception as UniformException;

if (!function_exists('turnstileField')) {
    function turnstileField(): string
    {
        $siteKey = option('anselmh.uniform-turnstile.siteKey');

        switch (option('anselmh.uniform-turnstile.theme')) {
            case 'light':
                $theme = 'light';
                break;
            case 'dark':
                $theme = 'dark';
                break;
            default:
                $theme = 'auto';
        }

        if (empty($siteKey)) {
            throw new UniformException('The Turnstile sitekey for Uniform is not configured');
        }

        return '<div class="cf-turnstile" data-sitekey="' . $siteKey . '" data-theme="' . $theme . '" data-callback="javascriptCallback"></div>';
    }
}

if (!function_exists('turnstileScript')) {
    function turnstileScript(): string
    {
        return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    }
}


