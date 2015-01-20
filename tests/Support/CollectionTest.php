<?php namespace Arcanedev\Markup\Tests\Support;

use Arcanedev\Markup\Support\Collection;
use Arcanedev\Markup\Tests\TestCase;

class CollectionTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const COLLECTION_CLASS = 'Arcanedev\\Markup\\Support\\Collection';
    /** @var Collection */
    private $collection;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->collection = new Collection();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->collection);
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
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->collection);
        $this->assertCount(0, $this->collection);
    }

    /**
     * @test
     */
    public function testCanGetAll()
    {
        $items = [
            'foo' => 'bar',
            'bar' => 'foo'
        ];

        $this->collection = new Collection($items);

        $this->assertEquals($items, $this->collection->all());
        $this->assertEquals($items, $this->collection->toArray());

        $collection = new Collection($this->collection);
        $this->assertEquals($items, $collection->all());
        $this->assertEquals($items, $collection->toArray());
    }

    /**
     * @test
     */
    public function testCanGet()
    {
        $items = [
            'foo' => 'bar',
            'bar' => 'foo'
        ];
        $this->collection = new Collection($items);

        $this->assertEquals('bar', $this->collection->get('foo'));
        $this->assertEquals('foo', $this->collection->get('bar'));

        $this->assertNull($this->collection->get('baz'));

        $this->assertEquals('Not found', $this->collection->get('baz', 'Not found'));
    }

    /**
     * @test
     */
    public function testCanSet()
    {
        $this->collection->set('foo', 'bar');
        $this->collection->put('bar', 'foo');
        $this->collection->push('baz');
        $this->collection->set(null, 'hello');

        $this->assertCount(4, $this->collection);

        $this->assertEquals(
            ['foo' => 'bar', 'bar' => 'foo', 'baz', 'hello'],
            $this->collection->all()
        );
    }

    /**
     * @test
     */
    public function testCanCheckExists()
    {
        $items = [
            'foo' => 'bar',
            'bar' => 'foo'
        ];
        $this->collection = new Collection($items);

        $this->assertTrue($this->collection->has('foo'));
        $this->assertTrue($this->collection->has('bar'));
        $this->assertFalse($this->collection->has('baz'));
    }

    /**
     * @test
     */
    public function testCanForget()
    {
        $items = [
            'foo' => 'bar',
            'bar' => 'foo'
        ];
        $this->collection = new Collection($items);

        $this->assertCount(2, $this->collection);

        $this->collection->forget('foo');

        $this->assertCount(1, $this->collection);
        $this->assertFalse($this->collection->has('foo'));
        $this->assertTrue($this->collection->has('bar'));

        $this->collection->forget('bar');

        $this->assertCount(0, $this->collection);
        $this->assertFalse($this->collection->has('foo'));
        $this->assertFalse($this->collection->has('bar'));
    }

    /**
     * @test
     */
    public function testCanLoopEachItem()
    {
        $items = [
            'foo' => 'bar',
            'bar' => 'foo'
        ];
        $this->collection = new Collection($items);

        $collection = $this->collection->each(function($item) {
            return strtoupper($item);
        });

        $this->assertEquals($items, $this->collection->all());
        $this->assertEquals([
            'foo' => 'BAR',
            'bar' => 'FOO'
        ], $collection->all());
    }

    /**
     * @test
     */
    public function testCanGetOnlyKeys()
    {
        $items = [
            'foo' => 'Hello',
            'bar' => 'World'
        ];
        $this->collection = new Collection($items);

        $this->assertEquals(array_keys($items), $this->collection->keys());
    }

    /**
     * @test
     */
    public function testCanGetLastItem()
    {
        $items = [
            'foo' => 'Bar',
            'bar' => 'Foo'
        ];
        $this->collection = new Collection($items);

        $this->assertEquals('Foo', $this->collection->last());
    }
}
