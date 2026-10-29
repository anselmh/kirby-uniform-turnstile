<?php

use Uniform\Exceptions\Exception as UniformException;
use Uniform\Exceptions\PerformerException;
use Uniform\Form;
use Uniform\Guards\TurnstileGuard;

/**
 * Guard that returns a canned Cloudflare response instead of calling the API.
 */
class FakeTurnstileGuard extends TurnstileGuard
{
    public ?array $cloudflareResponse = ['success' => true];
    public ?array $sentData = null;

    protected function verify(array $data): ?array
    {
        $this->sentData = $data;
        return $this->cloudflareResponse;
    }
}

class TurnstileGuardTest extends KirbyTestCase
{
    /**
     * @param string|null $token Value of the submitted Turnstile field (null: not submitted)
     * @param array $options Guard options, e.g. ['action' => 'contact']
     * @param array $config Plugin options, see KirbyTestCase::bootKirby()
     */
    private function guard(?string $token, array $options = [], array $config = ['secretKey' => 'secret']): FakeTurnstileGuard
    {
        $this->bootKirby($config, $token === null ? [] : [TurnstileGuard::fieldName => $token]);

        return new FakeTurnstileGuard(new Form(), $options);
    }

    public function testThrowsConfigurationErrorWithoutSecretKey(): void
    {
        $this->expectException(UniformException::class);
        $this->expectExceptionMessage('secret key');
        $this->guard('token', [], [])->perform();
    }

    public function testRejectsMissingToken(): void
    {
        $this->expectException(PerformerException::class);
        $this->guard(null)->perform();
    }

    public function testRejectsTokenLongerThanCloudflareAllows(): void
    {
        $guard = $this->guard(str_repeat('a', 2049));

        try {
            $guard->perform();
            $this->fail('Expected the token to be rejected');
        } catch (PerformerException) {
            $this->assertNull($guard->sentData, 'Oversized token must not be sent to Cloudflare');
        }
    }

    public function testRejectsWhenCloudflareCannotBeReached(): void
    {
        $guard = $this->guard('token');
        $guard->cloudflareResponse = null;

        $this->expectException(PerformerException::class);
        $guard->perform();
    }

    public function testRejectsFailedChallenge(): void
    {
        $guard = $this->guard('token');
        $guard->cloudflareResponse = ['success' => false, 'error-codes' => ['invalid-input-response']];

        $this->expectException(PerformerException::class);
        $guard->perform();
    }

    public function testThrowsConfigurationErrorForInvalidSecretKey(): void
    {
        $guard = $this->guard('token');
        $guard->cloudflareResponse = ['success' => false, 'error-codes' => ['invalid-input-secret']];

        $this->expectException(UniformException::class);
        $this->expectExceptionMessage('secret key');
        $guard->perform();
    }

    public function testAcceptsValidChallengeAndSendsSecretAndToken(): void
    {
        $guard = $this->guard('token');
        $guard->perform();

        $this->assertSame('secret', $guard->sentData['secret']);
        $this->assertSame('token', $guard->sentData['response']);
    }

    public function testRejectsTokenIssuedForDifferentAction(): void
    {
        $guard = $this->guard('token', ['action' => 'contact']);
        $guard->cloudflareResponse = ['success' => true, 'action' => 'newsletter'];

        $this->expectException(PerformerException::class);
        $guard->perform();
    }

    public function testAcceptsTokenIssuedForExpectedAction(): void
    {
        $guard = $this->guard('token', ['action' => 'contact']);
        $guard->cloudflareResponse = ['success' => true, 'action' => 'contact'];

        $guard->perform();
        $this->addToAssertionCount(1); // reaching this line means nothing was thrown
    }
}
