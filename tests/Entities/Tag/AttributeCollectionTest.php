<?php namespace Arcanedev\Markup\Tests\Entities\Tag;

use Arcanedev\Markup\Entities\Tag\AttributeCollection;
use Arcanedev\Markup\Tests\TestCase;

class AttributeCollectionTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var AttributeCollection */
    private $attributeCollection;

    const ATTRIBUTES_CLASS = 'Arcanedev\\Markup\\Entities\\Tag\\AttributeCollection';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->attributeCollection = new AttributeCollection;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->attributeCollection);
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
        $this->assertInstanceOf(
            self::ATTRIBUTES_CLASS,
            $this->attributeCollection
        );

        $this->assertTrue($this->attributeCollection->isEmpty());
        $this->assertCount(0,$this->attributeCollection);
    }

    /**
     * @test
     */
    public function testCanAddAttributes()
    {
        $this->attributeCollection->add('id', 'submit-btn');
        $this->assertCount(1, $this->attributeCollection);

        $this->attributeCollection->add('name', 'submit-btn');
        $this->assertCount(2, $this->attributeCollection);

        $this->attributeCollection->add('type', 'submit');
        $this->assertCount(3, $this->attributeCollection);

        $this->attributeCollection->add('class', ['btn', 'btn-xs', 'btn-danger']);
        $this->assertCount(4, $this->attributeCollection);

        $this->assertFalse($this->attributeCollection->isEmpty());
        $this->assertEquals(
            'id="submit-btn" name="submit-btn" type="submit" class="btn btn-xs btn-danger"',
            $this->attributeCollection->render()
        );
    }

    /**
     * @test
     */
    public function testCanGetAttribute()
    {
        $this->attributeCollection->add('id', 'submit-btn');
        $this->attributeCollection->add('name', 'submit-btn');
        $this->attributeCollection->add('class', ['btn', 'btn-sm', 'btn-danger btn-block']);

        $attr = $this->attributeCollection->getAttr('id');
        $this->assertEquals('submit-btn', $attr->getValues());

        $attr = $this->attributeCollection->getAttr('name');
        $this->assertEquals('submit-btn', $attr->getValues());

        $attr = $this->attributeCollection->getAttr('class');
        $this->assertEquals('btn btn-sm btn-danger btn-block', $attr->getValues());

        $attr = $this->attributeCollection->getAttr('type');
        $this->assertNull($attr);
    }

    /**
     * @test
     */
    public function testCanUpdateAttribute()
    {
        $this->attributeCollection->add('class', ['btn', 'btn-xs', 'btn-danger']);
        $this->assertCount(1, $this->attributeCollection);

        $this->attributeCollection->add('class', ['btn-modal']);
    }

    /**
     * @test
     */
    public function testCanAddAttributesFormArray()
    {
        $this->attributeCollection->addMany([
            'id'    => 'submit-btn',
            'name'  => 'submit-btn',
            'type'  => 'submit',
            'class' => ['btn', 'btn-xs', 'btn-danger'],
        ]);

        $this->assertCount(4, $this->attributeCollection);
        $this->assertEquals(
            'id="submit-btn" name="submit-btn" type="submit" class="btn btn-xs btn-danger"',
            $this->attributeCollection->render()
        );
    }

    /**
     * @test
     */
    public function testCanForgetAttributeValue()
    {
        $this->attributeCollection->add('class', ['btn', 'btn-xs', 'btn-danger']);

        $this->attributeCollection->forgetValue('class', 'btn-danger');
        $values = $this->attributeCollection->getAttr('class')->values();
        $this->assertEquals(['btn', 'btn-xs'], $values);
    }

    /**
     * @test
     */
    public function testCanConvertToArray()
    {
        $attributes = [
            'id'    => ['submit-btn'],
            'name'  => ['submit-btn'],
            'class' => ['btn', 'btn-sm', 'btn-danger btn-block']
        ];

        $this->attributeCollection->add('id',    $attributes['id'][0]);
        $this->attributeCollection->add('name',  $attributes['name'][0]);
        $this->attributeCollection->add('class', $attributes['class']);

        $this->assertEquals(
            $attributes,
            $this->attributeCollection->toArray()
        );
    }
}
