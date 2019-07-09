<?php
/**
 * Phossa Project
 *
 * @category  Library
 * @package   Phossa2\Event
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event;

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventQueueInterface;
use Phossa2\Event\Interfaces\EventManagerInterface;

/**
 * EventManager
 *
 * A basic implementation of EventManagerInterface
 *
 * ```php
 * $events = new EventManager();
 *
 * $events->attach('test', function() { echo 'test'; });
 *
 * $events->trigger('test');
 * ```
 *
 * @package Phossa2\Event
 * @see     ObjectAbstract
 * @see     EventManagerInterface
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 updated to use the new EventManagerInterface
 */
class EventManager extends ObjectAbstract implements EventManagerInterface
{
    /**
     * Events managing
     *
     * @var    EventQueueInterface[]
     */
    protected $events = [];

    /**
     * {@inheritDoc}
     */
    public function attach(string $event, callable $callback, int $priority = 0): bool
    {
        if (!$this->hasEvent($event)) {
            $this->events[$event] = $this->newEventQueue();
        }

        $this->events[$event]->insert($callback, $priority);

        return true;
    }

    /**
     * if call is NULL, clear this event.
     * if event is '', clear all events
     *
     * {@inheritDoc}
     */
    public function detach(string $event = '', callable $callback = null): bool
    {
        if ($this->hasEvent($event)) {
            $this->removeEventCallable($event, $callback);
        } elseif ('' === $event) {
            $this->events = []; // remove all events
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function clearListeners(string $event): void
    {
        $this->detach($event, null);
    }

    /**
     * {@inheritDoc}
     */
    public function trigger($event, $target = null, array $argv = [])
    {
        // result
        $res = true;

        // make sure is an event
        $evt = $this->newEvent($event, $target, $argv);

        // get handler queue
        $queue = $this->getMatchedQueue($evt->getName());

        foreach ($queue as $q) {
            // execute the handler
            $res = $q['data']($evt);

            // stopped ?
            if ($evt->isPropagationStopped()) {
                break;
            }
        }

        return $res;
    }

    /**
     * Has $eventName been bound ?
     */
    protected function hasEvent(string $eventName): bool
    {
        return isset($this->events[$eventName]);
    }

    /**
     * Create a new event
     *
     * @param  string|EventInterface $eventName
     * @param  object|string|null $target
     */
    protected function newEvent($eventName, $target = null, array $parameters = []): EventInterface
    {
        if (is_object($eventName)) {
            return $eventName;
        } else {
            return new Event($eventName, $target, $parameters);
        }
    }

    /**
     * Get a new event queue
     */
    protected function newEventQueue(): EventQueueInterface
    {
        return new EventQueue();
    }

    /**
     * Get related event handler queue for this $eventName
     */
    protected function getMatchedQueue(string $eventName): EventQueueInterface
    {
        if ($this->hasEvent($eventName)) {
            return $this->events[$eventName];
        } else {
            return $this->newEventQueue();
        }
    }

    /**
     * Remove event or its callable
     *
     * @param  string $eventName
     * @param  callable|null $callable
     * @access protected
     */
    protected function removeEventCallable(string $eventName, callable $callable = null): void
    {
        if (null === $callable) {
            // remove all callables
            $this->events[$eventName]->flush();
        } else {
            // remove one callable
            $this->events[$eventName]->remove($callable);
        }

        // when count is zero, remove queue
        if (count($this->events[$eventName]) === 0) {
            unset($this->events[$eventName]);
        }
    }
}
