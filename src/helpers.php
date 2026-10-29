<?php

use Kirby\Toolkit\Html;
use Uniform\Exceptions\Exception as UniformException;

if (!function_exists('turnstileField')) {
    /**
     * Render the Turnstile widget container.
     *
     * Optional attributes (rendered as `data-*`): theme, action, cdata, size,
     * appearance, language, callback, error-callback, expired-callback.
     */
    function turnstileField(array $attributes = []): string
    {
        $siteKey = option('anselmh.uniform-turnstile.siteKey');

        if (empty($siteKey)) {
            throw new UniformException('The Turnstile sitekey for Uniform is not configured');
        }

        $attributes += ['theme' => option('anselmh.uniform-turnstile.theme', 'auto')];

        if (!in_array($attributes['theme'], ['light', 'dark', 'auto'], true)) {
            $attributes['theme'] = 'auto';
        }

        $data = ['class' => 'cf-turnstile', 'data-sitekey' => $siteKey];

        foreach ($attributes as $name => $value) {
            if ($value !== null && $value !== '') {
                $data['data-' . $name] = $value;
            }
        }

        return '<div ' . Html::attr($data) . '></div>';
    }
}

if (!function_exists('turnstileScript')) {
    function turnstileScript(): string
    {
        return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    }
}
