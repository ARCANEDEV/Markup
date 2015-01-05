<?php namespace Arcanedev\Markup\Tests\Entities;

use Arcanedev\Markup\Entities\Attribute;

use Arcanedev\Markup\Tests\TestCase;

class AttributeTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Attribute */
    private $attribute;

    const ATTRIBUTE_CLASS = 'Arcanedev\\Markup\\Entities\\Attribute';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->attribute);
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
        $this->attribute = new Attribute('class', 'test');
        $this->assertInstanceOf(self::ATTRIBUTE_CLASS, $this->attribute);
        $this->assertFalse($this->attribute->isEmpty());
    }

    /**
     * @test
     */
    public function testCanMakeAttribute()
    {
        $name            = 'class';
        $content         = 'btn btn-lg btn-danger';
        $this->attribute = Attribute::make($name, $content);

        $this->assertEquals("$name=\"$content\"", $this->attribute->toString());

        $name            = 'class';
        $content         = ['btn', 'btn-xs', 'btn-primary', 'btn-block'];
        $this->attribute = Attribute::make($name, $content);
        $content         = implode(' ', $content);

        $this->assertEquals("$name=\"$content\"", $this->attribute->toString());
    }

    /**
     * @test
     */
    public function testCanAddAndRemoveContent()
    {
        $this->attribute = Attribute::make('class', [
            'btn', 'btn-xs'
        ]);

        $this->assertEquals(2, count($this->attribute->values()));
        $this->assertEquals('class="btn btn-xs"', $this->attribute->render());

        $this->attribute->addContent([
            'btn', 'btn-primary', 'btn-block'
        ]);
        $this->assertEquals(4, count($this->attribute->values()));

        $this->attribute->forgetValue('btn-block');
        $this->assertEquals(3, count($this->attribute->values()));

        $this->assertEquals('class="btn btn-xs btn-primary"', $this->attribute->render());
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Markup\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The name must be a string value, boolean is given.
     */
    public function testMustThrowInvalidTypeExceptionOnName()
    {
        Attribute::make(true, 'btn');
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Markup\Exceptions\Exception
     * @expectedExceptionMessage The attribute name must not be empty.
     */
    public function testMustThrowExceptionOnEmptyName()
    {
        Attribute::make('  ', 'class-name');
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Markup\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The content must be an array or string value, boolean is given.
     */
    public function testMustThrowInvalidTypeExceptionOnContent()
    {
        Attribute::make('enabled', true);
    }
}
