<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * Able to process listener
 *
 * @since 2.0.0 added
 * @since 2.1.0 updated
 */
interface ListenerAwareInterface
{
    /**
     * Attach a listener with all its event handlers
     *
     * @return bool true on success false on failure
     */
    public function attachListener(ListenerInterface $listener): bool;

    /**
     * Detach a listener with all its event handlers or one specific event
     *
     * @return bool true on success false on failure
     */
    public function detachListener(ListenerInterface $listener, string $eventName = ''): bool;
}
