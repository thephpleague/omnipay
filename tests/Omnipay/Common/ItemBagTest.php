<?php

namespace Omnipay\Common;

use Omnipay\Tests\TestCase;

class ItemBagTest extends TestCase
{
    public function setUp()
    {
        $this->bag = new ItemBag;
    }

    public function testConstruct()
    {
        $bag = new ItemBag(array(array('name' => 'Floppy Disk')));
        $this->assertCount(1, $bag);
    }

    public function testAll()
    {
        $items = array(new Item, new Item);
        $bag = new ItemBag($items);

        $this->assertSame($items, $bag->all());
    }

    public function testReplace()
    {
        $items = array(new Item, new Item);
        $this->bag->replace($items);

        $this->assertSame($items, $this->bag->all());
    }

    public function testAddWithItem()
    {
        $item = new Item;
        $item->setName('CD-ROM');
        $this->bag->add($item);

        $contents = $this->bag->all();
        $this->assertSame($item, $contents[0]);
    }

    public function testAddWithArray()
    {
        $item = array('name' => 'CD-ROM');
        $this->bag->add($item);

        $contents = $this->bag->all();
        $this->assertInstanceOf('\Omnipay\Common\Item', $contents[0]);
        $this->assertSame('CD-ROM', $contents[0]->getName());
    }

    public function testGetIterator()
    {
        $item = new Item;
        $item->setName('CD-ROM');
        $this->bag->add($item);

        foreach ($this->bag as $bagItem) {
            $this->assertSame($item, $bagItem);
        }
    }

    public function testCount()
    {
        $this->bag->add(new Item);
        $this->bag->add(new Item);
        $this->bag->add(new Item);

        $this->assertSame(3, count($this->bag));
    }
}
