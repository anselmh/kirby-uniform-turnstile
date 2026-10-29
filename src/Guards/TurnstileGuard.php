<?php

namespace Uniform\Guards;

use Kirby\Http\Remote;
use Throwable;
use Uniform\Exceptions\Exception;

class TurnstileGuard extends Guard
{
    /**
     * Turnstile HTML input field name
     *
     * @var string
     */
    const fieldName = 'cf-turnstile-response';

    /**
     * URL for the Turnstile verification
     *
     * @var string
     */
    const verificationUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Maximum token length accepted by Cloudflare
     *
     * @var int
     */
    const maxTokenLength = 2048;

    /**
     * {@inheritDoc}
     *
     * Verify the turnstile challenge
     * Remove the field from the form data if it was correct
     *
     * Supported guard options:
     * - action: if set, the action of the verified token must match
     */
    public function perform()
    {
        $secretKey = option('anselmh.uniform-turnstile.secretKey');

        if (empty($secretKey)) {
            throw new Exception('The Turnstile secret key for Uniform is not configured');
        }

        $token = kirby()->request()->get(self::fieldName);

        if (!is_string($token) || $token === '') {
            $this->reject(t('uniform-turnstile-empty'), self::fieldName);
        }

        if (strlen($token) > self::maxTokenLength) {
            $this->reject(t('uniform-turnstile-invalid'), self::fieldName);
        }

        $data = [
            'secret' => $secretKey,
            'response' => $token,
        ];

        if ($ip = kirby()->visitor()->ip()) {
            $data['remoteip'] = $ip;
        }

        $result = $this->verify($data);

        if (!is_array($result)) {
            $this->reject(t('uniform-turnstile-unavailable'), self::fieldName);
        }

        $errors = $result['error-codes'] ?? [];

        if (is_array($errors) && array_intersect($errors, ['missing-input-secret', 'invalid-input-secret'])) {
            throw new Exception('The Turnstile secret key for Uniform is invalid');
        }

        if (($result['success'] ?? false) !== true) {
            $this->reject(t('uniform-turnstile-invalid'), self::fieldName);
        }

        $action = $this->option('action');

        if ($action !== null && ($result['action'] ?? null) !== $action) {
            $this->reject(t('uniform-turnstile-invalid'), self::fieldName);
        }

        $this->form->forget(self::fieldName);
    }

    /**
     * Send the token to Cloudflare.
     *
     * @return array|null The decoded response or null if it could not be retrieved
     */
    protected function verify(array $data): ?array
    {
        try {
            $response = Remote::request(self::verificationUrl, [
                'method' => 'POST',
                'data' => $data,
                'timeout' => (int) option('anselmh.uniform-turnstile.timeout', 10),
            ]);
            $result = $response->code() === 200 ? $response->json() : null;
        } catch (Throwable) {
            return null;
        }

        return is_array($result) ? $result : null;
    }
}
