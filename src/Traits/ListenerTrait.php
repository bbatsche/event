<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Traits;

use Phossa2\Event\Interfaces\ListenerInterface;

/**
 * @see   ListenerInterface
 *
 * @since 2.1.3 added
 * @since 2.1.5 accept event names in registerEvent()
 */
trait ListenerTrait
{
    /** @var array<string, string>|array<string, callable>|array<string, array<string|int>> */
    protected $eventsListening = [];

    /**
     * {@inheritDoc}
     */
    public function eventsListening(): array
    {
        return $this->eventsListening;
    }

    /**
     * {@inheritDoc}
     */
    public function registerEvent($eventName, $handler): ListenerInterface
    {
        foreach ((array) $eventName as $evtName) {
            if (!isset($this->eventsListening[$evtName])) {
                $this->eventsListening[$evtName] = [];
            }

            $this->eventsListening[$evtName][] = $handler;
        }

        return $this;
    }
}
