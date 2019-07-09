<?php
/**
 * Phossa Project
 *
 * @category  Library
 * @package   Phossa2\Event
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Traits;

use Phossa2\Event\Event;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventPrototypeInterface;

/**
 * EventPrototypeTrait
 *
 * Injecting a event prototype for creating new ones
 *
 * @package Phossa2\Event
 * @version 2.1.1
 * @since   2.1.1 added
 */
trait EventPrototypeTrait
{
    /**
     * event prototype
     *
     * @var    EventInterface
     */
    protected $event_proto;

    /**
     * {@inheritDoc}
     */
    public function setEventPrototype(EventInterface $eventPrototype = null): EventPrototypeInterface
    {
        $this->event_proto = $eventPrototype;

        return $this;
    }

    /**
     * Create an event
     *
     * @param  string|EventInterface $event
     * @param  mixed|null $target
     */
    protected function newEvent($event, $target = null, array $parameters = []): EventInterface
    {
        if (is_object($event)) {
            return $event;
        }

        if (isset($this->event_proto)) {
            // clone the prototype
            $evt = clone $this->event_proto;

            $evt->setName($event);
            $evt->setTarget($target);
            $evt->setParams($parameters);

            return $evt;
        }

        return new Event($event, $target, $parameters);
    }
}
