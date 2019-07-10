<?php

namespace Phossa2\Event\Test;

use Phossa2\Event\EventDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * EventCapableAbstract test case.
 */
class EventCapableAbstractTest extends TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        require_once __DIR__ . '/MyClass.php';
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers Phossa2\Event\EventCapableAbstract::setEventManager
     * @covers Phossa2\Event\EventCapableAbstract::getEventManager
     */
    public function testSetEventManager()
    {
        $em  = new EventDispatcher('TEST');
        $obj = new \MyClass();

        // assign the new manager
        $obj->setEventManager($em);
        $this->assertTrue($em === $obj->getEventManager());
    }

    /**
     * @covers Phossa2\Event\EventCapableAbstract::setEventPrototype
     */
    public function testSetEventPrototype()
    {
        // default manager
        $obj = new \MyClass();
        $obj->setEventPrototype(new \MyEvent('test'));

        $evt = $obj->triggerEvent('wow');
        $this->assertTrue($evt instanceof \MyEvent);
        $this->assertEquals('wow', $evt->getName());
    }

    /**
     * Get trigger() result
     *
     * @covers Phossa2\Event\EventCapableAbstract::trigger
     */
    public function testTrigger()
    {
        $this->expectOutputString('xxx');
        $obj = new \MyClass();
        $this->assertTrue($obj->trigger('afterTest'));
    }
}
