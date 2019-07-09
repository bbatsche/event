<?php
/**
 * Phossa Project
 *
 * @category  Library
 * @package   Phossa2\Event
 * @license   http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * EventCapableInterface
 *
 * Classes implementing this interface is able to trigger events
 *
 * @package Phossa2\Event
 * @version 2.1.0
 * @see     EventPrototypeInterface
 * @since   2.0.0 added
 * @since   2.1.0 updated
 * @since   2.1.1 added EventPrototypeInterface
 */
interface EventCapableInterface extends EventPrototypeInterface
{
    /**
     * Setup event manager
     *
     * ```php
     * $this->setEventManager(new EventDispatcher());
     * ```
     */
    public function setEventManager(EventManagerInterface $eventManager): self;

    /**
     * Get the event manager. if not set yet, will CREATE ONE by default
     */
    public function getEventManager(): EventManagerInterface;

    /**
     * Trigger an event and return last result from handler
     *
     * @param  array $parameters (optional) custom event parameters if any
     * @return mixed last handler result
     * @throws \Exception if event processing goes wrong
     */
    public function trigger(string $eventName, array $parameters = []);

    /**
     * Trigger an event and processed it by event manager, return the event
     *
     * @param  array $parameters (optional) custom event parameters if any
     * @throws \Exception if event processing goes wrong
     */
    public function triggerEvent(string $eventName, array $parameters = []): EventInterface;
}
