<?php
/**
 * Phossa Project
 *
 * @license http://mit-license.org/ MIT License
 */

declare(strict_types=1);

namespace Phossa2\Event\Interfaces;

/**
 * EventManagerInterface
 *
 * @since 2.1.0 added
 */
interface EventManagerInterface
{
    /**
     * Attaches a listener to an event
     *
     * @param string $event    Event to attach too
     * @param int    $priority Priority at which the $callback executed
     *
     * @return bool true on success false on failure
     */
    public function attach(string $event, callable $callback, int $priority = 0): bool;

    /**
     * Detaches a listener from an event
     *
     * @param string $event Event to detach too
     *
     * @return bool true on success false on failure
     */
    public function detach(string $event, callable $callback = null): bool;

    /**
     * Clear all listeners for a given event
     */
    public function clearListeners(string $event): void;

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param string|EventInterface $event
     * @param object|string         $target
     * @param mixed[]               $argv
     *
     * @return mixed
     */
    public function trigger($event, $target = null, array $argv = []);
}
