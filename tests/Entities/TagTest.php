<?php namespace Arcanedev\Markup\Tests\Entities;

use Arcanedev\Markup\Entities\Tag;
use Arcanedev\Markup\Tests\TestCase;

class TagTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Tag */
    private $tag;

    const TAG_CLASS = 'Arcanedev\\Markup\\Entities\\Tag';

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

        $this->assertTrue($this->tag->hasAttributes());
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
        $this->assertEquals($output, (string) $link);

        $this->assertEquals(
            '<p class="text-center">' . $output . '</p>',
            $this->tag->render()
        );
        $this->assertEquals(
            '<p class="text-center">' . $output . '</p>',
            (string) $this->tag
        );

        $this->tag->addElement('img', [
            'src' => 'logo.png', 'class' => 'img-responsive'
        ]);
        $output .= '<img src="logo.png" class="img-responsive"/>';

        $result = '<p class="text-center">' . $output . '</p>';

        $this->assertEquals($result, $this->tag->render());
        $this->assertEquals($result, (string) $this->tag);
    }

    /**
     * @test
     *
     * @expectedException \Arcanedev\Markup\Exceptions\InvalidTypeException
     * @expectedExceptionMessage The tag type must be a string, boolean is given.
     */
    public function testMustThrowInvalidTypeExceptionOnType()
    {
        Tag::make(true);
    }

    /**
     * @test
     */
    public function testCanGetAndRemoveElements()
    {
        $container = Tag::make('div', ['class' => 'container']);
        $paragraph = Tag::make('p', ['class' => 'text-center'], $container);
        $anchor    = Tag::make('a', ['class' => 'btn btn-xs btn-danger'], $container);

        $this->assertTrue($container->hasElements());
        $this->assertEquals(2, $container->countElements());
        $this->assertFalse($paragraph->hasElements());
        $this->assertFalse($anchor->hasElements());

        // First Element
        $this->assertNull($container->getFirst());

        $elt = $paragraph->getFirst();
        $this->assertInstanceOf(self::TAG_CLASS, $elt);
        $this->assertEquals('p', $elt->getType());

        $elt = $anchor->getFirst();
        $this->assertInstanceOf(self::TAG_CLASS, $elt);
        $this->assertEquals('p', $elt->getType());

        // Next Element
        $this->assertNull($container->getNext());

        $elt = $paragraph->getNext();
        $this->assertInstanceOf(self::TAG_CLASS, $elt);
        $this->assertEquals('a', $elt->getType());

        $elt = $anchor->getNext();
        $this->assertNull($elt);

        // Previous Element
        $this->assertNull($container->getPrevious());

        $elt = $paragraph->getPrevious();
        $this->assertNull($elt);

        $elt = $anchor->getPrevious();
        $this->assertInstanceOf(self::TAG_CLASS, $elt);
        $this->assertEquals('p', $elt->getType());

        // Last Element
        $this->assertNull($container->getLast());

        $elt = $paragraph->getLast();
        $this->assertInstanceOf(self::TAG_CLASS, $elt);
        $this->assertEquals('a', $elt->getType());

        $elt = $anchor->getLast();
        $this->assertInstanceOf(self::TAG_CLASS, $elt);
        $this->assertEquals('a', $elt->getType());

        // Remove Element
        $this->assertNull($container->remove());

        $parent = $anchor->remove();

        $this->assertInstanceOf(self::TAG_CLASS, $parent);
        $this->assertEquals($container, $parent);
        $this->assertTrue($parent->hasElements());
        $this->assertEquals(1, $parent->countElements());

        $parent = $paragraph->remove();

        $this->assertInstanceOf(self::TAG_CLASS, $parent);
        $this->assertEquals($container, $parent);
        $this->assertFalse($parent->hasElements());
        $this->assertEquals(0, $parent->countElements());

        $parent = $paragraph->remove();

        $this->assertNull($parent);
    }
}
