<?php

namespace Uniform\Guards;

use ErrorException;
use Kirby\Http\Remote;
use Kirby\Http\Request;
use Uniform\Exceptions\Exception;

class HcaptchaGuard extends Guard
{
    /**
     * hCaptcha HTML input field name
     *
     * @var string
     */
    const fieldName = 'h-captcha-response';

    /**
     * URL for the hCaptcha verification
     *
     * @var string
     */
    const verificationUrl = 'https://hcaptcha.com/siteverify';

    /**
     * {@inheritDoc}
     *
     * Verify the hCaptcha challenge
     * Remove the field from the form data if it was correct
     */
    public function perform()
    {
        $hcaptchaChallenge = kirby()->request()->get(self::fieldName);

        if (empty($hcaptchaChallenge)) {
            $this->reject(t('uniform-hcaptcha-empty'), self::fieldName);
        }

        $secretKey = option('leitsch.uniform-hcaptcha.secretKey');

        if (empty($secretKey)) {
            throw new Exception('The hCaptcha secret key for Uniform is not configured');
        }

        $response = Remote::request(self::verificationUrl, [
          'method' => 'POST',
          'data' => [
            'secret' => $secretKey,
            'response' => $hcaptchaChallenge,
          ],
        ]);

        if ($response->code() !== 200 || $response->json()['success'] !== true) {
            $this->reject(t('uniform-hcaptcha-invalid'), self::fieldName);
        }

        $this->form->forget(self::fieldName);
    }
}
