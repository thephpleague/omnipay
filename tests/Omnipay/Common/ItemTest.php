<?php

namespace Omnipay\Common;

use Omnipay\Tests\TestCase;

class ItemTest extends TestCase
{
    public function setUp()
    {
        $this->item = new Item;
    }

    public function testConstructWithParams()
    {
        $item = new Item(array('name' => 'Floppy Disk'));
        $this->assertSame('Floppy Disk', $item->getName());
    }

    public function testInitializeWithParams()
    {
        $this->item->initialize(array('name' => 'Floppy Disk'));
        $this->assertSame('Floppy Disk', $this->item->getName());
    }

    public function testGetParameters()
    {
        $this->item->setName('CD-ROM');
        $this->assertSame(array('name' => 'CD-ROM'), $this->item->getParameters());
    }

    public function testName()
    {
        $this->item->setName('CD-ROM');
        $this->assertSame('CD-ROM', $this->item->getName());
    }

    public function testDescription()
    {
        $this->item->setDescription('CD');
        $this->assertSame('CD', $this->item->getDescription());
    }

    public function testQuantity()
    {
        $this->item->setQuantity(5);
        $this->assertSame(5, $this->item->getQuantity());
    }

    public function testPrice()
    {
        $this->item->setPrice('10.01');
        $this->assertSame('10.01', $this->item->getPrice());
    }
}
