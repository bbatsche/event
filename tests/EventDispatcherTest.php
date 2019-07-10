<?php

namespace Phossa2\Event\Test;

use Phossa2\Event\EventDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * EventDispatcher test case.
 */
class EventDispatcherTest extends TestCase
{
    /**
     *
     * @var EventDispatcher
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new EventDispatcher();

        require_once __DIR__ . '/MyClass.php';
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        parent::tearDown();
    }

    /**
     * Call protected/private method of a class.
     *
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod($methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($this->object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->object, $parameters);
    }

    /**
     * getPrivateProperty
     *
     * @param  string $propertyName
     * @return the property
     */
    public function getPrivateProperty($propertyName)
    {
        $reflector = new \ReflectionClass($this->object);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->object);
    }

    /**
     * shared event manager
     *
     * @covers Phossa2\Event\EventDispatcher::addScope
     * @covers Phossa2\Event\EventDispatcher::getShareable
     * @covers Phossa2\Event\EventDispatcher::on
     * @covers Phossa2\Event\EventDispatcher::trigger
     */
    public function testSharedManager1()
    {
        $this->expectOutputString('scope_bingoBINGO');

        // bind event to shared manager of the $scope1
        $this->object->attach('*', function($evt) {
            echo "scope_" . $evt->getName();
        }, 10);

        // bind to self
        $this->object->attach('bingo', function($evt) {
            echo "BINGO";
        });

        $this->object->trigger('bingo');
    }

    /**
     * listener aware
     *
     * @covers Phossa2\Event\EventDispatcher::attachListener
     * @covers Phossa2\Event\EventDispatcher::detachListener
     */
    public function testAttachListener()
    {
        $this->expectOutputString('xxx');

        $listener = new \MyClass();

        // attach
        $this->object->attachListener($listener);
        $this->object->trigger('afterTest');

        // detach
        $this->object->detachListener($listener, 'afterTest');
        $this->object->trigger('afterTest');
    }

    /**
     * countable
     *
     * @covers Phossa2\Event\EventDispatcher::one
     * @covers Phossa2\Event\EventDispatcher::many
     * @covers Phossa2\Event\EventDispatcher::off
     */
    public function testOne()
    {
        $this->expectOutputString('onetwotwothree');

        // one
        $this->object->one('one', function() {
            echo "one";
        });
        $this->object->trigger('one');
        $this->object->trigger('one');

        // many
        $this->object->many(2, 'two', function() {
            echo "two";
        });
        $this->object->trigger('two');
        $this->object->trigger('two');

        // off
        $this->object->many(3, 'three', function() {
            echo "three";
        });
        $this->object->trigger('three');
        $this->object->clearListeners('three');
        $this->object->trigger('three');
    }
}
