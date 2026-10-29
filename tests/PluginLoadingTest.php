<?php

use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

class PluginLoadingTest extends TestCase
{
    /**
     * Regression test for issue #3: the guard extends a Uniform class, so it
     * must not be loaded while the plugin loads (Uniform may not be loaded yet).
     */
    #[RunInSeparateProcess]
    public function testGuardIsNotLoadedUntilRequested(): void
    {
        require __DIR__ . '/../index.php';

        $this->assertFalse(class_exists('Uniform\Guards\TurnstileGuard', false));
        $this->assertTrue(class_exists('Uniform\Guards\TurnstileGuard'));
    }
}
