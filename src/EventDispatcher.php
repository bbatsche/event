<?php
/**
 * Phossa Project
 *
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event;

use Phossa2\Event\Interfaces\CountableInterface;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventPrototypeInterface;
use Phossa2\Event\Interfaces\EventQueueInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;
use Phossa2\Event\Traits\CountableTrait;
use Phossa2\Event\Traits\EventPrototypeTrait;
use Phossa2\Event\Traits\ListenerAwareTrait;
use Phossa2\Shared\Globbing\GlobbingTrait;

/**
 * Advanced event manager with:
 *
 * - event name globbing
 * - shared manager support
 * - attach/detach listener
 * - able to trigger an event with countable times
 *
 * @since   2.0.0 added
 * @since   2.1.0 updated
 * @since   2.1.1 added EventPrototype
 */
class EventDispatcher extends EventManager implements
    ListenerAwareInterface,
    CountableInterface,
    EventPrototypeInterface
{
    use GlobbingTrait;
    use CountableTrait;
    use ListenerAwareTrait;
    use EventPrototypeTrait;

    /**
     * Create a event manager with defined scopes
     *
     * @param  string|string[] $scopes
     * @param  EventInterface  $event_proto Event prototype if any
     */
    public function __construct($scopes = '', EventInterface $eventProto = null)
    {
        // set scopes
        if ($scopes !== '') {
            $this->scopes = (array) $scopes;
        }

        // set event prototype
        $this->setEventPrototype($eventProto);
    }

    /**
     * Override `getMatchedQueue()` in EventManager.
     *
     * Support for shared manager & name globbing
     *
     * {@inheritDoc}
     */
    protected function getMatchedQueue(string $eventName): EventQueueInterface
    {
        $nqueue = parent::getMatchedQueue($eventName);

        $nqueue = $nqueue->combine($this->matchEventQueues($eventName));

        return $nqueue;
    }

    /**
     * Get all event names of $this manager
     *
     * @return string[]
     */
    protected function getEventNames(): array
    {
        return array_keys($this->events);
    }

    /**
     * Get a merged queue in $this manager for the given event name
     */
    protected function matchEventQueues(string $eventName): EventQueueInterface
    {
        $nqueue = $this->newEventQueue();
        $names  = $this->globbingNames($eventName, $this->getEventNames());

        foreach ($names as $evtName) {
            if ($this->hasEvent($evtName)) {
                $nqueue = $nqueue->combine($this->events[$evtName]);
            }
        }

        return $nqueue;
    }
}
