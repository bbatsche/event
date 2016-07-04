<?php

namespace Phossa2\Event;

/**
 * StaticEventDispatcher test case.
 */
class StaticEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers Phossa2\Event\StaticEventDispatcher::setEventManager
     * @covers Phossa2\Event\StaticEventDispatcher::getEventManager
     */
    public function testSetEventManager()
    {
        $events = new EventDispatcher();
        StaticEventDispatcher::setEventManager($events);
        $this->assertTrue($events === StaticEventDispatcher::getEventManager());
    }

    /**
     * @covers Phossa2\Event\StaticEventDispatcher::on
     * @covers Phossa2\Event\StaticEventDispatcher::off
     * @covers Phossa2\Event\StaticEventDispatcher::trigger
     */
    public function testOn()
    {
        $this->expectOutputString('t**t*');

        StaticEventDispatcher::on('*', function() {
            echo '*';
        }, 100);

        StaticEventDispatcher::on('t*', function() {
            echo 't*';
        }, 20);

        StaticEventDispatcher::trigger('test');

        StaticEventDispatcher::off('*');
        StaticEventDispatcher::trigger('test');
    }
}
