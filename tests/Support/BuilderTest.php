<?php namespace Arcanedev\Markup\Tests\Support;

use Arcanedev\Markup\Support\Builder;
use Arcanedev\Markup\Entities\Tag;

use Arcanedev\Markup\Tests\TestCase;

class BuilderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Builder */
    private $builder;

    const BUILDER_CLASS = 'Arcanedev\\Markup\\Support\\Builder';

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

    /**
     * @test
     */
    public function testCanMake()
    {
        $text    = 'Lorem Ipsum';
        $textTag = Tag::make('')->setText($text);

        $this->assertEquals($text, Builder::make($textTag));

        $tag = Tag::make('a', [
            'href'  => '#',
            'class' => 'btn btn-sm btn-danger'
        ]);

        $this->assertEquals(
            '<a href="#" class="btn btn-sm btn-danger"></a>',
            Builder::make($tag)
        );

        $tag->addElement($textTag);

        $this->assertEquals(
            '<a href="#" class="btn btn-sm btn-danger">' . $text . '</a>',
            Builder::make($tag)
        );
    }
}
