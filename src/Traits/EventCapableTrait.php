<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Traits;

use Phossa2\Event\EventDispatcher;
use Phossa2\Event\Interfaces\EventCapableInterface;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventManagerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;
use Phossa2\Event\Interfaces\ListenerInterface;

/**
 * Implementation of EventCapableInterface
 *
 * @see   EventCapableInterface
 *
 * @since 2.0.0 added
 * @since 2.1.1 updated
 * @since 2.1.5 added attachSelfToEventManager()
 */
trait EventCapableTrait
{
    use EventPrototypeTrait;

    /**
     * Event manager or dispatcher
     *
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Flag for attachListener
     *
     * @var   bool
     *
     * @since 2.1.5
     */
    protected $listenerAttached = false;

    /**
     * {@inheritDoc}
     *
     * @since 2.1.5 moved attachListener to getEventManager
     */
    public function setEventManager(EventManagerInterface $eventManager): EventCapableInterface
    {
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @since 2.1.5 added attachSelfToEventManager()
     */
    public function getEventManager(): EventManagerInterface
    {
        // create the default slave
        if (!isset($this->eventManager)) {
            // add own classname as scope
            $this->setEventManager(new EventDispatcher(static::class));
        }

        // attach self to the event manager if not yet
        $this->attachSelfToEventManager();

        return $this->eventManager;
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
     * @since 2.1.5
     */
    protected function attachSelfToEventManager(): void
    {
        if ($this->listenerAttached) {
            return;
        }

        if ($this->eventManager instanceof ListenerAwareInterface && $this instanceof ListenerInterface) {
            $this->eventManager->attachListener($this);
        }

        $this->listenerAttached = true;
    }
}
