<?php

declare(strict_types=1);

namespace Transitions\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Transitions\TestCase;

final class GenerateTransitionTest extends TestCase
{
    private Filesystem $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = new Filesystem;
    }

    protected function tearDown(): void
    {
        $this->files->deleteDirectory(app_path('Http/Transitions'));

        parent::tearDown();
    }

    #[Test]
    public function it_generates_a_transition_class(): void
    {
        $contents = $this->generate('TestTransition');

        $this->assertStringContainsString('namespace App\Http\Transitions;', $contents);
        $this->assertStringContainsString('class TestTransition extends Transition', $contents);
        $this->assertStringContainsString('public function transformRequest', $contents);
        $this->assertStringContainsString('public function transformResponse', $contents);
    }

    #[Test]
    public function it_generates_a_request_only_transition_class(): void
    {
        $contents = $this->generate('TestRequestTransition', ['--request-only' => true]);

        $this->assertStringContainsString('public function transformRequest', $contents);
        $this->assertStringNotContainsString('public function transformResponse', $contents);
    }

    #[Test]
    public function it_generates_a_response_only_transition_class(): void
    {
        $contents = $this->generate('TestResponseTransition', ['--response-only' => true]);

        $this->assertStringContainsString('public function transformResponse', $contents);
        $this->assertStringNotContainsString('public function transformRequest', $contents);
    }

    /**
     * @param  array<string, bool>  $options
     */
    private function generate(string $name, array $options = []): string
    {
        $this->assertSame(0, Artisan::call('make:transition', ['name' => $name, ...$options]));

        return $this->files->get(app_path("Http/Transitions/{$name}.php"));
    }
}
