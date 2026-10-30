<?php

declare(strict_types=1);

namespace Transitions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class VersionTest extends TestCase
{
    /** @return iterable<string, array{string, string, bool}> */
    public static function orderings(): iterable
    {
        yield 'earlier date' => ['20150101', '20160101', true];
        yield 'same date' => ['20160101', '20160101', true];
        yield 'later date' => ['20170101', '20160101', false];
        yield 'dashed dates' => ['2015-06-01', '2016-01-01', true];
        yield 'numeric segments, not characters' => ['v2', 'v10', true];
        yield 'words sort after numbers' => ['latest', '20160101', false];
    }

    #[Test]
    #[DataProvider('orderings')]
    public function it_orders_versions_naturally(string $requested, string $stage, bool $isAtOrBefore): void
    {
        $this->assertSame($isAtOrBefore, Version::from($requested)->isAtOrBefore(Version::from($stage)));
    }

    #[Test]
    public function a_missing_or_empty_header_is_unversioned(): void
    {
        $this->assertNull(Version::fromHeader(null));
        $this->assertNull(Version::fromHeader(''));
        $this->assertNull(Version::fromHeader('  '));
        $this->assertNull(Version::fromHeader(', 20160101'));
    }

    /** @return iterable<string, array{string}> */
    public static function headersNamingTheSameVersion(): iterable
    {
        yield 'single value' => ['20160101'];
        yield 'surrounding whitespace' => [" \t20160101 "];
        yield 'repeated header' => ['20160101,20170101'];
        yield 'repeated header with whitespace' => ["20160101 ,\t20170101"];
    }

    #[Test]
    #[DataProvider('headersNamingTheSameVersion')]
    public function it_takes_the_first_comma_separated_value_ignoring_whitespace(string $header): void
    {
        $this->assertSame('20160101', Version::fromHeader($header)?->value);
    }
}
