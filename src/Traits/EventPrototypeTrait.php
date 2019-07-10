<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Traits;

use Phossa2\Event\Event;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventPrototypeInterface;

/**
 * Injecting an event prototype for creating new events based on it
 *
 * @since 2.1.1 added
 */
trait EventPrototypeTrait
{
    /** @var EventInterface */
    protected $eventPrototype;

    public function setEventPrototype(EventInterface $eventPrototype = null): EventPrototypeInterface
    {
        $this->eventPrototype = $eventPrototype;

        return $this;
    }

    /**
     * Create an event
     *
     * @param string|EventInterface $event
     * @param mixed|null            $target
     * @param mixed[]               $parameters
     */
    protected function newEvent($event, $target = null, array $parameters = []): EventInterface
    {
        if (is_object($event)) {
            return $event;
        }

        if (isset($this->eventPrototype)) {
            // clone the prototype
            $evt = clone $this->eventPrototype;

            $evt->setName($event);
            $evt->setTarget($target);
            $evt->setParams($parameters);

            return $evt;
        }

        return new Event($event, $target, $parameters);
    }
}
