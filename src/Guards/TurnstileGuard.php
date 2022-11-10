<?php

namespace Uniform\Guards;

use Kirby\Http\Remote;
use Uniform\Exceptions\Exception;

class TurnstileGuard extends Guard
{
    /**
     * Turnstile HTML input field name
     *
     * @var string
     */
    const fieldName = 'turnstile-response';

    /**
     * URL for the Turnstile verification
     *
     * @var string
     */
    const verificationUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * {@inheritDoc}
     *
     * Verify the turnstile challenge
     * Remove the field from the form data if it was correct
     */
    public function perform()
    {
        $turnstileChallenge = kirby()->request()->get(self::fieldName);

        if (empty($turnstileChallenge)) {
            $this->reject(t('uniform-turnstile-empty'), self::fieldName);
        }

        $secretKey = option('anselmh.uniform-turnstile.secretKey');

        if (empty($secretKey)) {
            throw new Exception('The Turnstile secret key for Uniform is not configured');
        }

        $response = Remote::request(self::verificationUrl, [
          'method' => 'POST',
          'data' => [
            'secret' => $secretKey,
            'response' => $turnstileChallenge,
          ],
        ]);

        if ($response->code() !== 200 || $response->json()['success'] !== true) {
            $this->reject(t('uniform-turnstile-invalid'), self::fieldName);
        }

        $this->form->forget(self::fieldName);
    }
}
