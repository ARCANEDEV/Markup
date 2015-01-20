<?php namespace Arcanedev\Markup\Tests\Entities\Tag;

use Arcanedev\Markup\Entities\Tag\Type;
use Arcanedev\Markup\Tests\TestCase;

class TypeTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const TYPE_CLASS = 'Arcanedev\\Markup\\Entities\\Tag\\Type';
    /** @var Type */
    private $type;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->type = new Type;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->type);
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
        $this->assertInstanceOf(self::TYPE_CLASS, $this->type);
        $this->assertEmpty($this->type->getName());
        $this->assertTrue($this->type->isEmpty());
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Markup\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The tag type must be a string, boolean is given.
     */
    public function testMustThrowInvalidTypeExceptionOnName()
    {
        new Type(true);
    }
}
