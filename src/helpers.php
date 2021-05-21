<?php

use Uniform\Exceptions\Exception as UniformException;

if (!function_exists('hcaptchaField')) {
    function hcaptchaField(): string
    {
        $siteKey = option('leitsch.uniform-hcaptcha.siteKey');
        $size = option('leitsch.uniform-hcaptcha.size');

        if (empty($siteKey)) {
            throw new UniformException('The hCaptcha sitekey for Uniform is not configured');
        }

        return '<div class="h-captcha" data-sitekey="'.$siteKey.'" data-size="' . $size . '"></div>';
    }
}

if (!function_exists('hcaptchaScript')) {
    function hcaptchaScript(): string
    {
        return '<script src="https://hcaptcha.com/1/api.js" async defer></script>';
    }
}
