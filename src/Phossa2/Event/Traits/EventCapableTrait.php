<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa2\Event
 * @copyright Copyright (c) 2016 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa2\Event\Traits;

use Phossa2\Event\Event;
use Phossa2\Event\EventDispatcher;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\ListenerInterface;
use Phossa2\Event\Interfaces\EventManagerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;

/**
 * Implementation of EventCapableInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventCapableInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait EventCapableTrait
{
    /**
     * event manager or dispatcher
     *
     * @var    EventManagerInterface
     * @access protected
     */
    protected $event_manager;

    /**
     * event prototype
     *
     * @var    EventInterface
     * @access protected
     */
    protected $event_proto;

    /**
     * {@inheritDoc}
     */
    public function setEventManager(
        EventManagerInterface $eventManager
    ) {
        $this->event_manager = $eventManager;

        // attach events from $this
        if ($eventManager instanceof ListenerAwareInterface &&
            $this instanceof ListenerInterface
        ) {
            $eventManager->attachListener($this);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()/*# : EventManagerInterface */
    {
        // create the default slave
        if (is_null($this->event_manager)) {
            // add classname as a scope
            $this->setEventManager(new EventDispatcher(get_class($this)));
        }
        return $this->event_manager;
    }

    /**
     * {@inheritDoc}
     */
    public function setEventPrototype(EventInterface $eventPrototype)
    {
        $this->event_proto = $eventPrototype;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function trigger(
        /*# string */ $eventName,
        array $parameters = []
    )/*# : bool */ {
        $evt = $this->createEvent($eventName, $parameters);
        return $this->getEventManager()->trigger($evt);
    }

    /**
     * {@inheritDoc}
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $parameters = []
    )/*# : EventInterface */ {
        $evt = $this->createEvent($eventName, $parameters);
        $this->getEventManager()->trigger($evt);
        return $evt;
    }

    /**
     * Create an event with $eventName and parameters
     *
     * @param  string $eventName
     * @param  array $parameters
     * @return EventInterface
     * @access protected
     */
    protected function createEvent(
        /*# string */ $eventName,
        array $parameters
    )/*# : EventInterface */ {
        // get event prototype
        if (is_null($this->event_proto)) {
            return new Event($eventName, $this, $parameters);
        } else {
            // clone the prototype
            $evt = clone $this->event_proto;
            $evt->setName($eventName);
            $evt->setTarget($this);
            $evt->setParams($parameters);
            return $evt;
        }
    }
}
