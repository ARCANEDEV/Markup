<?php namespace Arcanedev\Markup\Tests;

use Arcanedev\Markup\Markup;

class MarkupTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Markup */
    private $markup;

    const MARKUP_CLASS = 'Arcanedev\\Markup\\Markup';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->markup = new Markup;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->markup);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(self::MARKUP_CLASS, $this->markup);
    }
}
