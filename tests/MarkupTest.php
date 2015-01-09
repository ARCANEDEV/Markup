<?php namespace Arcanedev\Markup\Tests;

use Arcanedev\Markup\Markup;

class MarkupTest extends TestCase
{
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
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @test
     */
    public function testCanMakeTag()
    {
        $tag = Markup::make('p', ['class' => 'text-center']);

        $this->assertInstanceOf('Arcanedev\\Markup\\Entities\\Tag', $tag);

        $output = '<p class="text-center"></p>';
        $this->assertEquals($output, $tag->render());
        $this->assertEquals($output, (string) $tag);
    }

    /**
     * @test
     */
    public function testCanMakeTitle()
    {
        $title = 'Hello World';
        $tag    = Markup::title($title);
        $output = "<title>$title</title>";

        $this->assertEquals($output, $tag->render());
        $this->assertEquals($output, (string) $tag);

        $tag    = Markup::title()->setText($title);
        $output = "<title>$title</title>";

        $this->assertEquals($output, $tag->render());
        $this->assertEquals($output, (string) $tag);
    }

    /**
     * @test
     */
    public function testCanMakeImageTag()
    {
        $img = Markup::img('assets/img/logo.png', 'Logo', ['class' => 'img-responsive']);

        $output = '<img src="assets/img/logo.png" alt="Logo" class="img-responsive"/>';
        $this->assertEquals($output, $img->render());
        $this->assertEquals($output, (string) $img);
    }

    /**
     * @test
     */
    public function testCanMakeMetaTag()
    {
        $meta = Markup::meta('name', 'author', 'ARCANEDEV');

        $output = '<meta name="author" content="ARCANEDEV"/>';
        $this->assertEquals($output, $meta->render());
        $this->assertEquals($output, (string) $meta);

        $meta   = Markup::meta('property', 'og:title', 'My website title');
        $output = '<meta property="og:title" content="My website title"/>';
        $this->assertEquals($output, $meta->render());
        $this->assertEquals($output, (string) $meta);

        $meta   = Markup::meta('name', 'keywords', 'keyword 1, keyword 2, keyword 3, keyword 4');
        $output = '<meta name="keywords" content="keyword 1, keyword 2, keyword 3, keyword 4"/>';
        $this->assertEquals($output, $meta->render());
        $this->assertEquals($output, (string) $meta);
    }

    /**
     * @test
     */
    public function testCanMakeAnchorTag()
    {
        $url        = 'http://www.arcanedev.net';
        $text       = 'ARCANEDEV';
        $title      = 'ARCANEDEV website';
        $class      = 'btn btn-lg btn-primary';
        $attributes = [
            'title' => $title,
            'class' => $class,
        ];
        $link = Markup::link($url, $text, $attributes);

        $output = '<a href="' . $url . '" title="' . $title . '" class="' . $class . '">' . $text . '</a>';
        $this->assertEquals($output, $link->render());
        $this->assertEquals($output, (string) $link);
    }

    /**
     * @test
     */
    public function testCanMakeStyleTag()
    {
        $url   = 'assets/css/style.css';
        $style = Markup::style($url, ['media' => 'all']);

        $output = '<link rel="stylesheet" href="' . $url . '" type="text/css" media="all"/>';
        $this->assertEquals($output, $style->render());
        $this->assertEquals($output, (string) $style);
    }
}
