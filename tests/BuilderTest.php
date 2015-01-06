<?php namespace Arcanedev\Markup\Tests;

use Arcanedev\Markup\Builder;

class BuilderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Builder */
    private $builder;

    const BUILDER_CLASS = 'Arcanedev\\Markup\\Builder';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->builder = new Builder;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->builder);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf(self::BUILDER_CLASS, $this->builder);
    }
}
