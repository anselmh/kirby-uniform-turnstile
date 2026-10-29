<?php

use Uniform\Exceptions\Exception as UniformException;

class HelpersTest extends KirbyTestCase
{
    public function testFieldRendersSiteKeyAndDefaultTheme(): void
    {
        $this->bootKirby(['siteKey' => 'my-key']);

        $html = turnstileField();

        $this->assertStringContainsString('class="cf-turnstile"', $html);
        $this->assertStringContainsString('data-sitekey="my-key"', $html);
        $this->assertStringContainsString('data-theme="auto"', $html);
    }

    public function testFieldThrowsWithoutSiteKey(): void
    {
        $this->bootKirby([]);

        $this->expectException(UniformException::class);
        turnstileField();
    }

    public function testFieldEscapesValues(): void
    {
        $this->bootKirby(['siteKey' => '"><script>alert(1)</script>']);

        $this->assertStringNotContainsString('<script>', turnstileField());
    }

    public function testFieldFallsBackToAutoForUnknownTheme(): void
    {
        $this->bootKirby(['siteKey' => 'my-key', 'theme' => 'neon']);

        $this->assertStringContainsString('data-theme="auto"', turnstileField());
    }

    public function testFieldRendersCustomAttributes(): void
    {
        $this->bootKirby(['siteKey' => 'my-key']);

        $html = turnstileField(['action' => 'contact', 'theme' => 'dark']);

        $this->assertStringContainsString('data-action="contact"', $html);
        $this->assertStringContainsString('data-theme="dark"', $html);
    }

    public function testFieldHasNoDefaultCallback(): void
    {
        $this->bootKirby(['siteKey' => 'my-key']);

        $this->assertStringNotContainsString('data-callback', turnstileField());
    }
}
