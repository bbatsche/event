<?php

namespace Phossa2\Event\Test;

use Phossa2\Event\EventQueue;
use Phossa2\Event\Interfaces\EventQueueInterface;
use PHPUnit\Framework\TestCase;

class EventQueueTest extends TestCase
{
    /**
     * @var EventQueue
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new EventQueue();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object =  null;
        parent::tearDown();
    }

    /**
     * @covers Phossa2\Event\EventQueue::count
     */
    public function testCount()
    {
        // 0
        $this->assertTrue(0 === $this->object->count());

        // 1
        $this->object->insert([$this->object, 'count'], 10);
        $this->assertTrue(1 === count($this->object));
    }

    /**
     * @covers Phossa2\Event\EventQueue::getIterator
     */
    public function testGetIterator1()
    {
        $it = $this->object->getIterator();
        $this->assertInstanceOf('SplPriorityQueue', $it);
    }

    /**
     * @covers Phossa2\Event\EventQueue::getIterator
     */
    public function testGetIterator2()
    {
        $callable = [$this->object, 'count'];
        $this->object->insert($callable, 10);
        foreach ($this->object as $data) {
            $this->assertSame($callable, $data['data']);
            $this->assertSame(10, $data['priority'][0]);
        }
    }

    /**
     * @covers Phossa2\Event\EventQueue::insert
     */
    public function testInsert1()
    {
        // insert callable
        $this->object->insert([$this->object, 'count'], 10);
        $this->assertCount(1, $this->object);

        // insert another callable
        $this->object->insert([$this->object, 'flush'], 70);
        $this->assertCount(2, $this->object);

        // insert callable one again
        $this->object->insert([$this->object, 'count'], 50);
        $this->assertCount(3, $this->object);
    }

    /**
     * test sorted order
     *
     * @covers Phossa2\Event\EventQueue::insert
     */
    public function testInsert2()
    {
        $callable1 = [$this->object, 'count'];
        $callable2 = [$this->object, 'flush'];
        $callable3 = 'phpinfo';

        // insert callable, order 70
        $this->object->insert($callable1, 70);
        $this->object->insert($callable2, 20);
        $this->object->insert($callable3, 50);

        $result = [];
        foreach ($this->object as $data) {
            $result[] = $data['data'];
        }

        // check order
        $this->assertTrue($callable1 === $result[0]);
        $this->assertTrue($callable3 === $result[1]);
        $this->assertTrue($callable2 === $result[2]);
    }

    /**
     * @covers Phossa2\Event\EventQueue::remove
     */
    public function testRemove()
    {
        $callable = [$this->object, 'count'];
        $this->object->insert($callable, 10);
        $this->assertTrue(1 === count($this->object));
        $this->object->remove($callable);
        $this->assertTrue(0 === count($this->object));
    }

    /**
     * @covers Phossa2\Event\EventQueue::flush
     */
    public function testFlush()
    {
        $callable = [$this->object, 'count'];
        $this->object->insert($callable, 10);
        $this->assertTrue(1 === count($this->object));
        $this->object->flush();
        $this->assertTrue(0 === count($this->object));
    }

    /**
     * @covers Phossa2\Event\EventQueue::combine
     */
    public function testAppend()
    {
        // callables
        $call1 = [$this->object, 'count'];
        $call2 = [$this->object, 'flush'];

        // queue 1
        $this->object->insert($call1, 10);

        // queue 2
        $que2 = new EventQueue();
        $que2->insert($call2, 20);

        $this->object->append($que2);

        // count right
        $this->assertCount(2, $this->object);
    }
}
