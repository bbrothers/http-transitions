<?php

namespace Transitions;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    /** @test */
    public function itThrowsAnExceptionIfNoHeaderKeyIsConfigured() : void
    {

        $this->expectException(HeaderKeyNotDefined::class);
        $this->expectExceptionMessageRegExp('/^A header key must be set/');
        new Config;
    }

    /** @test */
    public function itCanBeCreatedFromAnArray() : void
    {

        $config = Config::fromArray([
            'headerKey' => 'some-key',
            'transitions' => ['array of transitions'],
        ]);

        $this->assertInstanceOf(Config::class, $config);
    }

    /** @test */
    public function itWillReturnFilterOutTransitionsWithAKeyLessThanTheSuppliedVersion() : void
    {

        $config = Config::fromArray([
            'headerKey' => 'Api-Version',
            'transitions' => [
                '2015' => ['2015-transitions'],
                '2016' => ['2016-transitions'],
                '2017' => ['2017-transitions'],
            ],
        ]);

        $this->assertEquals([
            '2016-transitions',
            '2017-transitions',
        ], $config->transitionsForVersion(2016));
    }
}
