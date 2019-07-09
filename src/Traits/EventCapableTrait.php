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
use Phossa2\Event\EventDispatcher;
use Phossa2\Event\Interfaces\ListenerInterface;
use Phossa2\Event\Interfaces\EventCapableInterface;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventManagerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;

/**
 * Implementation of EventCapableInterface
 *
 * @package Phossa2\Event
 * @see     EventCapableInterface
 * @version 2.1.5
 * @since   2.0.0 added
 * @since   2.1.1 updated
 * @since   2.1.5 added attachSelfToEventManager()
 */
trait EventCapableTrait
{
    use EventPrototypeTrait;

    /**
     * event manager or dispatcher
     *
     * @var    EventManagerInterface
     */
    protected $event_manager;

    /**
     * flag for attachListener
     *
     * @var    bool
     * @since  2.1.5
     */
    protected $listener_attached = false;

    /**
     * {@inheritDoc}
     *
     * @since  2.1.5 moved attachListener to getEventManager
     */
    public function setEventManager(EventManagerInterface $eventManager): EventCapableInterface
    {
        $this->event_manager = $eventManager;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @since  2.1.5 added attachSelfToEventManager()
     */
    public function getEventManager(): EventManagerInterface
    {
        // create the default slave
        if (is_null($this->event_manager)) {
            // add own classname as scope
            $this->setEventManager(new EventDispatcher(get_class($this)));
        }

        // attach self to the event manager if not yet
        $this->attachSelfToEventManager();

        return $this->event_manager;
    }

    /**
     * {@inheritDoc}
     */
    public function trigger(string $eventName, array $parameters = []): bool
    {
        $evt = $this->newEvent($eventName, $this, $parameters);

        return $this->getEventManager()->trigger($evt);
    }

    /**
     * {@inheritDoc}
     */
    public function triggerEvent(string $eventName, array $parameters = []): EventInterface
    {
        $evt = $this->newEvent($eventName, $this, $parameters);
        $this->getEventManager()->trigger($evt);

        return $evt;
    }

    /**
     * Attach $this to the event manager if not yet
     *
     * @since  2.1.5
     */
    protected function attachSelfToEventManager(): void
    {
        if (!$this->listener_attached) {
            if ($this->event_manager instanceof ListenerAwareInterface && $this instanceof ListenerInterface) {
                $this->event_manager->attachListener($this);
            }

            $this->listener_attached = true;
        }
    }
}
