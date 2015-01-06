<?php namespace Arcanedev\Markup\Tests\Entities;


use Arcanedev\Markup\Entities\Tag;
use Arcanedev\Markup\Tests\TestCase;

class TagTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const TAG_CLASS = 'Arcanedev\\Markup\\Entities\\Tag';
    /** @var Tag */
    private $tag;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->tag = new Tag('');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->tag);
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
        $this->assertInstanceOf(self::TAG_CLASS, $this->tag);
    }

    /**
     * @test
     */
    public function testCanMake()
    {
        $this->tag = Tag::make('a', ['href' => '#', 'class' => 'btn btn-xs btn-primary']);

        $this->assertEquals('a', $this->tag->getType());
    }

    /**
     * @test
     */
    public function testCanSetAndGetAttributes()
    {
        $this->tag = Tag::make('a', [
            'href'  => '#',
            'class' => 'btn btn-xs btn-primary'
        ]);

        $this->tag->attr('title', 'Button title');

        $this->assertEquals([
            'href'  => ['#'],
            'class' => ['btn', 'btn-xs', 'btn-primary'],
            'title' => ['Button title']
        ], $this->tag->getAttributes());

        $this->tag->attr('title', 'New button title');
        $this->tag->attrs([
            'id'    => 'buttonId',
            'class' => 'btn-block'
        ]);

        $this->assertEquals([
            'href'  => ['#'],
            'class' => ['btn', 'btn-xs', 'btn-primary', 'btn-block'],
            'title' => ['New button title'],
            'id'    => ['buttonId'],
        ], $this->tag->getAttributes());
    }

    /**
     * @test
     */
    public function testCanRenderAttributes()
    {
        $this->tag = Tag::make('a', [
            'href'  => '#',
            'title' => 'Button title',
            'class' => 'btn btn-xs btn-primary'
        ]);

        $this->assertEquals(
            'href="#" title="Button title" class="btn btn-xs btn-primary"',
            $this->tag->renderAttributes()
        );
    }

    /**
     * @test
     */
    public function testCanSetAndGetParent()
    {
        $container = Tag::make('div', ['class' => 'container']);

        $this->assertNull($container->getParent());

        $this->tag = Tag::make('a', ['href' => '#', 'class' => 'btn'], $container);

        $parent = $this->tag->getParent();

        $this->assertInstanceOf(self::TAG_CLASS, $parent);
        $this->assertEquals('div', $parent->getType());
        $this->assertEquals(
            ['class' => ['container']],
            $parent->getAttributes()
        );
    }

    /**
     * @test
     */
    public function testCanSetAndGetText()
    {
        $this->tag = Tag::make('p', ['class' => 'text-center']);

        $this->assertEmpty($this->tag->getText());

        $lorem = 'Lorem ipsum';
        $text  = ' Text without a tag';
        $this->tag->setText($lorem);
        $this->tag->text($text); // add an empty element with text

        $this->assertEquals($lorem, $this->tag->getText());
        $this->assertEquals(
            '<p class="text-center">' . $lorem . $text .'</p>',
            $this->tag->render()
        );
    }

    /**
     * @test
     */
    public function testCanRender()
    {
        $this->tag = Tag::make('p', ['class' => 'text-center']);

        $this->assertEquals(
            '<p class="text-center"></p>',
            $this->tag->render()
        );

        $link   = Tag::make('a', ['class' => 'btn btn-xs btn-primary']);
        $text   = 'Lorem ipsum';
        $output = '<a class="btn btn-xs btn-primary">' . $text . '</a>';
        $link->setText($text);
        $this->tag->addElement($link);

        $this->assertEquals($output, $link->render());
        $this->assertEquals(
            '<p class="text-center">' . $output . '</p>',
            $this->tag->render()
        );

        $this->tag->addElement('img', [
            'src' => 'logo.png', 'class' => 'img-responsive'
        ]);
        $output .= '<img src="logo.png" class="img-responsive"/>';

        $this->assertEquals(
            '<p class="text-center">' . $output . '</p>',
            $this->tag->render()
        );
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Markup\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The type tag must be a string, boolean is given.
     */
    public function testMustThrowInvalidTypeExceptionOnType()
    {
        Tag::make(true);
    }
}
