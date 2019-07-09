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

use Phossa2\Event\Interfaces\ListenerInterface;

/**
 * ListenerTrait
 *
 * @package Phossa2\Event
 * @see     ListenerInterface
 * @version 2.1.5
 * @since   2.1.3 added
 * @since   2.1.5 accept event names in registerEvent()
 */
trait ListenerTrait
{
    /**
     * Events listening
     */
    protected $events_listening = [];

    /**
     * {@inheritDoc}
     */
    public function eventsListening(): array
    {
        return $this->events_listening;
    }

    /**
     * {@inheritDoc}
     */
    public function registerEvent($eventName, $handler): ListenerInterface
    {
        foreach ((array) $eventName as $evtName) {
            if (!isset($this->events_listening[$evtName])) {
                $this->events_listening[$evtName] = [];
            }

            $this->events_listening[$evtName][] = $handler;
        }

        return $this;
    }
}
