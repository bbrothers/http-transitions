<?php

declare(strict_types=1);

namespace Transitions;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;
use Transitions\Transitions\BirthDateTransition;
use Transitions\Transitions\FirstNameLastNameToFullNameTransition;
use Transitions\Transitions\FullNameToNameTransition;

final class ConfigTest extends TestCase
{
    #[Test]
    public function it_throws_an_exception_if_no_header_key_is_configured(): void
    {
        $this->expectException(InvalidConfiguration::class);
        $this->expectExceptionMessageMatches('/^A header key must be set/');

        Config::fromArray([]);
    }

    #[Test]
    public function it_rejects_a_stage_entry_that_is_not_a_transition(): void
    {
        $this->expectException(InvalidConfiguration::class);
        $this->expectExceptionMessage('`stdClass` in stage `2016` must be a class extending '.Transition::class);

        Config::fromArray(['headerKey' => 'Api-Version', 'transitions' => ['2016' => [stdClass::class]]]);
    }

    #[Test]
    public function it_rejects_a_stage_that_is_not_a_list(): void
    {
        $this->expectException(InvalidConfiguration::class);
        $this->expectExceptionMessage('The `transitions` entry for stage `2016` must be a list');

        Config::fromArray(['headerKey' => 'Api-Version', 'transitions' => ['2016' => BirthDateTransition::class]]);
    }

    #[Test]
    public function it_filters_out_transitions_with_a_key_less_than_the_requested_version(): void
    {
        $config = Config::fromArray([
            'headerKey' => 'Api-Version',
            'transitions' => [
                '2015' => [FirstNameLastNameToFullNameTransition::class],
                '2016' => [FullNameToNameTransition::class],
                '2017' => [BirthDateTransition::class],
            ],
        ]);

        $this->assertSame(
            [FullNameToNameTransition::class, BirthDateTransition::class],
            $config->transitionsFor(Version::from('2016')),
        );
    }

    #[Test]
    public function it_applies_every_transition_to_an_unversioned_request(): void
    {
        $config = Config::fromArray([
            'headerKey' => 'Api-Version',
            'transitions' => [
                '2015' => [FirstNameLastNameToFullNameTransition::class],
                '2016' => [FullNameToNameTransition::class],
            ],
        ]);

        $this->assertSame(
            [FirstNameLastNameToFullNameTransition::class, FullNameToNameTransition::class],
            $config->transitionsFor(null),
        );
    }

    #[Test]
    public function it_always_includes_transitions_with_a_wildcard(): void
    {
        $config = Config::fromArray([
            'headerKey' => 'Api-Version',
            'transitions' => [
                '*' => [BirthDateTransition::class],
                '2017' => [FullNameToNameTransition::class],
            ],
        ]);

        $this->assertSame([BirthDateTransition::class], $config->transitionsFor(Version::from('2018')));
    }
}
