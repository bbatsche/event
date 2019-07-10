<?php
/**
 * Phossa Project
 *
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event;

use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventManagerInterface;
use Phossa2\Event\Interfaces\EventQueueInterface;
use Phossa2\Shared\Base\ObjectAbstract;

/**
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
 * @see     ObjectAbstract
 * @see     EventManagerInterface
 *
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
        } elseif ($event === '') {
            $this->events = []; // remove all events
        }

        return true;
    }

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
     * @param string|EventInterface $eventName
     * @param object|string|null    $target
     * @param mixed[]               $parameters
     */
    protected function newEvent($eventName, $target = null, array $parameters = []): EventInterface
    {
        return is_object($eventName) ? $eventName : new Event($eventName, $target, $parameters);
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
        return $this->hasEvent($eventName) ? $this->events[$eventName] : $this->newEventQueue();
    }

    /**
     * Remove event or its callable
     *
     * @param string        $eventName
     * @param callable|null $callable
     */
    protected function removeEventCallable(string $eventName, callable $callable = null): void
    {
        if (!isset($callable)) {
            // remove all callables
            $this->events[$eventName]->flush();
        } else {
            // remove one callable
            $this->events[$eventName]->remove($callable);
        }

        // when count is zero, remove queue
        // phpcs:ignore SlevomatCodingStandard.ControlStructures.EarlyExit
        if (count($this->events[$eventName]) === 0) {
            unset($this->events[$eventName]);
        }
    }
}
