<?php

use Kirby\Cms\App as Kirby;
use PHPUnit\Framework\TestCase;

abstract class KirbyTestCase extends TestCase
{
    /**
     * Boot a Kirby instance with the given plugin options (without the
     * `anselmh.uniform-turnstile.` prefix) and request body.
     */
    protected function bootKirby(array $config = [], array $body = []): void
    {
        $options = [];
        foreach ($config as $key => $value) {
            $options['anselmh.uniform-turnstile.' . $key] = $value;
        }

        new Kirby([
            'roots' => ['index' => sys_get_temp_dir()],
            'options' => $options,
            'request' => ['body' => $body],
        ]);
    }

    /**
     * Kirby registers its own error and exception handlers; remove them so
     * PHPUnit does not flag every test as risky.
     */
    protected function tearDown(): void
    {
        restore_error_handler();
        restore_exception_handler();
        parent::tearDown();
    }
}
